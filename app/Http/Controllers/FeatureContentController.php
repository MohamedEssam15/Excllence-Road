<?php

namespace App\Http\Controllers;

use App\Enum\CourseStatus;
use App\Http\Requests\AddFeatureContentRequest;
use App\Http\Resources\CourseBasicInfoResource;
use App\Http\Resources\FeatureContentResource;
use App\Http\Resources\PackageResource;
use App\Models\Course;
use App\Models\FeatureContent;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class FeatureContentController extends Controller
{
    public function index(Request $request)
    {
        $term = $request->get('query') ?? '';
        if ($request->ajax()) {
            $contents = FeatureContent::where('subject', 'LIKE', '%' . $term . '%')->orderBy('updated_at', 'desc')->get();
            return response()->json([
                'table_data' => view('feature-content.Partial-Components.all-partial-table', compact('contents'))->render(),
            ]);
        }

        return view('feature-content.index');
    }

    public function create()
    {
        return view('feature-content.create');
    }

    public function getCourses()
    {
        $courses = Course::where('status_id', CourseStatus::ACTIVE)->get();
        return apiResponse(__('response.dataRetrieved'),  CourseBasicInfoResource::collection($courses));
    }

    public function getPackages()
    {
        $packages = Package::all();
        return apiResponse(__('response.dataRetrieved'),  PackageResource::collection($packages));
    }

    public function store(AddFeatureContentRequest $request)
    {
        ds($request);
        $relatedModelType = null;
        $courseId = null;
        $packageId = null;
        if ($request->assignToType == 'course') {
            $relatedModelType = 'course';
            $courseId = $request->assignTo;
        } elseif ($request->assignToType == 'package') {
            $relatedModelType = 'package';
            $packageId = $request->assignTo;
        }
        // Create Feature Content
        $featureContent = FeatureContent::create([
            'subject' => $request->subject,
            'cover_photo' => null,
            'cover_video' => null,
            'course_id' => $courseId,
            'package_id' => $packageId,
            'modelable_type' => $relatedModelType,
        ]);

        if ($request->contentType == 'video') {
            $videoFileExtension = $request->file('uploadedFile')->getClientOriginalExtension();
            $videoFileName = Str::random(10) . '.' . $videoFileExtension;
            $videoPath = 'feature_content/' . $featureContent->id . '/cover_video/';
            Storage::disk('publicFolder')->putFileAs($videoPath, $request->file('uploadedFile'), $videoFileName);
            $featureContent->cover_video = $videoFileName;
            $featureContent->type = 'video';
        } else {
            $photoFileExtension = $request->file('uploadedFile')->getClientOriginalExtension();
            $photoFileName = Str::random(10) . '.' . $photoFileExtension;
            $photoPath = 'feature_content/' . $featureContent->id . '/cover_photo/';
            Storage::disk('publicFolder')->putFileAs($photoPath, $request->file('uploadedFile'), $photoFileName);
            $featureContent->cover_photo = $photoFileName;
            $featureContent->type = 'photo';
        }
        $featureContent->save();

        return redirect()->route('featureContent.all')->with('status', __('response.addedSuccessfully'));
    }

    public function getFeatureContent()
    {
        $featureContent = FeatureContent::all();
        return apiResponse(__('response.dataRetrieved'), ['featureContent' => FeatureContentResource::collection($featureContent)]);
    }
    public function delete(Request $request)
    {
        $featureContent = FeatureContent::find($request->contentId);
        Storage::disk('publicFolder')->deleteDirectory('feature_content/' . $featureContent->id);
        $featureContent->delete();
        return apiResponse(__('response.deletedSuccessfully'));
    }
}
