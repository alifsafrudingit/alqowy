<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCourseRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return $this->user()->hasAnyRole(['teacher', 'owner']);
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'name' => 'required|string|max:255',
      'path_trailer' => 'required|string|max:255',
      'about' => 'required|string',
      'category_id' => 'required|integer',
      'thumbnail' => 'required|image|mimes:png,jpg,jpeg,svg|max:2048',
      'course_keypoints.*' => 'required|string|max:255',
    ];
  }
}
