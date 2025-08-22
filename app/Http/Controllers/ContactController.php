<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Validator;

class ContactController extends Controller
{
    public function show()
    {
        return view('theme.contact');
    }

    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'service' => 'required|in:Complaints,note,collaboration',
            'message' => 'required|string|max:2000',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        Contact::create($validator->validated());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم استلام رسالتك بنجاح وسنتواصل معك قريباً'
            ]);
        }

        return back()->with('success', 'تم استلام رسالتك بنجاح وسنتواصل معك قريباً');
    }
}
