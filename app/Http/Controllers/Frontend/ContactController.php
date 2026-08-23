<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('frontend.pages.contact', [
            'title' => 'Contact',
            'email' => Setting::get('contact_email'),
            'phone' => Setting::get('contact_phone'),
        ]);
    }

    public function store(Request $request)
    {
        // Honeypot: bots fill hidden "website" field. Pretend success, drop silently.
        if ($request->filled('website')) {
            return redirect()->route('contact.index')->with('ok', 'Thanks - your message has been sent.');
        }

        $data = $request->validate([
            'name'    => ['required', 'string', 'max:190'],
            'email'   => ['required', 'email', 'max:190'],
            'phone'   => ['nullable', 'string', 'max:60'],
            'subject' => ['nullable', 'string', 'max:190'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        ContactSubmission::create($data + ['ip_address' => $request->ip()]);

        try {
            $to = Setting::get('contact_email') ?: config('mail.from.address');
            if ($to) {
                $body = "New enquiry from {$data['name']} <{$data['email']}>\n"
                    ."Phone: ".($data['phone'] ?? '-')."\n"
                    ."Subject: ".($data['subject'] ?? '-')."\n\n"
                    .$data['message'];

                Mail::raw($body, function ($m) use ($to, $data) {
                    $m->to($to)
                      ->subject('New contact message'.(!empty($data['subject']) ? ': '.$data['subject'] : ''))
                      ->replyTo($data['email'], $data['name']);
                });
            }
        } catch (\Throwable $e) {
            // Submission is already saved; don't fail the request if mail hiccups.
        }

        return redirect()->route('contact.index')->with('ok', 'Thanks - your message has been sent. We will be in touch shortly.');
    }
}
