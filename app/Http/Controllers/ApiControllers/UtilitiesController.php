<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UtilitiesController extends Controller
{
    public function configuration()
    {
        $configuration = [
            "configurations" => [
                "minimumBuildNumberAndroid" => 1,
                "minimumBuildNumberIos" => 1,
                "appAndroidUrl" => "LINK_HERE",
                "appIosUrl" => "LINK_HERE",
                "appUnderMaintenance" => false,
                "facebookLink" => "https://www.facebook.com",
                "instagramLink" => "https://www.instagram.com",
                "whatsappNumber" => "+2015**",
                "phoneNumber" => "+2015**",
                "privacyPolicy" => "Privacy Policy Text Here",
                "termsOfUse" => "Terms Of Use Text Here",
            ]
        ];

        return response()->json($configuration);
    }
}
