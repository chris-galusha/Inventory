<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return userIsNormalOrBetter();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
      return [
        'name' => ["min:3", "max:255", "string", "required", "unique:reports,name"],
        'description' => ["min:3", "max:255", "string", "required"],
        'active' => ["integer", "min:0", "max:1"],
        'frequency' => ["min:3", "max:255", "string", "required"],
        'email' => ["min:3", "max:255", "string", "required", 'email'],
        'time' => ["date_format:H:i",  "nullable"],
        'day_of_week' => ["integer", "min:0", "max:6", "nullable"],
        'day_of_month' => ["integer", "min:1", "max:31", "nullable"],
      ];
    }
}
