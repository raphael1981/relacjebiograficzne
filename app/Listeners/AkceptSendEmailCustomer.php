<?php

namespace App\Listeners;

use App\Events\AkceptNewCustomer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class AkceptSendEmailCustomer
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
     * @param  AkceptNewCustomer  $event
     * @return void
     */
    public function handle(AkceptNewCustomer $event)
    {
        $customer = $event->customer;

        $adminemail = config('services')['adminemail'];

        Mail::send('emails.customer.tocustomer.akcept', ['customer' =>  $customer], function ($m) use ($customer,$adminemail) {

            $m->from($adminemail, 'AHM');
            $m->to($customer->email, '')->subject('Twoje konto na portalu www.relacjebiograficzne.pl zostało zaakceptowane');

        });

    }
}
