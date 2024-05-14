<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseRequest;
use App\Models\Category;
use App\Models\Course;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CourseController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $user = Auth::user();
    $query = Course::with(['category', 'teacher', 'students'])->orderByDesc('id');

    if ($user->hasRole('teacher')) {
      $query->whereHas('teacher', function ($query) use ($user) {
        $query->where('user_id', $user->id);
      });
    }

    $courses = $query->paginate(10);

    return view('admin.courses.index', compact('courses'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $categories = Category::all();

    return view('admin.courses.create', compact('categories'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(StoreCourseRequest $request)
  {
    $teacher = Teacher::where('user_id', Auth::user()->id)->first();

    if (!$teacher) {
      return redirect()->route('admin.courses.index')->withErrors('Anda bukan seorang guru');
    }

    $data = $request->all();

    $data['slug'] = Str::slug($data['name']);

    if ($request->hasFile('thumbnail')) {
      $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
    }

    $data['teacher_id'] = $teacher->id;

    $course = Course::create($data);

    if (!empty($data['course_keypoints'])) {
      foreach ($data['course_keypoints'] as $keypointText) {
        $course->course_keypoints()->create([
          'name' => $keypointText,
        ]);
      }
    }

    toast($data['name'] . ' course has been created!', 'success');

    return redirect()->route('admin.courses.index');
  }

  /**
   * Display the specified resource.
   */
  public function show(Course $course)
  {
    return view('admin.courses.show', compact('course'));
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Course $course)
  {
    $categories = Category::all();

    return view('admin.courses.edit', compact('course', 'categories'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Course $course)
  {
    $data = $request->all();

    $data['slug'] = Str::slug($data['name']);

    if ($request->hasFile('thumbnail')) {
      $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
    }

    $course->update($data);

    if (!empty($data['course_keypoints'])) {
      $course->course_keypoints()->delete();
      foreach ($data['course_keypoints'] as $keypointText) {
        $course->course_keypoints()->create([
          'name' => $keypointText,
        ]);
      }
    }

    toast($data['name'] . ' course has been updated!', 'success');

    return redirect()->route('admin.courses.show', $course);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Course $course)
  {
    try {
      $course->delete();

      toast($course['name'] . ' course has been deleted!', 'success');

      return redirect()->route('admin.courses.index');
    } catch (\Exception $e) {
      return redirect()->route('admin.courses.index')->with('error', `Terjadi error ketika menghapus kelas $course->name`);
    }
  }
}
