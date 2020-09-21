<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ColumnUpdateRequest extends FormRequest
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
          return [
            'display-name' => ["string", "min:3", "required"],
            'protected' => ["integer", "min:0", "max:1", "nullable"],
            'required' => ["integer", "min:0", "max:1", "nullable"],
            'display' => ["integer", "min:0", "max:1", "nullable"],
          ];
    }
}
