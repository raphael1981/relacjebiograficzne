<?php

namespace App\Listeners;

use App\Events\CreateNewCustomer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class CreateSendEmailAdmin
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
        $customer = $event->customer;
        $services = config('services');

        Mail::send('emails.customer.toadmin.customercreate', ['customer' =>  $customer], function ($m) use ($customer, $services) {

            $m->from('raphael@raphael.usermd.net', 'AHM');
            $m->to($services['adminemail'], '')->subject('Potwierdzenie rejestacji w systemie AHM - '.$customer->email);
        });

    }
}
