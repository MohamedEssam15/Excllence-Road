<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    public function deleteAndRedirect($id)
    {
        $notification = AdminNotification::find($id);

        if ($notification) {
            $redirectRoute = $this->getRedirectRoute($notification->type); // Determine where to redirect
            $notification->delete();
            return redirect()->route($redirectRoute)->with('success', __('Notification deleted successfully.'));
        }

        return redirect()->back()->with('error', __('Notification not found.'));
    }

    private function getRedirectRoute($type)
    {
        return match ($type) {
            'new-teacher' => 'users.teacher.pending',
            'new-course' => 'courses.pending',
            'new-contact-us-message' => 'contactUs.all',
            default => 'dashboard', // Fallback route
        };
    }
}
