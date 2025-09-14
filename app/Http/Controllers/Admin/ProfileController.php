<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * عرض الملف الشخصي للـ Admin
     */
    public function index()
    {
        $user = Auth::user();
        return view('admin.profile.index', compact('user')); // view داخل resources/views/admin/profile/index.blade.php
    }
    
    public function edit()
{
    $user = Auth::user();
    return view('admin.profile.edit', compact('user'));
}


    /**
     * تحديث بيانات الملف الشخصي
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // التحقق من البيانات المدخلة
        $request->validate([
            'first_name' => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // تحديث البيانات
        $user->first_name = $request->first_name;
        $user->email      = $request->email;

        // تحديث الصورة إن وجدت
        if ($request->hasFile('image_path')) {
            $path = $request->file('image_path')->store('profile_images', 'public');
            $user->image_path = $path;
        }

        $user->save();

        return redirect()->route('admin.profile')->with('success', 'تم تحديث الملف الشخصي بنجاح.');
    }
}
