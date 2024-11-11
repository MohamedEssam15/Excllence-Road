<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddReviewRequest;
use App\Http\Requests\EditReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Course;
use App\Models\Review;
use Illuminate\Http\Request;
use stdClass;

class ReviewController extends Controller
{
    /**
     * Store a newly created review in storage.
     *
     * @param  \App\Http\Requests\AddReviewRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function addReview(AddReviewRequest $request)
    {
        if(Review::where('student_id', auth()->id())->where('course_id', $request->courseId)->exists()){
            return apiResponse(__('response.reviewExists'), new stdClass(), [__('response.reviewExists')], 422);
        }
        $review = Review::create([
            'student_id' => auth()->id(),
            'course_id' => $request->courseId,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);
        return apiResponse(__('response.addedSuccessfully'), new ReviewResource($review));
    }
    public function deleteReview(Review $review)
    {
        if (auth()->user()->hasRole('student') && $review->student_id == auth()->id()) {
            $review->delete();
            return apiResponse(__('response.deletedSuccessfully'));
        } else {
            return apiResponse(__('response.notAuthorized'), new stdClass(), [__('response.notAuthorized')], 401);
        }
    }
    public function editReview(EditReviewRequest $request, Review $review)
    {
        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);
        return apiResponse(__('response.updatedSuccessfully'), new ReviewResource($review));
    }
    public function show(Review $review) {}
}
