<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeacherRequest;
use App\Models\Teacher;
use App\Models\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException as ValidationValidationException;

class TeacherController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $teachers = Teacher::orderBy('id', 'desc')->get();

    return view('admin.teachers.index', compact('teachers'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    return view('admin.teachers.create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(StoreTeacherRequest $request)
  {
    $data = $request->all();

    $user = User::where('email', $data['email'])->first();

    if (!$user) {
      return back()->withErrors([
        'email' => 'Data tidak ditemukan'
      ]);
    }

    if ($user->hasRole('teacher')) {
      return back()->withErrors([
        'email' => 'Email sudah terdaftar sebagai guru'
      ]);
    }

    $data['user_id'] = $user->id;
    $data['is_active'] = true;

    Teacher::create($data);

    if ($user->hasRole('student')) {
      $user->removeRole('student');
    }

    $user->assignRole('teacher');

    toast($user['name'] . ' teacher has been created!', 'success');

    return redirect()->route('admin.teachers.index');
  }

  /**
   * Display the specified resource.
   */
  public function show(Teacher $teacher)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Teacher $teacher)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Teacher $teacher)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Teacher $teacher)
  {
    try {
      $teacher->delete();

      $user = User::find($teacher->user_id);
      $user->removeRole('teacher');
      $user->assignRole('student');

      toast($user['name'] . ' teacher has been updated!', 'success');

      return redirect()->back();
    } catch (\Exception $e) {
      DB::rollBack();
      return $e->getMessage();
    }
  }
}
