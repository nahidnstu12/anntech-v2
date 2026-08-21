<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactEnquiry;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(ContactRequest $request)
    {
        $c = require resource_path('data/content.php');

        Mail::to($c['company']['email'])->queue(new ContactEnquiry(
            $request->validated('name'),
            $request->validated('email'),
            $request->validated('phone'),
            $request->validated('message'),
        ));

        return redirect('/#contact')->with('status', 'Thanks — we will be in touch.');
    }
}
