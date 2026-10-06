<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class AdminSpaController extends Controller
{
    public function show(): View
    {
        return view('admin');
    }
}
