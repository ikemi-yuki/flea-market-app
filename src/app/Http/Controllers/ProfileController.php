<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        $profile = $user->profile;

        return view('profile', compact('profile'));
    }

    public function update(ProfileRequest $request)
    {
        $user = auth()->user();

        $path = null;

        $wasCompleted = $user->profile_completed;

        if ($request->hasFile('icon_path')) {
            $currentProfile = $user->profile;

            if ($currentProfile && $currentProfile->icon_path) {
                Storage::disk('public')->delete($currentProfile->icon_path);
            }

            $path = $request->file('icon_path')->store('profile_icons', 'public');
        }

        $profile = $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'name' => $request->name,
                'post_code' => $request->post_code,
                'address' => $request->address,
                'building' => $request->building,
                'icon_path' => $path ?? $user->profile?->icon_path,
            ]
        );

        if (!$wasCompleted) {
            $user->update([
                'profile_completed' => true,
            ]);
        }

        if (!$wasCompleted) {
            return redirect()->route('items.index');
        }

        return redirect()->route('profile.edit');
    }
}
