<?php

namespace App\Services\Payments;

use App\Enum\DiscountTypes;
use App\Enum\PaymentStatus;
use App\Models\Course;
use App\Models\Order;
use App\Models\Package;
use App\Models\Payment as ModelsPayment;
use App\Models\TeacherRevenues;
use Illuminate\Support\Facades\DB;
use stdClass;
use Illuminate\Support\Str;

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
                    if ($isPackage) {
                        Order::create([
                            'order_number' => Str::upper(Str::random(3)) . str_pad(mt_rand(0, 99999), 4, '0', STR_PAD_LEFT),
                            'student_id' => $user->id,
                            'is_package' => $isPackage,
                            'package_id' => $record->id,
                            'course_id' => null,
                            'payment_id' => $payment->id
                        ]);
                    } else {
                        Order::create([
                            'order_number' => Str::upper(Str::random(3)) . str_pad(mt_rand(0, 99999), 4, '0', STR_PAD_LEFT),
                            'student_id' => $user->id,
                            'is_package' => $isPackage,
                            'package_id' => null,
                            'course_id' => $record->id,
                            'payment_id' => $payment->id
                        ]);
                    }

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
            $order = Order::create([
                'order_number' => Str::upper(Str::random(3)) . str_pad(mt_rand(0, 99999), 4, '0', STR_PAD_LEFT),
                'student_id' => $user->id,
                'is_package' => $isPackage,
                'package_id' => $record->id,
                'discount' => $record->discount,
                'discount_type' => $record->discount_type,
                'added_by' => null,
                'course_id' => null,
                'payment_id' => $payment->id
            ]);
            foreach ($record->courses as $course) {
                $user->enrollments()->attach($course->id, [
                    'payment_id' => $payment->id,
                    'start_date' => $record->start_date,
                    'end_date' => $record->end_date,
                    'from_package' => $isPackage,
                    'package_id' => $record->id
                ]);
                $revenue = ($course->new_price ?? $course->price) * ($record->teacher_commission ?? 10) / 100;
                TeacherRevenues::create([
                    "teacher_id" => $course->teacher_id,
                    "order_id" => $order->id,
                    "revenues" => $revenue,
                ]);
            }
        } else {
            $order = Order::create([
                'order_number' => Str::upper(Str::random(3)) . str_pad(mt_rand(0, 99999), 4, '0', STR_PAD_LEFT),
                'student_id' => $user->id,
                'is_package' => $isPackage,
                'package_id' => null,
                'discount' => $record->discount,
                'discount_type' => $record->discount_type,
                'added_by' => null,
                'course_id' => $record->id,
                'payment_id' => $payment->id
            ]);
            $user->enrollments()->attach($record->id, [
                'payment_id' => $payment->id,
                'start_date' => $record->start_date,
                'end_date' => $record->end_date,
                'from_package' => $isPackage,
                'package_id' => null
            ]);
            $revenue = ($record->new_price ?? $record->price) * (($record->teacher_commission ?? 10) / 100);
            TeacherRevenues::create([
                "teacher_id" => $record->teacher_id,
                "order_id" => $order->id,
                "revenues" => $revenue,
            ]);
        }
    }

    public function paymentGateway($cardInfo, $addressInfo, $payInfo)
    {
        $boolean = fake()->boolean(75);
        return $boolean;
    }
    public function validateThePayment($user, $record, $isPackage)
    {
        if ($isPackage) {
            $existingEnrollment = DB::table('courses_users')
                ->where('user_id', $user->id)
                ->where('package_id', $record->id)
                ->whereDate('start_date', '=', $record->start_date)
                ->exists();
        } else {
            $existingEnrollment = DB::table('courses_users')
                ->where('user_id', $user->id)
                ->where('course_id', $record->id)
                ->whereDate('start_date', '=', $record->start_date)
                ->exists();
        }
        if ($existingEnrollment) {
            return apiResponse('error', new stdClass(), [__('response.alreadyBuyTheCourse')], 422);
        }
    }

    public function createFreePayment($user, $record, $isPackage, $discountPercentage)
    {
        //calcaulate amount
        if ($record->new_price == null) {
            $amount = $record->price - (($record->price * $discountPercentage) / 100);
        } else {
            $amount = $record->new_price - (($record->new_price * $discountPercentage) / 100);
        }

        //save payment
        $payment = ModelsPayment::create([
            'amount' => $amount,
            'status' => PaymentStatus::Done,
            'user_id' => $user->id,
        ]);
        $translations = [
            ['locale' => 'en', 'status' => PaymentStatus::Done],
            ['locale' => 'ar', 'status' => "مكتمله"],
        ];
        $payment->translations()->createMany($translations);
        $payment->save();

        $this->handleFreeEnrollments($user, $record, $isPackage, $payment, $discountPercentage);
    }
    public function handleFreeEnrollments($user, $record, $isPackage, $payment, $discountPercentage)
    {
        if ($isPackage) {
            $order = Order::create([
                'order_number' => Str::upper(Str::random(3)) . str_pad(mt_rand(0, 99999), 4, '0', STR_PAD_LEFT),
                'student_id' => $user->id,
                'is_package' => $isPackage,
                'package_id' => $record->id,
                'discount' => $discountPercentage,
                'discount_type' => DiscountTypes::PERCENTAGE,
                'added_by' => auth()->user()->id,
                'course_id' => null,
                'payment_id' => $payment->id
            ]);
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
            $order = Order::create([
                'order_number' => Str::upper(Str::random(3)) . str_pad(mt_rand(0, 99999), 4, '0', STR_PAD_LEFT),
                'student_id' => $user->id,
                'is_package' => $isPackage,
                'package_id' => null,
                'discount' => $discountPercentage,
                'discount_type' => DiscountTypes::PERCENTAGE,
                'added_by' => auth()->user()->id,
                'course_id' => $record->id,
                'payment_id' => $payment->id
            ]);
            $user->enrollments()->attach($record->id, [
                'payment_id' => $payment->id,
                'start_date' => $record->start_date,
                'end_date' => $record->end_date,
                'from_package' => $isPackage,
                'package_id' => null
            ]);
        }
    }
}
