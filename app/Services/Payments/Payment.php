<?php

namespace App\Services\Payments;

use App\Enum\PaymentStatus;
use App\Models\Payment as ModelsPayment;
use Illuminate\Support\Facades\DB;
use stdClass;

class Payment
{
    public function storePaymentInfo($status, $payInfo, $user, $record, $isPackage)
    {
        try {
            $response = DB::transaction(function () use ($status, $payInfo, $user, $record, $isPackage) {
                if ($status) {
                    //save payments info
                    $payment = ModelsPayment::create([
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

                    $this->handleEnrollments($user, $record, $isPackage, $payment);
                    return apiResponse('Paid successfully');
                } else {

                    // Create a new payment
                    $payment = ModelsPayment::create([
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
        return $response;
    }

    private function handleEnrollments($user, $record, $isPackage, $payment)
    {
        if ($isPackage) {
            foreach ($record->courses as $course) {
                $user->enrollments()->attach($course->id, [
                    'payment_id' => $payment->id,
                    'start_date' => $record->start_date,
                    'end_date' => $record->end_date,
                    'from_package' => $isPackage,
                    'package_id' => $record->id
                ]);
            }
        } else {
            $user->enrollments()->attach($record->id, [
                'payment_id' => $payment->id,
                'start_date' => $record->start_date,
                'end_date' => $record->end_date,
                'from_package' => $isPackage,
                'package_id' => null
            ]);
        }
    }

    public function paymentGateway($cardInfo,$addressInfo,$payInfo)
    {
        $boolean = fake()->boolean(75);
        return $boolean;
    }
    public function validateThePayment($user, $record,$isPackage)
    {
        if($isPackage){
            $existingEnrollment = DB::table('courses_users')
            ->where('user_id', $user->id)
            ->where('package_id', $record->id)
            ->whereDate('start_date','=', $record->start_date)
            ->exists();
        }else{
            $existingEnrollment = DB::table('courses_users')
            ->where('user_id', $user->id)
            ->where('course_id', $record->id)
            ->whereDate('start_date','=', $record->start_date)
            ->exists();
        }
        if ($existingEnrollment) {
            return apiResponse('error', new stdClass(), [__('response.alreadyBuyTheCourse')], 422);
        }
    }
}
