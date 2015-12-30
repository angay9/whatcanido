<?php

namespace App\Http\Requests;

use App\Event;
use App\Http\Requests\Request;

class UpdateEventRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $evtId = request()->input('id');
        $event = Event::find($evtId);
        
        if (!$event) {
            return false;
        }

        return auth()->check() && $event->isCreator(auth()->user());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id'    =>  'required|integer|min:1',
            'title' => 'required|max:255',
            'description'   => 'required',
            'latitude'   =>  'required|numeric|min:0',
            'longitude'   =>  'required|numeric|min:0',
            'starts_at' =>  'required|date|after:now' 
        ];
    }
}
