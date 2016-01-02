<?php

namespace App\Http\Controllers;

use App\Settings;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveSettingsRequest;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        return view('settings.index');
    }

    public function save(SaveSettingsRequest $request)
    {
        $settings = auth()->user()->settings;
        $data = request()->only(
            'address', 'email', 'email_notifications'
        );
        
        $settingsData = array_intersect_key($data, array_flip(Settings::$fields));
        $userData = array_intersect_key($data, array_flip(['address', 'email']));
        
        if (! $settings) {
            $settings = new Settings(
                array_merge(
                    $settingsData, 
                    ['user_id' => auth()->user()->id]
                )
            );
        } else {
            $settings->update($settingsData);
        }

        auth()->user()->update($userData);

        return $this->respondSuccess();
    }

    public function saveAvatar(SaveAvatarRequest $request)
    {
        
    }
}
