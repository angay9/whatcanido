<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Contracts\Validation\Validator;

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
            'starts_at' =>  'required|date|after:now',
            'place' =>  'required'
        ];
    }

    /**
     * Format the errors from the given Validator instance.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return array
     */
    protected function formatErrors(Validator $validator)
    {
        $errors = $validator->getMessageBag()->toArray();

        return array_except($errors, ['latitude', 'longitude']);
    }
}
