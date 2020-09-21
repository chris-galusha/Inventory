<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportUpdateRequest extends FormRequest
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
      $report = request()->route('report');
      return [
        'columns.name' => ["min:3", "max:255", "string", Rule::unique('reports')->ignore($report->id)],
        'columns.description' => ["min:3", "max:255", "string"],
        'columns.active' => ["integer", "min:0", "max:1"],
        'columns.frequency' => ["min:3", "max:255", "string"],
        'columns.email' => ["min:3", "max:255", "string", 'email'],
        'columns.time' => ["date_format:H:i", "nullable"],
        'columns.day_of_week' => ["integer", "min:0", "max:6", "nullable"],
        'columns.day_of_month' => ["integer", "min:1", "max:31", "nullable"],
      ];
    }
}
