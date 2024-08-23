<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::guard('customer')->user();

        // Kiểm tra xem người dùng có profile hay không
        if (!$user) {
            return redirect()->route('login')->withErrors(['error' => 'User not found']);
        }

        // Tạo profile nếu chưa tồn tại
        $profile = $user->profile ?? Profile::create(['customer_id' => $user->id,
            'avatar' =>  'avatars/default-avatar.png',]);

        return view('profile.show', compact('profile'));
    }

    public function edit()
    {
        $user = Auth::guard('customer')->user();

        // Kiểm tra xem người dùng có profile hay không
        if (!$user) {
            return redirect()->route('login')->withErrors(['error' => 'User not found']);
        }

        // Tạo profile nếu chưa tồn tại
        $profile = $user->profile ?? Profile::create(['customer_id' => $user->id]);

        return view('profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $user = Auth::guard('customer')->user();


        if (!$user) {
            return redirect()->route('login')->withErrors(['error' => 'User not found']);
        }

        $profile = $user->profile ?? Profile::create(['customer_id' => $user->id]);

        $request->validate([
            'name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'birthdate' => 'nullable|date',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'current_password' => 'nullable',
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);


        $profile->fill($request->only(['name', 'address', 'phone', 'birthdate']));
        $profile->save();


        if ($request->hasFile('avatar')) {
            if ($profile->avatar) {
                Storage::delete('public/' . $profile->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $profile->avatar = $path;
            $profile->save();
        }


        if ($request->filled('current_password') && $request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect']);
            }

            $user->password = Hash::make($request->new_password);
            $user->save();
        }

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully');
    }
}
