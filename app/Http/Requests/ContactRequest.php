<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
//use Illuminate\Validation\Rule;

class ContactRequest extends FormRequest
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
                'customer_id' => 'sometimes|required|integer|exists:customers,id',
                'firstName' => 'sometimes|required|string|max:32',
                'lastName' => 'sometimes|nullable|string|max:32',

            ];
        }
        else
        {
            return [
                'id' => 'required|integer|exists:contacts,id',
                'customer_id' => 'required|integer|exists:customers,id',
                'firstName' => 'required|string|max:32',
                'lastName' => 'nullable|string|max:32',

            ];
        }
    }
    
    protected function prepareForValidation()
    {
        if($this->customerId)
        {
            $this->merge([
            'customer_id' => $this->customerId
        ]);
        }
        if($this->firstname)
        {
            $this->merge([
            'firstName' => $this->firstname
        ]);
        }
        if($this->lastname)
        {
            $this->merge([
            'lastName' => $this->lastname
        ]);
        }

    }
}
    