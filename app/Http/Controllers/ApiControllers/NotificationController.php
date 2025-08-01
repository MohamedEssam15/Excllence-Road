<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddMessageRequest;
use App\Http\Resources\AllMessagesResource;
use App\Http\Resources\MessageResource;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\TeacherChatResource;
use App\Models\Notification;
use App\Models\UserMessage;
use App\Services\Notifications\NotificationServices;
use Illuminate\Http\Request;
use stdClass;

class NotificationController extends Controller
{

    public function userAllMessages(Request $request)
    {
        $validator = validator($request->all(), [
            'courseId' => 'required|exists:courses,id',
            'studentId' => 'nullable|exists:users,id',
        ]);
        if ($validator->fails()) {
            return apiResponse('error', new \stdClass(), $validator->errors()->all(), 422);
        }
        $userMessages = UserMessage::where('course_id', $request->courseId);
        if (auth()->user()->hasRole('teacher')) {
            $userMessages->where(function ($query) use ($request) {
                $query->where('sender_id', $request->studentId)->orWhere('receiver_id', $request->studentId);
            });
        } else {
            $userMessages->where(function ($query) use ($request) {
                $query->where('sender_id', auth()->id())->orWhere('receiver_id', auth()->id());
            });
        }
        return apiResponse(__('response.dataRetrieved'), ['messages' => MessageResource::collection($userMessages->latest()->get())]);
    }



    public function sendMessage(AddMessageRequest $request)
    {

        $notificationService = new NotificationServices();
        $response = $notificationService->sendMessage($request->message, $request->courseId, $request->studentId);
        if ($response['type'] == 'broadcast') {
            return apiResponse(__('response.messageSent'));
        }
        return apiResponse(__('response.messageSent'), new MessageResource($response['message']));
    }

    public function deleteMessage($id)
    {
        $message = UserMessage::findOrFail($id);
        $message->delete();
        return apiResponse(__('response.deletedSuccessfully'));
    }
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->is_read = true;
        $notification->save();
        return apiResponse(__('response.updatedSuccessfully'), new NotificationResource($notification));
    }
    public function deleteNotification($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();
        return apiResponse(__('response.deletedSuccessfully'));
    }
    public function userNotification()
    {
        $notificationService = new NotificationServices();
        $notifications =  $notificationService->getNotifications();
        return apiResponse(__('response.dataRetrieved'), ['notifications' => NotificationResource::collection($notifications)]);
    }

    public function teacherChats()
    {
        $user = auth()->user();
        if (! $user->hasRole('teacher')) {
            return apiResponse(__('response.notAuthorized'), new stdClass(), [__('response.notAuthorized')], 401);
        }
        $teacherId = $user->id;
        $latestChats = UserMessage::selectRaw('
            CASE
                WHEN sender_id = ? THEN receiver_id
                ELSE sender_id
            END AS user_id,
            course_id,
            MAX(created_at) AS latest_message_time
        ', [$teacherId])
            ->where(function ($query) use ($teacherId) {
                $query->where('sender_id', $teacherId)
                    ->orWhere('receiver_id', $teacherId);
            })
            ->groupBy('user_id', 'course_id') // Group by user and course
            ->orderByDesc('latest_message_time') // Order by latest message time
            ->get();

        return apiResponse(__('response.dataRetrieved'), ['chats' => TeacherChatResource::collection($latestChats)]);
    }
}
