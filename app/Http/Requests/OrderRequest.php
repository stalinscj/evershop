<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer_name'   => 'required|string|max:80',
            'customer_email'  => 'required|string|email:filter|max:120',
            'customer_mobile' => 'required|string|digits:11',
        ];
    }
}
