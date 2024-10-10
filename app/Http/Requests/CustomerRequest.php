<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
//use Illuminate\Validation\Rule;

class CustomerRequest extends FormRequest
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
                'name' => 'sometimes|required|string|max:255',
                'reference' => 'sometimes|required|string',
                'category_id' => 'sometimes|nullable|integer|exists:categories,id',
                'startDate' => 'sometimes|nullable|date',
                'description' => 'sometimes|nullable|string',

            ];
        }
        else
        {
            return [
                'id' => 'required|integer|exists:customers,id',
                'name' => 'required|string|max:255',
                'reference' => 'required|string',
                'category_id' => 'nullable|integer|exists:categories,id',
                'startDate' => 'nullable|date',
                'description' => 'nullable|string',

            ];
        }
    }
    
    protected function prepareForValidation()
    {
        if($this->categoryId)
        {
            $this->merge([
            'category_id' => $this->categoryId
        ]);
        }
        if($this->startdate)
        {
            $this->merge([
            'startDate' => $this->startdate
        ]);
        }

    }
}
    