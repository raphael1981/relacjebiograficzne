<?php

namespace App\Listeners;

use App\Events\VerificationNewCustomer;
use App\Helpers\CustomHelp;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class VerificationSendEmailAdmin
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
     * @param  VerificationNewCustomer  $event
     * @return void
     */
    public function handle(VerificationNewCustomer $event)

    {
        $customer = $event->customer;
        $services = config('services');

        $token = CustomHelp::createTokenToAdminEmailAkcept($customer);

        Mail::send('emails.customer.toadmin.verfiybyemail', [
            'customer' =>  $customer,
            'adminprotocol'=>$services['protocol']['admin'],
            'admindomain'=>$services['domains']['admin'],
            'token'=>$token
        ], function ($m) use ($customer, $services) {

            $m->from('raphael@raphael.usermd.net', 'AHM');
            $m->to($services['adminemail'], '')->subject('Potwierdzenie werfikacji użytkownika w systemie AHM - '.$customer->email);
        });
    }
}
