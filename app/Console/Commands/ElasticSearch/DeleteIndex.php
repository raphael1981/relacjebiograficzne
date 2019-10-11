<?php

namespace App\Console\Commands\ElasticSearch;

use Illuminate\Console\Command;
use Elasticsearch\Client;
use Ixudra\Curl\Facades\Curl;

class DeleteIndex extends Command
{
    private $elasticsearch;
    private $curl;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'els:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Client $elasticsearch, Curl $curl)
    {
        parent::__construct();
        $this->elasticsearch = $elasticsearch;
        $this->curl = $curl;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $index = $this->anticipate('Podaj nazwę indexu.', ['galleries', 'nagrania', 'images', 'records']);
        $this->info($index);

        $response = Curl::to('http://localhost:9200/'.$index)->delete();

        $this->info($response);
    }
}
