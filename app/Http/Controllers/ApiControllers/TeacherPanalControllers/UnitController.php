<?php

namespace App\Http\Controllers\ApiControllers\TeacherPanalControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddUnitsRequest;
use App\Http\Requests\UpdateUnitRequest;
use App\Http\Resources\TeacherCourseInfoResource;
use App\Http\Resources\TeacherUnitInfoResource;
use App\Http\Resources\UnitInfoResource;
use App\Models\Course;
use App\Models\Unit;
use App\Services\Courses\CourseServices;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function addUnits(AddUnitsRequest $request, Course $course)
    {
        $courseServices = new CourseServices();
        $courseServices->addUnits($request->units, $course->id);
        $course->refresh();
        return apiResponse(__('response.addedSuccessfully'), ['units' => TeacherUnitInfoResource::collection($course->units)]);
    }
    public function updateUnit(UpdateUnitRequest $request, Course $course, Unit $unit)
    {
        $courseServices = new CourseServices();
        $unit = $courseServices->updateUnit($request->all(), $course, $unit);
        return apiResponse(__('response.updatedSuccessfully'), new TeacherUnitInfoResource($unit));
    }
    public function deleteUnit(Course $course, Unit $unit)
    {
        $courseServices = new CourseServices();
        $response = $courseServices->deleteUnit($course, $unit);
        return $response;
    }
}
