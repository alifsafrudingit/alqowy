<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubscribeTransactionRequest;
use App\Models\Category;
use App\Models\Course;
use App\Models\SubscribeTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FrontController extends Controller
{
  public function index()
  {
    $categories = Category::take(7)->orderBy('id', 'asc')->get();

    $courses = Course::with(['category', 'teacher', 'students'])->orderByDesc('id')->get();

    return view('front.index', compact('categories', 'courses'));
  }

  public function details(Course $course)
  {
    return view('front.details', compact('course'));
  }

  public function learning(Course $course, $courseVideoId)
  {
    $user = Auth::user();

    if (!$user->hasActiveSubscription()) {
      return redirect()->route('front.pricing');
    }

    $video = $course->course_videos->firstWhere('id', $courseVideoId);

    $user->courses()->syncWithoutDetaching($course->id);

    return view('front.learning', compact('course', 'video'));
  }

  public function category(Category $category)
  {
    $coursesByCategory = $category->courses()->get();

    return view('front.category', compact('coursesByCategory', 'category'));
  }

  public function pricing()
  {
    if (Auth::user()->hasActiveSubscription()) {
      return redirect()->route('front.index');
    }

    return view('front.pricing');
  }

  public function checkout()
  {
    $codeSwift = 'ALQOWYTRF' . Str::upper(Str::random(5));

    return view('front.checkout', compact('codeSwift'));
  }

  public function checkout_store(StoreSubscribeTransactionRequest $request)
  {
    $user = Auth::user();

    if (Auth::user()->hasActiveSubscription()) {
      return redirect()->route('front.index');
    }

    DB::transaction(function () use ($request, $user) {
      $data = $request->validated();

      if ($request->hasFile('proof')) {
        $proofPath = $request->file('proof')->store('proofs', 'public');
        $data['proof'] = $proofPath;
      }

      $data['user_id'] = $user->id;
      $data['code_swift'] = $request->code_swift;
      $data['total_amount'] = 429000;
      $data['is_paid'] = false;

      $transaction = SubscribeTransaction::create($data);
    });

    return redirect()->route('dashboard');
  }
}
