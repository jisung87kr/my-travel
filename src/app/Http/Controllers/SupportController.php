<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class SupportController extends Controller
{
    public function help(): View
    {
        return view('support.help');
    }

    public function contact(): View
    {
        return view('support.contact');
    }

    public function faq(): View
    {
        return view('support.faq');
    }

    public function cancellationPolicy(): View
    {
        return view('support.cancellation-policy');
    }

    public function aboutUs(): View
    {
        return view('support.about');
    }

    public function privacy(): View
    {
        return view('support.privacy');
    }

    public function terms(): View
    {
        return view('support.terms');
    }
}
