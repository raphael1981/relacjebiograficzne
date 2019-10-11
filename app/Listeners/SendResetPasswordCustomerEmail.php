<?php

namespace App\Listeners;

use App\Events\ResetCustomerPassword;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendResetPasswordCustomerEmail
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
     * @param  ResetCustomerPassword  $event
     * @return void
     */
    public function handle(ResetCustomerPassword $event)
    {

        $adminemail = config('services')['adminemail'];

        $customer = $event->customer;

        Mail::send('emails.customer.tocustomer.linkverifyremeber', ['customer' =>  $customer], function ($m) use ($customer,$adminemail) {

            $m->from($adminemail, 'AHM');
            $m->to($customer->email, '')->subject('Link do resetu hasła');
        });
    }
}
