<?php

namespace App\Console\Commands\ElasticSearch\Test;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Entities\Record;
use Elasticsearch\Client;
use CSD\Image\Image as ImageCSD;
use App\Entities\Fragment;
use App\Entities\Tag;
use Illuminate\Support\Facades\Storage;
use Ixudra\Curl\Facades\Curl;


class FragmentsSearch extends Command
{
    private $elasticsearch;
    private $curl;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:els:fragments:search';

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
        $text = $this->ask('Podaj frazę do wyszukiwania');

        $this->info($text);

        $array_search = [
            "index" => "records",
            "type" => "elements",
            "body" => [
                "from" => 0,
                "size" => 2000,
                "query" => [
                    "bool" => [
                        "must"=>[
                            ["match_phrase_prefix" => [
                                "content" => [
                                    "query" => $text,
                                    "analyzer" => 'content.super'
                                ]
                            ]
                            ]
                        ]
                    ]
                ],
                "highlight" => [
                    "fields" => [
                        "fragments.content" => ["pre_tags" => "<strong>", "post_tags" => "</strong>"]
                    ]
                ]
            ]
        ];


        $response = $this->elasticsearch->search($array_search);

        $this->info($response);

    }
}
