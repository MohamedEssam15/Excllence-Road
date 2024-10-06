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
    public function getPackages()
    {
        $packages = Package::where('start_date', '>=', Carbon::today())->get();
        if (!isset($packages[0])) {
            return apiResponse(__('response.noPackages'), new stdClass(), [__('response.noPackages')]);
        }

        return apiResponse('Data Retrieved', PackageResource::collection($packages));
    }
    public function show($id)
    {
        $package = Package::where('start_date', '>=', Carbon::today())->where('id', $id)->first();
        if (is_null($package)) {
            return apiResponse(__('response.noPackages'), new stdClass(), [__('response.noPackages')]);
        }

        return apiResponse('Data Retrieved', new PackageCoursesResource($package));
    }

    public function getPopularPackages()
    {
        $packages = Package::where('start_date', '>=', Carbon::today())->where('is_popular', true)->get();
        if (!isset($packages[0])) {
            return apiResponse(__('response.noPackages'), new stdClass(), [__('response.noPackages')]);
        }

        return apiResponse('Data Retrieved', PackageResource::collection($packages));
    }
}
