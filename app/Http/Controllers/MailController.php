<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mail;
use App\Mail\AccountCreation;

class MailController extends Controller
{
    public function index()
    {
        $mailData = [
            'title' => 'Mail from SalePro.com',
            'body' => 'Please Verfiy Your Account.',
            'action_url' => url('verify/account')
        ];
         
        Mail::to('your_email@gmail.com')->send(new DemoMail($mailData));
           
        // dd("Email is sent successfully.");
    }
}
