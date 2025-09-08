<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProfileController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        return view('admin.profile.index', compact('user'));
    }
    
    public function edit()
{
    $user = Auth::user();
    return view('admin.profile.edit', compact('user'));
}


    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->first_name = $request->first_name;
        $user->email      = $request->email;

        if ($request->hasFile('image_path')) {
            $path = $request->file('image_path')->store('profile_images', 'public');
            $user->image_path = $path;
        }

        $user->save();

        return redirect()->route('admin.profile')->with('success', 'تم تحديث الملف الشخصي بنجاح.');
    }
}
