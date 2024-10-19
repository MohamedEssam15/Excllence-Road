<?php

namespace App\Http\Controllers\ApiControllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiLoginRequest;
use App\Http\Requests\RegisterTeacherRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\StudentResource;
use App\Http\Resources\TeacherResource;
use App\Mail\ResetPasswordEmail;
use App\Mail\VerifyEmailCodeMail;
use App\Models\User;
use App\Models\UserAttchment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use stdClass;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'sendResetCode', 'resetWithCode', 'teacherRegister']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(ApiLoginRequest $request)
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth('api')->attempt($credentials, request('rememberMe'))) {
            return apiResponse(__('auth.failed'), new stdClass(), [__('auth.failed')], 401);
        }
        $user = auth()->user();
        if (! $user->is_active) {
            return apiResponse(__('auth.notActive'), new stdClass(), [__('auth.notActive')], 401);
        }

        if (! $user->hasAnyRole('student', 'teacher')) {
            Auth::logout();
            return apiResponse(__('auth.loginRoleError'), new stdClass(), [__('auth.loginRoleError')], 401);
        }

        return $this->respondWithToken($token, $user);
    }

    public function register(RegisterUserRequest $request)
    {

        $image_parts = explode(";base64,", $request->photo);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image = base64_decode($image_parts[1]);
        $imageName = Str::random(10) . '.' . $image_type;
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'avatar' => $imageName,
            'is_active' => true
        ]);
        $user->assignRole('student');
        $path = 'users_attachments/' . $user->id . '/avatar/' . $imageName;
        Storage::disk('publicFolder')->put($path, $image);
        $token = auth()->login($user, true);
        return $this->respondWithToken($token, $user);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = auth()->user();
        if ($user->hasRole('teacher')) {
            $resource = new TeacherResource($user);
        } else {
            $resource = new StudentResource($user);
        }
        return apiResponse('Data Retrieved', $resource);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        if (is_null(auth()->user())) {
            return apiResponse(__('auth.logoutError'), new stdClass(), [__('auth.logoutError')], 401);
        }
        auth()->logout();
        return apiResponse(__('auth.logout'));
    }

    public function sendResetCode(Request $request)
    {
        // Validate the email
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return apiResponse('error', new stdClass(), $validator->errors()->all(), 422);
        }
        $user = User::where('email', $request->email)->first();
        // Generate a 4-digit code
        $code = rand(1000, 9999);

        // Store the code in the database with the email and timestamp
        DB::table('password_resets_jwt')->updateOrInsert(
            ['email' => $request->email],
            ['code' => $code, 'created_at' => Carbon::now()]
        );
        if (! is_null($user)) {
            Mail::to($request->email)->send(new ResetPasswordEmail($code));
        }


        return apiResponse(__('passwords.sent'));
    }

    public function resetWithCode(Request $request)
    {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'code' => 'required|numeric',
            'password' => 'required|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return apiResponse('error', new stdClass(), $validator->errors()->all(), 422);
        }

        // Check if the code is valid and not expired (e.g., 10 minutes expiry)
        $resetEntry = DB::table('password_resets_jwt')
            ->where('email', $request->email)
            ->where('code', $request->code)
            ->where('created_at', '>=', Carbon::now()->subMinutes(10))
            ->first();

        if (!$resetEntry) {
            return apiResponse(__('passwords.token'), new stdClass(), [__('passwords.token')], 422);
        }

        // Reset the user's password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();
        // Delete the reset code from the database
        DB::table('password_resets_jwt')->where('email', $request->email)->delete();

        return apiResponse(__('passwords.reset'));
    }


    public function sendVerificationCode(Request $request)
    {
        $user = auth('api')->user();
        if ($user->email_verified_at != null) {
            return apiResponse(__('response.emailVerfiedAlready'), new stdClass(), [__('response.emailVerfiedAlready')], 422);
        }

        $code = rand(1000, 9999);
        DB::table('password_resets_jwt')->updateOrInsert(
            ['email' => $user->email],
            ['code' => $code, 'created_at' => Carbon::now()]
        );
        Mail::to($user->email)->send(new VerifyEmailCodeMail($code));
        return apiResponse(__('passwords.sendVerficationEmail'));
    }

    /**
     * Verify the email with the provided code.
     */
    public function verifyEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return apiResponse('error', new stdClass(), $validator->errors()->all(), 422);
        }

        // Find user
        $user = auth()->user();
        $resetEntry = DB::table('password_resets_jwt')
            ->where('email', $user->email)
            ->where('code', $request->code)
            ->where('created_at', '>=', Carbon::now()->subMinutes(10))
            ->first();

        if (!$resetEntry) {
            return apiResponse(__('passwords.emailToken'), new stdClass(), [__('passwords.emailToken')], 422);
        }

        $user->email_verified_at = now();
        $user->save();

        DB::table('password_resets_jwt')->where('email', $request->email)->delete();
        return apiResponse(__('passwords.emailVerfied'));
    }

    public function delete()
    {
        $user = auth()->user();
        auth()->logout();
        if ($user->hasRole('teacher')) {
            $user->delete();
        } else {
            $deletePath = "/users_attachments/{$user->id}";
            Storage::disk('publicFolder')->deleteDirectory($deletePath);
            $user->forceDelete();
        }
        return apiResponse(__('response.deleteUser'), new stdClass());
    }

    public function teacherRegister(RegisterTeacherRequest $request)
    {
        if ($request->file('photo')->isValid()) {
            $file = $request->file('photo');
            $fileExtension = $file->getClientOriginalExtension();
            $fileName = Str::random(10) . '.' . $fileExtension;
        } else {
            return apiResponse(__('response.fileDamaged'), new stdClass(), [__('response.fileDamaged')]);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'avatar' => $fileName,
            'is_active' => false
        ]);
        $user->assignRole('teacher');
        $path = 'users_attachments/' . $user->id . '/avatar/';
        $file->storeAs($path, $fileName, 'publicFolder');
        //saving certificates
        if (isset($request->certificates)) {
            foreach ($request->certificates as $certificate) {
                $file = $certificate;
                $fileExtension = $file->getClientOriginalExtension();
                $fileName = Str::random(10) . '.' . $fileExtension;

                $this->saveTeacherAttachment($user->id, $fileName, $file);
            }
        }

        return apiResponse(__('response.teacherSignedUp'), new stdClass());
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $user = null)
    {
        $resource = null;
        if ($user->hasRole('teacher')) {
            $resource = new TeacherResource($user);
        } else {
            $resource = new StudentResource($user);
        }
        $response = [
            'access_token' => $token,
            'user' => $resource,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
        return apiResponse('login successfully', $response);
    }

    protected function saveTeacherAttachment($userId, $attachName, $file)
    {
        $path = 'users_attachments/' . $userId . '/attachments/';
        $userAttachment = UserAttchment::create([
            'name' => $attachName,
            'user_id' => $userId,
        ]);
        $file->storeAs($path, $attachName, 'publicFolder');
    }
}
