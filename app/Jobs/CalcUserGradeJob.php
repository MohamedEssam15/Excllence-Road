<?php

namespace App\Jobs;

use App\Models\Course;
use App\Models\Exam;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CalcUserGradeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    private $questions;
    private $answers;
    private $examId;
    private $courseId;
    /**
     * Create a new job instance.
     */
    public function __construct($user, $questions, $answers, $examId, $courseId)
    {
        $this->user = $user;
        $this->questions = $questions;
        $this->answers = $answers;
        $this->examId = $examId;
        $this->courseId = $courseId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $exam = Exam::find($this->examId);
        $user = $this->user;
        $grade  = 0;
        $questions = $exam->questions;
        $answers = $this->answers;
        foreach ($answers as $answer) {
            foreach ($questions as $question) {
                if ($question->id == $answer['questionId']) {
                    if ($question->answer_id == $answer['answerId']) {
                        $user->studentQuestions()->attach([$question->id => [
                            'is_correct' => 1,
                            'exam_id' => $this->examId,
                            'course_id' => $this->courseId,
                            'answer_id' => $answer['answerId'],
                        ]]);
                        $grade += 1;
                    } else {
                        $user->studentQuestions()->attach([$question->id => [
                            'is_correct' => 0,
                            'exam_id' => $this->examId,
                            'course_id' => $this->courseId,
                            'answer_id' => $answer['answerId'],
                        ]]);
                    }
                }
            }
        }
        $user->studentExams()->updateExistingPivot($this->examId, ['grade' => $grade]);
    }
}
