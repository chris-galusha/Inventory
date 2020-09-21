<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemStoreRequest extends FormRequest
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

    $columns = getColumns();
    $validate_array = generateValidateArray($columns, $storing = true);
    $validate_array['create-count'] = ["nullable", "min:1", "integer"];

    return $validate_array;
  }
}
