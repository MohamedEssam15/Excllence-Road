<?php

namespace App\Http\Controllers\ApiControllers;

use App\Enum\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Package;
use App\Models\Payment;
use App\Services\Payments\Payment as PaymentsPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class PaymentController extends Controller
{
    public function pay(PaymentRequest $request)
    {
        $user = auth('api')->user();
        if($request->paymentFor == 'package'){
            $isPackage = true;
            $record = Package::where('id', $request->paymentForId)->first();
        }else{
            $isPackage = false;
            $record = Course::where('id', $request->paymentForId)->first();
        }
        $paymentServices = new PaymentsPayment();
        $validationResult = $paymentServices->validateThePayment($user, $record, $isPackage);
        if ($validationResult !== null) {
            return $validationResult;
        }

        $payInfo = [
            'name' => $record->translate(config('app.locale'))->name,
            'price' => $record->price,
        ];

        
        $status = $paymentServices->paymentGateway($request->cardInfo, $request->addressInfo, $payInfo);
        $response = $paymentServices->storePaymentInfo($status,$payInfo,$user,$record,$isPackage);
        return $response;

    }
}
