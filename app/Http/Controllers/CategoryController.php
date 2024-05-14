<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $categories = Category::orderByDesc('id')->get();

    return view('admin.categories.index', compact('categories'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    return view('admin.categories.create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(StoreCategoryRequest $request)
  {
    $data = $request->all();

    $data['slug'] = Str::slug($data['name']);

    if ($request->hasFile('icon')) {
      $data['icon'] = $request->file('icon')->store('icons', 'public');
    } else {
      $data['icon'] = 'images/icon-default.png';
    }

    Category::create($data);

    toast($data['name'] . ' category has been created!', 'success');

    return redirect()->route('admin.categories.index');
  }

  /**
   * Display the specified resource.
   */
  public function show(Category $category)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Category $category)
  {
    return view('admin.categories.edit', compact('category'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdateCategoryRequest $request, Category $category)
  {
    $data = $request->all();

    $data['slug'] = Str::slug($data['name']);

    if ($request->hasFile('icon')) {
      $data['icon'] = $request->file('icon')->store('icons', 'public');
    }

    $category->update($data);

    toast($data['name'] . ' category has been updated!', 'success');

    return redirect()->route('admin.categories.index');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Category $category)
  {
    // DB::beginTransaction();

    try {
      $category->delete();
      // DB::commit();
      toast($category['name'] . ' category has been removed!', 'success');
      // alert()->success('Hore!', 'Category Deleted Successfully');
      return back();
    } catch (\Exception $e) {
      DB::rollback();
      return redirect()->route('admin.categories.index')->with('error', `Terjadi error ketika menghapus category $category->name`);
    }
  }
}
