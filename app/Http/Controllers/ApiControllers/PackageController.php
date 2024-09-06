<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\PackageCoursesResource;
use App\Http\Resources\PackageResource;
use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Http\Request;
use stdClass;

class PackageController extends Controller
{
    public function getPackages(){
        $packages = Package::where('start_date', '>=', Carbon::today())->get();
        // ds($packages)->die();
        if (!isset($packages[0])) {
            return apiResponse(__('noPackages'), new stdClass(), [__('noPackages')], 404);
        }

        return apiResponse('Data Retrieved', PackageResource::collection($packages));
    }
    public function show($id){
        $package = Package::where('start_date', '>=', Carbon::today())->where('id',$id)->first();
        // ds($packages)->die();
        if (is_null($package)) {
            return apiResponse(__('noPackages'), new stdClass(), [__('noPackages')], 404);
        }

        return apiResponse('Data Retrieved', new PackageCoursesResource($package));
    }
}
