<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\SaveAvatarRequest;
use App\Http\Requests\SaveSettingsRequest;
use App\Settings;
use Illuminate\Http\Request;

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
            $settings->save();
        } else {
            $settings->update($settingsData);
        }

        auth()->user()->update($userData);

        return $this->respondSuccess();
    }

    public function saveAvatar(SaveAvatarRequest $request)
    {
        $file = request()->file('file');
        $path = config('whatcanido.paths.avatar') . '/' . auth()->user()->id . '/';
        $oldAvatar = $path . auth()->user()->img;

        if (file_exists($oldAvatar) && $oldAvatar != '' && $oldAvatar != null) {
            unlink($oldAvatar);
        }

        $name = sha1(md5($file->getClientOriginalName() . auth()->user()->id)) . '.' . $file->guessClientExtension();

        $file->move($path, $name);

        auth()->user()->img = $name;
        auth()->user()->save();

        return $this->respondSuccess();
    }
}
