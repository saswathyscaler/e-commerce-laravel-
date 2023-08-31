<?php


namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Mail\demoMail;

class demoOrder extends Controller
{

    public function index()
    {
        $mailData = [ 
            'title' => "mail from shop app",
            'body' => "This is a testing mail from shop app"
        ];

        Mail::to('saswatr.mohanty@hyscaler.com')->send(new demoMail($mailData));
        dd('email send successfully');
    }
}
