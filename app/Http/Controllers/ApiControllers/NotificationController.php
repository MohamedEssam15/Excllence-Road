<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddTeacherNotificationRequest;
use App\Http\Resources\NotificationResource;
use App\Models\Course;
use App\Models\Notification;
use App\Models\User;
use App\Services\Notifications\NotificationServices;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function userNotification()
    {
        $userNotificaitons = Notification::where('reciever_id', auth()->id())->latest()->take(5)->get();
        $unreadCount = Notification::where('reciever_id', auth()->id())->where('is_read', false)->count();
        return apiResponse(__('response.dataRetrieved'), ['notifications' => NotificationResource::collection($userNotificaitons), 'unReadNotifcationCount' => $unreadCount]);
    }

    public function userAllNotification()
    {
        $userNotificaitons = Notification::where('reciever_id', auth()->id())->latest()->get();
        $unreadCount = Notification::where('reciever_id', auth()->id())->where('is_read', false)->count();
        return apiResponse(__('response.dataRetrieved'), ['notifications' => NotificationResource::collection($userNotificaitons), 'unReadNotifcationCount' => $unreadCount]);
    }

    public function sendNotification(AddTeacherNotificationRequest $request)
    {

        $notificationService = new NotificationServices();
        $notificationService->sendNotification($request->message, $request->courseId, $request->studentId, $request->lessonId);
        return apiResponse(__('response.notificationSent'));
    }

    public function deleteNotification($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();
        return apiResponse(__('response.deletedSuccessfully'));
    }
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->is_read = true;
        $notification->save();
        return apiResponse(__('response.updatedSuccessfully'));
    }
}
