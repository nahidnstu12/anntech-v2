<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactEnquiry;
use App\Models\ContactInquiry;
use App\Services\AdminActivityLogger;
use App\Support\InquiryStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(ContactRequest $request)
    {
        $c = require resource_path('data/content.php');

        $inquiry = DB::transaction(function () use ($request) {
            $inquiry = ContactInquiry::create([
                'name' => $request->validated('name'),
                'email' => $request->validated('email'),
                'phone' => $request->validated('phone'),
                'message' => $request->validated('message'),
                'status' => InquiryStatus::NEW,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            AdminActivityLogger::inquiry('Inquiry received', null, $inquiry, [
                'inquiry_id' => $inquiry->id,
            ]);

            return $inquiry;
        });

        Mail::to($c['company']['email'])->queue(new ContactEnquiry(
            $inquiry->name,
            $inquiry->email,
            $inquiry->phone,
            $inquiry->message,
            $inquiry->id,
        ));

        return redirect('/#contact')->with('status', 'Thanks — we will be in touch.');
    }
}
