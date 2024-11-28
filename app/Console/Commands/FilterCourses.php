<?php

namespace App\Console\Commands;

use App\Enum\CourseStatus;
use App\Models\Course;
use App\Models\UserMessage;
use Illuminate\Console\Command;

class FilterCourses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'courses:filter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'filter expired courses and delete unneccessary data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $courses = Course::where('end_date', '<', now())->whereHas('status', function ($query) {
            $query->where('name', '!=', 'paused');
        })->with(['exams'])->get();
        foreach ($courses as $course) {
            // $course->status_id = CourseStatus::PAUSED;
            // $course->start_date = null;
            // $course->end_date = null;
            // $course->is_populer = 0;
            ds($course->exams)->die();

            //delete users messages
            $messages = UserMessage::where('course_id', $course->id)->get();
        }
        ds($courses)->die();
    }
}
