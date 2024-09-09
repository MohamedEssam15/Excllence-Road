<?php

namespace App\Http\Controllers\TeachersControllers;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CoursesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $AuthUser= auth('web')->user();
        $coursesQuery = Course::query();
        if($AuthUser->hasRole('teacher')){
            $coursesQuery->where('teacher_id',$AuthUser->id);
        }
        $courses= $coursesQuery->paginate(1);
        if(! isset($courses[0])){
            return view('courses.all-courses')->withErrors([__('response.noCourses')]);
        }
        ds($courses);
        return view('courses.all-courses',compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
