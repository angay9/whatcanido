<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class SaveSettingsRequest extends Request
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
            'address'   =>  'max:255',
            'email_notifications'   =>  'boolean',
            'email' =>  'required|email|max:255',
        ];
    }
}
