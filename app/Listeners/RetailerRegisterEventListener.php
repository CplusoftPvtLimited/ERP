<?php

namespace App\Listeners;

use App\Events\RetailerRegisterEvent;
use App\Mail\AccountCreation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;


class RetailerRegisterEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\RetailerRegisterEvent  $event
     * @return void
     */
    public function handle(RetailerRegisterEvent $event)
    {
        // dd($event->maildata['email']);
        
        Mail::to($event->maildata['email'])->send(new AccountCreation($event->maildata));
    }
}
