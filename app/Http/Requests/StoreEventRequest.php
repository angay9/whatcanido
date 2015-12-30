<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class StoreEventRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:255',
            'description'   => 'required',
            'latitude'   =>  'required|numeric|min:0',
            'longitude'   =>  'required|numeric|min:0',
            'starts_at' =>  'required|date|after:now' 
        ];
    }
}
