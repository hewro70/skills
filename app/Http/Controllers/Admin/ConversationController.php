<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Conversation;

class ConversationController extends Controller
{
    // عرض كل المحادثات
    public function index()
    {
        $conversations = Conversation::all();
        return view('admin.conversations.index', compact('conversations'));
    }

    // حذف محادثة
    public function destroy($id)
    {
        $conversation = Conversation::findOrFail($id);
        $conversation->delete();

        return redirect()->back()->with('success', 'تم حذف المحادثة بنجاح');
    }
}
