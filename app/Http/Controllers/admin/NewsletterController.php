<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Newsletter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class NewsletterController extends Controller
{
    public function index()
    {
        $newsletter = Newsletter::orderBy('created_at', 'desc')->get();
        $newsletterEmails = $newsletter->pluck('email')->implode(',');
        return view('admin.newsletter.index', compact('newsletter', 'newsletterEmails'));
    }
}
