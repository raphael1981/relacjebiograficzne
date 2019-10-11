<?php

namespace App\Jobs;

use App\Repositories\CustomerRepositoryEloquent;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class RemeberVerifyByEmail implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $customer;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($customer)
    {
        $this->customer = $customer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(CustomerRepositoryEloquent $crepo)
    {

        $customer = $crepo->find($this->customer->id);

        Mail::send('emails.customer.tocustomer.linkverifyremeber', ['customer' =>  $customer], function ($m) use ($customer) {

            $m->from('raphael@raphael.usermd.net', 'AHM');
            $m->to($customer->email, '')->subject('Przypomnie o weryfikacji konta na portalu AHM');

        });

    }
}
