<?php

namespace App\Services\Courses;

use App\Models\Lesson as ModelsLesson;
use App\Models\LessonAttachment;
use App\Services\VideoServices\VideoStorageManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;



class LessonServices
{
    protected $updateLessonHandlers = [
        'meeting-video' => 'handleMeetingVideo',
        'meeting-meeting' => 'handleMeetingMeeting',
        'video-meeting' => 'handleVideoMeeting',
        'video-video' => 'handleVideoVideo',
    ];

    public function saveLesson($type, $enName, $arName, $enDescription, $arDescription, $unitId, $order, $meetingLink = null, $video = null, $attachments = null)
    {
        if ($type == 'meeting') {
            $lesson = $this->saveMeetingLesson($enName, $enDescription, $unitId, $order, $meetingLink);
        } else {
            $lesson = $this->saveVideoLesson($enName, $enDescription, $unitId, $order, $video);
        }

        $lessonTranslations = [
            ['locale' => 'en', 'name' => ucfirst($enName), 'description' => $enDescription],
            ['locale' => 'ar', 'name' => $arName, 'description' => $arDescription],
        ];
        $lesson->translations()->createMany($lessonTranslations);

        if (isset($attachments)) {
            foreach ($attachments as $attachment) {
                $file = $attachment;
                $fileName = $attachment->getClientOriginalName();
                $this->saveLessonAttachments($lesson->id, $fileName, $file);
            }
        }
        return $lesson;
    }

    protected function saveMeetingLesson($enName, $enDescription, $unitId, $order, $meetingLink)
    {
        $lesson = ModelsLesson::create([
            'name' => $enName,
            'unit_id' => $unitId,
            'description' => $enDescription,
            'order' => $order,
            'type' => 'meeting',
            'video_link' => $meetingLink,
        ]);

        return $lesson;
    }

    protected function saveVideoLesson($enName, $enDescription, $unitId, $order, $video)
    {
        $fileExtension = $video->getClientOriginalExtension();
        $fileName = Str::random(10) . '.' . $fileExtension;
        $lesson = ModelsLesson::create([
            'name' => $enName,
            'unit_id' => $unitId,
            'description' => $enDescription,
            'order' => $order,
            'type' => 'video',
            'video_link' => $fileName,
        ]);

        $videoStorageManger = new VideoStorageManager();
        $path = '/lessons/lessons_videos/' . $lesson->id;

        $videoStorageManger->upload($video, $path, $fileName);
        return $lesson;
    }

    protected function saveLessonAttachments($lessonId, $attachName, $file)
    {
        $path = '/lessons/lessons_attachments/' . $lessonId . '/';
        $lessonAttachment = LessonAttachment::create([
            'name' => $attachName,
            'lesson_id' => $lessonId,
        ]);
        $file->storeAs($path, $attachName, 'public');
    }

    public function addLessonAttachments($lesson, $files)
    {
        $path = '/lessons/lessons_attachments/' . $lesson->id . '/';
        foreach ($files as $file) {
            $lessonAttachment = LessonAttachment::create([
                'name' => $file->getClientOriginalName(),
                'lesson_id' => $lesson->id,
            ]);
            $file->storeAs($path, $file->getClientOriginalName(), 'public');
        }

        return apiResponse(__('response.addedSuccessfully'));
    }

    public function deleteLessonAttachment($attachment)
    {
        $deleteAttachmentPath = 'lessons/lessons_attachments/' . $attachment->lesson_id . '/' . $attachment->name;
        Storage::disk('public')->deleteDirectory($deleteAttachmentPath);
        return apiResponse(__('response.deletedSuccessfully'));
    }

    //working on updating
    public function updateLesson($lesson, $enName, $arName, $enDescription, $arDescription, $order, $type = null, $video = null, $meetingLink = null)
    {
        if ($type != null) {
            $newType = $type;
            $oldType = $lesson->type;

            $key = "{$oldType}-{$newType}";

            if (!array_key_exists($key, $this->updateLessonHandlers)) {
                return null;
            }

            $handlerMethod = $this->updateLessonHandlers[$key];
            $this->$handlerMethod($lesson, $meetingLink, $video);
        }
        $lesson->name = $enName;
        $lesson->description = $enDescription;
        $lesson->order = $order;
        $lesson->save();
        $lessonTranslations = [
            ['locale' => 'en', 'name' => ucfirst($enName), 'description' => $enDescription],
            ['locale' => 'ar', 'name' => $arName, 'description' => $arDescription],
        ];
        $lesson->translations()->delete();
        $lesson->translations()->createMany($lessonTranslations);
        return $lesson;
    }

    public function deleteLesson($lesson)
    {
        if ($lesson->type == 'video') {
            //delete the directory of lesson video
            $videoManger = new VideoStorageManager();
            $videoManger->deleteDirectory($lesson->id);
        }
        if (isset($lesson->attachments[0])) {
            $deleteAttachmentPath = 'lessons/lessons_attachments/' . $lesson->id . '/';
            Storage::disk('public')->deleteDirectory($deleteAttachmentPath);
        }

        $lesson->delete();
    }

    private function handleMeetingVideo($lesson, $meetingLink = null, $video = null)
    {
        $fileExtension = $video->getClientOriginalExtension();
        $fileName = Str::random(10) . '.' . $fileExtension;
        $lesson->type = 'video';
        $lesson->video_link = $fileName;

        $videoStorageManger = new VideoStorageManager();
        $path = '/lessons/lessons_videos/' . $lesson->id;

        $videoStorageManger->upload($video, $path, $fileName);
        return $lesson;
    }

    private function handleMeetingMeeting($lesson, $meetingLink = null, $video = null)
    {
        $lesson->video_link = $meetingLink;
    }

    private function handleVideoMeeting($lesson, $meetingLink = null, $video = null)
    {
        $lesson->video_link = $meetingLink;
        $lesson->type = 'meeting';

        $videoManger = new VideoStorageManager();
        $videoManger->deleteDirectory($lesson->id);
    }

    private function handleVideoVideo($lesson, $meetingLink = null, $video = null)
    {
        //delete the directory of lesson video
        $videoManger = new VideoStorageManager();
        $videoManger->deleteDirectory($lesson->id);

        //save the new video
        $fileExtension = $video->getClientOriginalExtension();
        $fileName = Str::random(10) . '.' . $fileExtension;
        $lesson->video_link = $fileName;

        $videoStorageManger = new VideoStorageManager();
        $path = '/lessons/lessons_videos/' . $lesson->id;
        $videoStorageManger->upload($video, $path, $fileName);
    }
}
