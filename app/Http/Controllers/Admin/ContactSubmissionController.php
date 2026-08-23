<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;

class ContactSubmissionController extends Controller
{
    public function index()
    {
        $messages = ContactSubmission::latest()->get();

        return view('admin.messages.index', ['messages' => $messages]);
    }

    public function show(ContactSubmission $message)
    {
        if (! $message->is_read) {
            $message->update(['is_read' => true]);
        }

        return view('admin.messages.show', ['message' => $message]);
    }

    public function toggleRead(ContactSubmission $message)
    {
        $message->update(['is_read' => ! $message->is_read]);

        return response()->json(['is_read' => $message->is_read]);
    }

    public function markAllRead()
    {
        ContactSubmission::where('is_read', false)->update(['is_read' => true]);

        return back()->with('ok', 'All messages marked as read.');
    }

    public function destroy(ContactSubmission $message)
    {
        $message->delete();

        return redirect()->route('admin.messages.index')->with('ok', 'Message deleted.');
    }
}
