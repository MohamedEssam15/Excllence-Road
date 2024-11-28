<?php

namespace App\Services\Notifications;

use App\Models\Course;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserMessage;
use Carbon\Carbon;

class NotificationServices
{
    public function sendMessage($message, $courseId, $recieverId = null)
    {
        $authUser = auth()->user();
        if ($authUser->hasRole('student')) {
            $response =  $this->studentMessage($authUser, $message, $courseId);
        } else {
            $response = $this->teacherMessage($authUser, $message, $courseId, $recieverId);
        }
        return $response;
    }

    private function teacherMessage($authUser, $message, $courseId, $recieverId = null)
    {
        if ($recieverId != null) {
            $messageResponse =  UserMessage::create([
                'message' => $message,
                'receiver_id' => $recieverId,
                'sender_id' => $authUser->id,
                'course_id' => $courseId,
            ]);
            $this->saveNotification("newMessage", $recieverId, 'message', $courseId, null, $authUser->id);
            return $response = [
                'message' => $messageResponse,
                'type' => 'message'
            ];
        } else {
            $enrolledStudents = User::role('student')->whereHas('enrollments', function ($query) use ($courseId) {
                $query->where('courses_users.course_id', $courseId)->where('courses_users.end_date', '>', Carbon::today());
            })->get();
            foreach ($enrolledStudents as $student) {
                UserMessage::create([
                    'message' => $message,
                    'receiver_id' => $student->id,
                    'sender_id' => $authUser->id,
                    'course_id' => $courseId,
                ]);
                $this->saveNotification("newMessage", $student->id, 'message', $courseId, null, $authUser->id);
            }
            return $response = [
                'message' => null,
                'type' => 'broadcast'
            ];
        }
    }

    private function studentMessage($authUser, $message, $courseId)
    {
        $course = Course::find($courseId);
        $messageResponse =  UserMessage::create([
            'message' => $message,
            'receiver_id' => $course->teacher_id,
            'sender_id' => $authUser->id,
            'course_id' => $courseId,
        ]);
        $this->saveNotification("newMessage", $course->teacher_id, 'message', $courseId, null, $authUser->id);
        return $response = [
            'message' => $messageResponse,
            'type' => 'message'
        ];
    }
    public function getNotifications()
    {
        $notifications = Notification::where('reciever_id', auth()->id())->latest()->get();
        return $notifications;
    }
    public function saveNotification($message, $recieverId, $type, $courseId = null, $examId = null, $senderId = null)
    {
        return Notification::create([
            'message' => $message,
            'reciever_id' => $recieverId,
            'sender_id' => $senderId,
            'course_id' => $courseId,
            'exam_id' => $examId,
            'is_read' => false,
            'type' => $type
        ]);
    }
}
