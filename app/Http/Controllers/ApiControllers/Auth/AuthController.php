<?php

namespace App\Http\Controllers\ApiControllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiLoginRequest;
use App\Http\Resources\StudentResource;
use App\Models\User;
use Illuminate\Http\Request;
use stdClass;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth:api', ['except' => ['login']]);
    // }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(ApiLoginRequest $request)
    {
        $credentials = request(['email', 'password']);
        $credentials['is_active']=true;

        if (! $token = auth('api')->attempt($credentials,request('rememberMe'))) {
            return apiResponse(__('auth.notActive'),new stdClass(),[__('auth.notActive')],401);
        }
        $user = User::where('email', request('email'))->first();

        return $this->respondWithToken($token,$user);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        if(is_null(auth()->user())){
            return apiResponse(__('auth.logoutError'),new stdClass(),[__('auth.logoutError')],401);
        }
        auth()->logout();
        return apiResponse(__('auth.logout'));
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token,$user= null)
    {
        $response =[
            'access_token' => $token,
            'user'=>new StudentResource($user),
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
        return apiResponse('login successfully',$response);
    }
}
