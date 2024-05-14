<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseVideoRequest;
use App\Models\Course;
use App\Models\CourseVideo;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CourseVideoController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    //
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create(Course $course)
  {
    // $teacher = Teacher::where('user_id', Auth::user()->id)->first();

    return view('admin.course_videos.create', compact('course'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(StoreCourseVideoRequest $request, Course $course)
  {
    $data = $request->all();

    $data['course_id'] = $course->id;

    CourseVideo::create($data);

    return redirect()->route('admin.courses.show', $course->id);
  }

  /**
   * Display the specified resource.
   */
  public function show(CourseVideo $courseVideo)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(CourseVideo $courseVideo)
  {
    return view('admin.course_videos.edit', compact('courseVideo'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(StoreCourseVideoRequest $request, CourseVideo $courseVideo)
  {
    $data = $request->all();

    $courseVideo->update($data);

    return redirect()->route('admin.courses.show', $courseVideo->course_id);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(CourseVideo $courseVideo)
  {
    try {
      $courseVideo->delete();

      return redirect()->route('admin.courses.show', $courseVideo->course_id);
    } catch (\Exception $e) {
      return redirect()->route('admin.courses.show', $courseVideo->course_id)->with('error', `Terjadi error ketika menghapus video $courseVideo->name`);
    }
  }
}
