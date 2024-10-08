<?php

namespace App\Http\Controllers\ApiControllers\TeacherPanalControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddLessonRequest;
use App\Http\Requests\UpdateLessonRequest;
use App\Http\Resources\TeacherCourseInfoResource;
use App\Http\Resources\TeacherLessonInfoResource;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonAttachment;
use App\Services\Courses\LessonServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use stdClass;

class LessonsController extends Controller
{

    public function addLesson(AddLessonRequest $request){

        $lessonService = new LessonServices();
        $lesson = $lessonService->saveLesson($request->type,$request->enName,$request->arName,$request->enDescription,$request->arDescription,$request->unitId,$request->order,$request->meetingLink,$request->video,$request->attachments);
        return apiResponse(__('response.addedSuccessfully'), new TeacherLessonInfoResource($lesson));
    }

    public function updateLesson(UpdateLessonRequest $request,Lesson $lesson){
        $lessonService = new LessonServices();
        $updatedLesson = $lessonService->updateLesson($lesson,$request->enName,$request->arName,$request->enDescription,$request->arDescription,$request->order,$request->type,$request->video,$request->meetingLink);
        if(is_null($lesson)){
            return apiResponse(__('response.invalidTypeSelected'), new stdClass(),[__('response.invalidTypeSelected')],422);
        }
        return apiResponse(__('response.updatedSuccessfully'), new TeacherLessonInfoResource($updatedLesson));
    }
    public function deleteLesson(Lesson $lesson){
        if($lesson->unit->course->teacher_id != auth()->id()){
            return apiResponse(__('response.notAuthorized'), new stdClass(),[__('response.notAuthorized')],401);
        }
        $lessonService = new LessonServices();
        $lessonService->deleteLesson($lesson);
        return apiResponse(__('response.deletedSuccessfully'));
    }

    public function addLessonAttachment(Request $request,Lesson $lesson){
        $request->validate([
            'attachmentFiles' => ['required', 'array'],
            'attachmentFiles.*' => 'required|file|max:10240',
        ]);
        $lessonService = new LessonServices();
        $response = $lessonService->addLessonAttachments($lesson,$request->attachmentFiles);
        return $response;
    }

    public function deleteLessonAttachment($id){
        $lessonAttachment = LessonAttachment::findOrFail($id);
        if($lessonAttachment->lesson->unit->course->teacher_id != auth()->id()){
            return apiResponse("error", new stdClass(),[__('response.notAuthorized')],401);
        }

        $deleteAttachmentPath = 'lessons/lessons_attachments/' . $lessonAttachment->lesson_id . '/' . $lessonAttachment->name;
        Storage::disk('public')->delete($deleteAttachmentPath);
        $lessonAttachment->delete();
        return apiResponse(__('response.deletedSuccessfully'));
    }
}
