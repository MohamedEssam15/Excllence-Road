<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $course = Course::all()->random();
        $type = $this->faker->randomElement(['examReminder', 'message']);
        $students = $course->enrollments;
        // dd($students);
        if (isset($students[0])) {
            $studentId = $students->random()->id;
        } else {
            $studentId = User::all()->random()->id;
        }
        if ($type == 'examReminder') {
            $message = fake()->randomElement([
                'examExpiry',
                'examWillAvailableTommorrow',
            ]);
            $exams = $course->exams;
            if (isset($exams[0])) {
                $examId = $exams->random()->id;
            } else {
                $examId = null;
            }
            $senderId = null;
        } else {
            $examId = null;
            $senderId = $course->teacher_id;
            $message = 'newMessage';
        }

        return [
            'message' => $message,
            'reciever_id' => $studentId,
            'sender_id' => $senderId,
            'is_read' => $this->faker->boolean(),
            'course_id' => $course->id,
            'type' => $type,
            'exam_id' => $examId,
        ];
    }
}
