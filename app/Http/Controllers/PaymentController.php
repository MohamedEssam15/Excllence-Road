<?php

namespace App\Http\Controllers;

use App\Enum\PaymentStatus;
use App\Http\Requests\PaymentRequest;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Package;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class PaymentController extends Controller
{
    public function pay(PaymentRequest $request)
    {
        $user = auth('api')->user();
        $className = 'App\\Models\\' . ucfirst($request->paymentFor);
        $record = $className::where('id', $request->paymentForId)->first();
        $validationResult = $this->validateThePayment($user, $record, $className);
        if ($validationResult !== null) {
            return $validationResult;
        }

        $payInfo = [
            'name' => $record->translate(config('app.locale'))->name,
            'price' => $record->price,
        ];
        $status = $this->paymentGateway($request->cardInfo, $request->addressInfo, $payInfo);
        try {
            DB::transaction(function () use ($status, $payInfo, $user, $record) {
                if ($status) {
                    $payment = Payment::create([
                        'amount' => $payInfo['price'],
                        'status' => PaymentStatus::Done,
                        'user_id' => $user->id,
                    ]);
                    $translations = [
                        ['locale' => 'en', 'status' => PaymentStatus::Done],
                        ['locale' => 'ar', 'status' => "مكتمله"],
                    ];
                    $payment->translations()->createMany($translations);
                    $payment->save();

                    $enrollment = new Enrollment();
                    $enrollment->student()->associate($user);
                    $enrollment->enrollable()->associate($record);
                    $enrollment->payment()->associate($payment);
                    $enrollment->save();
                } else {
                    // Create a new payment
                    $payment = Payment::create([
                        'amount' => $payInfo['price'],
                        'status' => PaymentStatus::Failed,
                        'user_id' => $user->id,
                    ]);
                    $translations = [
                        ['locale' => 'en', 'status' => PaymentStatus::Failed],
                        ['locale' => 'ar', 'status' => "فشلت"],
                    ];
                    $payment->translations()->createMany($translations);
                    $payment->save();
                    return apiResponse('error', new stdClass(), ['Card Decline'], 422);
                }
            }, 2);
        } catch (\Exception $e) {
            return apiResponse('error', new stdClass(), [$e->getMessage()], 500);
        }
        return apiResponse('Paid successfully');
    }

    private function paymentGateway($creditCardInfo, $addressInfo, $payInfo)
    {
        $boolean = fake()->boolean(75);
        return $boolean;
    }
    private function validateThePayment($user, $record, $class)
    {
        $existingEnrollment = Enrollment::where('user_id', $user->id)
            ->where('enrollable_id', $record->id)
            ->where('enrollable_type', $class)
            ->first();
        if (! is_null($existingEnrollment)) {
            return apiResponse('error', new stdClass(), [__('response.alreadyBuyTheCourse')], 422);
        }
    }
}
