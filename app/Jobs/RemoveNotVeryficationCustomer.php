<?php

namespace App\Jobs;

use App\Repositories\CustomerRepositoryEloquent;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RemoveNotVeryficationCustomer implements ShouldQueue
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

        if($customer->status==-1){
            $customer->forceDelete();
        }


    }
}
