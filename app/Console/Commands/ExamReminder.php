<?php

namespace App\Console\Commands;

use App\Enum\CourseStatus;
use App\Models\Course;
use App\Services\Notifications\NotificationServices;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Notifications\Notification;

class ExamReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exams:reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Exam reminders student before available date and end date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $coursesQuery = Course::where('status_id', CourseStatus::ACTIVE)->with(['exams', 'exams.studentsAnswer', 'enrollments']);
        $examNotifications = $coursesQuery->whereHas('exams', function ($query) {
            $query->where(function ($subQuery) {
                $subQuery->whereDate('available_to', '>', now())
                    ->whereDate('available_to', '<=', now()->addDay()); // Expiry within 24 hours
            })->orWhere(function ($subQuery) {
                $subQuery->whereDate('available_from', '>', now())
                    ->whereDate('available_from', '<=', now()->addDay()); // Will be available in 24 hours
            });
        })->get();
        $notificationsService = new NotificationServices();
        foreach ($examNotifications as $course) {
            foreach ($course->exams as $exam) {
                if ($exam->pivot->available_to > now() && $exam->pivot->available_to <= now()->addDay()) {
                    foreach ($course->enrollments as $student) {
                        if (!$exam->studentsAnswer->contains('id', $student->id)) {
                            $notificationsService->saveNotification("examExpiry", $student->id, 'examReminder', $course->id, $exam->id);
                        }
                    }
                }
                if ($exam->pivot->available_from >= now() && $exam->pivot->available_from <= now()->addDay()) {
                    foreach ($course->enrollments as $student) {
                        $notificationsService->saveNotification("examWillAvailableTommorrow", $student->id, 'examReminder', $course->id, $exam->id);
                    }
                }
            }
        }
    }
}
