<?php

namespace App\Services\Notifications;

use App\Models\Course;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

class NotificationServices
{
    public function sendNotification($message, $courseId, $recieverId = null, $lessonId = null)
    {
        $authUser = auth()->user();
        if ($authUser->hasRole('student')) {
            $this->studentNotification($authUser, $message, $courseId, $lessonId);
        } else {
            $this->teacherNotification($authUser, $message, $courseId, $recieverId, $lessonId);
        }
    }

    private function teacherNotification($authUser, $message, $courseId, $recieverId = null, $lessonId = null)
    {
        if ($recieverId != null) {
            Notification::create([
                'message' => $message,
                'reciever_id' => $recieverId,
                'sender_id' => $authUser->id,
                'course_id' => $courseId,
                'lesson_id' => $lessonId
            ]);
        } else {
            $enrolledStudents = User::role('student')->whereHas('enrollments', function ($query) use ($courseId) {
                $query->where('course_id', $courseId)->where('end_date', '>', Carbon::now());;
            })->get();
            foreach ($enrolledStudents as $student) {
                Notification::create([
                    'message' => $message,
                    'reciever_id' => $student->id,
                    'sender_id' => $authUser->id,
                    'course_id' => $courseId,
                    'lesson_id' => $lessonId
                ]);
            }
        }
    }
    private function studentNotification($authUser, $message, $courseId, $lessonId = null)
    {
        $course = Course::find($courseId);
        Notification::create([
            'message' => $message,
            'reciever_id' => $course->teacher_id,
            'sender_id' => $authUser->id,
            'course_id' => $courseId,
            'lesson_id' => $lessonId
        ]);
    }
}
