<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
//use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize() : bool
    {
        return true;
    }

    public function rules() : array
    {
        $method = $this->method();
        if($method == 'POST')
        {
            return [
                'category' => 'sometimes|required|string|max:32',

            ];
        }
        else
        {
            return [
                'id' => 'required|integer|exists:categories,id',
                'category' => 'required|string|max:32',

            ];
        }
    }
    
    protected function prepareForValidation()
    {

    }
}
    