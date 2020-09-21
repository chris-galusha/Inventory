<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
  /**
  * Determine if the user is authorized to make this request.
  *
  * @return bool
  */
  public function authorize()
  {
    return userIsAdmin();
  }

  /**
  * Get the validation rules that apply to the request.
  *
  * @return array
  */
  public function rules()
  {
    $user = request()->route('user');
    return [
      "name" => ["max:255", "min:3", "string", "required"],
      "email" => ["max:255", "email", "required", Rule::unique('users')->ignore($user->id)],
      "password" => ["same:password-confirm", "min:8", "max:255", "nullable"],
      "password-confirm" => ["nullable"],
      "role" => ["exists:roles,name", "required"],
    ];
  }

  public function messages() {
    return [
      // Name
      'name.required' => 'You must provide a name.',
      'name.max' => 'Name must be less than 256 characters.',
      'name.string' => 'Name must be a string of characters.',
      'name.min' => 'Name must be at least 3 characters',

      // Email
      'email.required' => 'You must provide an email.',
      'email.email' => 'Email must be a valid email.',
      'email.unique' => 'Email must be unique.',
      'email.max' => 'Email must be less than 256 characters.',

      // Password
      'password.same' => 'Passwords must match.',
      'password.min' => 'Password must be at least 8 characters.',
      'password.max' => 'Password must be less than 256 characters.',

      // Role
      'role.required' => 'A role is required.',
      'role.exists' => 'The provided role must exist.',
    ];
  }
}
