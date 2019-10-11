<?php

namespace App\Listeners;

use App\Events\CreateNewCustomer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class CreateSendEmailCustomer
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
     * @param  CreateNewCustomer  $event
     * @return void
     */
    public function handle(CreateNewCustomer $event)
    {
        $adminemail = config('services')['adminemail'];

        $customer = $event->customer;

        Mail::send('emails.customer.tocustomer.linkverify', ['customer' =>  $customer], function ($m) use ($customer,$adminemail) {

            $m->from($adminemail, 'AHM');
            $m->to($customer->email, '')->subject('Potwierdzenie rejestacji w systemie AHM - link weryfikacyjny');
        });

    }
}
