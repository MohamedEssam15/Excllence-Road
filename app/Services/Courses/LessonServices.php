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

        'video_link-video' => 'handleVideoLinkVideo',
        'video_link-meeting' => 'handleVideoLinkMeeting',
        'video-video_link' => 'handleVideoVideoLink',
        'meeting-video_link' => 'handleMeetingVideoLink',
        'video_link-video_link' => 'handleVideoLinkVideoLink',
    ];

    public function saveLesson($type, $enName, $arName, $enDescription, $arDescription, $unitId, $order, $meetingLink = null, $meetingDate = null, $video = null, $attachments = null)
    {
        $name =  $enName ?? $arName;
        $description = $enDescription ?? $arDescription ?? null;
        if ($type == 'meeting') {
            $lesson = $this->saveMeetingLesson($name, $description, $unitId, $order, $meetingLink, $meetingDate);
        } elseif ($type == 'video_link') {
            $lesson = $this->saveVideoLinkLesson($name, $description, $unitId, $order, $video);
        }else{
            $lesson = $this->saveVideoLesson($name, $description, $unitId, $order, $video);
        }
        $lessonTranslations = [];
        if ($enName != null) {
            $lessonTranslations[] = ['locale' => 'en', 'name' => ucfirst($enName), 'description' => $enDescription];
        }
        if ($arName != null) {
            $lessonTranslations[] = ['locale' => 'ar', 'name' => $arName, 'description' => $arDescription];
        }
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

    protected function saveMeetingLesson($name, $description, $unitId, $order, $meetingLink, $meetingDate)
    {
        $lesson = ModelsLesson::create([
            'name' => $name,
            'unit_id' => $unitId,
            'description' => $description,
            'order' => $order,
            'type' => 'meeting',
            'video_link' => $meetingLink,
            'meeting_date' => $meetingDate,
        ]);

        return $lesson;
    }
    protected function saveVideoLinkLesson($name, $description, $unitId, $order, $videoLink)
    {
        $lesson = ModelsLesson::create([
            'name' => $name,
            'unit_id' => $unitId,
            'description' => $description,
            'order' => $order,
            'type' => 'video_link',
            'video_link' => $videoLink,
        ]);

        return $lesson;
    }

    protected function saveVideoLesson($name, $description, $unitId, $order, $video)
    {
        $fileExtension = $video->getClientOriginalExtension();
        $fileName = Str::random(10) . '.' . $fileExtension;
        $lesson = ModelsLesson::create([
            'name' => $name,
            'unit_id' => $unitId,
            'description' => $description,
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
    public function updateLesson($lesson, $enName, $arName, $enDescription, $arDescription, $order, $type = null, $video = null, $link = null, $meetingDate = null)
    {

        if ($type != null) {
            $newType = $type;
            $oldType = $lesson->type;

            $key = "{$oldType}-{$newType}";

            if (!array_key_exists($key, $this->updateLessonHandlers)) {
                return null;
            }

            $handlerMethod = $this->updateLessonHandlers[$key];
            $this->$handlerMethod($lesson, $link, $video);
        }

        $lesson->name = $enName ?? $arName;
        $lesson->description = $enDescription ?? $arDescription;
        $lesson->order = $order;
        $lesson->meeting_date = $meetingDate;
        $lesson->save();
        $lessonTranslations = [];
        if ($enName != null) {
            $lessonTranslations[] = ['locale' => 'en', 'name' => ucfirst($enName), 'description' => $enDescription];
        }
        if ($arName != null) {
            $lessonTranslations[] = ['locale' => 'ar', 'name' => $arName, 'description' => $arDescription];
        }
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

    private function handleMeetingVideo($lesson, $link = null, $video = null)
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
    private function handleVideoLinkVideo($lesson, $link = null, $video = null)
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

    private function handleVideoLinkMeeting($lesson, $link = null, $video = null)
    {
        $lesson->type = 'meeting';
        $lesson->video_link = $link;
    }
    private function handleMeetingVideoLink($lesson, $link = null, $video = null)
    {
        $lesson->type = 'video_link';
        $lesson->video_link = $link;
    }
    private function handleVideoLinkVideoLink($lesson, $link = null, $video = null)
    {
        $lesson->video_link = $link;
    }

    private function handleMeetingMeeting($lesson, $link = null, $video = null)
    {
        $lesson->video_link = $link;
    }

    private function handleVideoMeeting($lesson, $link = null, $video = null)
    {
        $lesson->video_link = $link;
        $lesson->type = 'meeting';

        $videoManger = new VideoStorageManager();
        $videoManger->deleteDirectory($lesson->id);
    }
    private function handleVideoVideoLink($lesson, $link = null, $video = null)
    {
        $lesson->video_link = $link;
        $lesson->type = 'video_link';

        $videoManger = new VideoStorageManager();
        $videoManger->deleteDirectory($lesson->id);
    }

    private function handleVideoVideo($lesson, $link = null, $video = null)
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
