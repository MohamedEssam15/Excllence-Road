<?php

namespace App\Http\Controllers;

use App\Http\Resources\FeatureContentResource;
use App\Models\FeatureContent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class FeatureContentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'nullable|string',
            'coverPhoto' => 'nullable|file|image|max:2048', // Ensure photo is a valid image
            'coverVideo' => 'nullable|file|mimes:mp4,avi,mkv|max:51200', // Ensure video is a valid format
            'type' => 'required|string|in:photo,video',
            'isCourse' => 'required|boolean',
            'isPackage' => 'required|boolean',
            'courseId' => 'nullable|exists:courses,id',
            'packageId' => 'nullable|exists:packages,id',
        ]);

        $relatedModelType = null;
        if ($validated['isCourse'] && $validated['courseId']) {
            $relatedModelType = 'course';
        } elseif ($validated['isPackage'] && $validated['packageId']) {
            $relatedModelType = 'package';
        }
        // Create Feature Content
        $featureContent = FeatureContent::create([
            'subject' => $validated['subject'],
            'cover_photo' => null,
            'cover_video' => null,
            'course_id' => $validated['courseId'],
            'package_id' => $validated['packageId'],
            'modelable_type' => $relatedModelType,
        ]);

        if ($request->hasFile('coverVideo')) {
            $videoFileExtension = $request->file('coverVideo')->getClientOriginalExtension();
            $videoFileName = Str::random(10) . '.' . $videoFileExtension;
            $videoPath = 'feature_content/' . $featureContent->id . '/cover_video/';
            Storage::disk('publicFolder')->putFileAs($videoPath, $request->file('coverVideo'), $videoFileName);
            $featureContent->cover_video = $videoFileName;
            $featureContent->type = 'video';
        } else {
            $photoFileExtension = $request->file('coverPhoto')->getClientOriginalExtension();
            $photoFileName = Str::random(10) . '.' . $photoFileExtension;
            $photoPath = 'feature_content/' . $featureContent->id . '/cover_photo/';
            Storage::disk('publicFolder')->putFileAs($photoPath, $request->file('coverPhoto'), $photoFileName);
            $featureContent->cover_photo = $photoFileName;
            $featureContent->type = 'photo';
        }
        $featureContent->save();

        return response()->json([
            'message' => 'Feature content created successfully!',
            'featureContent' => $featureContent,
        ], 201);
    }

    public function getFeatureContent()
    {
        $featureContent = FeatureContent::all();
        return apiResponse(__('response.dataRetrieved'), ['featureContent' => FeatureContentResource::collection($featureContent)]);
    }
}
