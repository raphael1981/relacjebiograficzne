<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\CacheSearchFrase::class,
        Commands\IndexSearch::class,
        Commands\BackUpCustomers::class,
        Commands\IndexPhotoIptc::class,
        Commands\LuceneIndexPlaces::class,
        Commands\LuceneIndexPlacesAll::class,
//        Commands\ElsIndexFragmentsTags::class,
//        Commands\ElsIndexFragmentsIntervals::class,
//        Commands\ElsIndexImagesOnly::class,
//        Commands\ElsAssociateImagesFragments::class,
//        Commands\ElsIndexFragmentsForFulltext::class,
//        Commands\ElsRecordFragmentInOne::class,
//        Commands\ElsTagsIntervalsInOne::class,
//        Commands\ElsGroupImagesInterviewees::class,
//        Commands\ElasticSearch\FragmentIndexFull::class,
//        Commands\ElasticSearch\GroupImagesInterviewees::class,
//        Commands\ElasticSearch\ImagesFulltextIndex::class,
//        Commands\ElasticSearch\RecordsFragments::class,
//        Commands\ElasticSearch\IndexGalleriesIptc::class,
        Commands\ElasticSearch\DeleteIndex::class,
        Commands\FindImagesTags::class,
        Commands\ElasticSearch\Panel\RecordsIndex::class,
        Commands\ElasticSearch\Panel\ImagesIndex::class,
        Commands\ElasticSearch\Panel\GalleryIndex::class,
        Commands\ElasticSearch\RecordsIndex::class,
        Commands\ElasticSearch\ImagesIndex::class,
        Commands\ElasticSearch\GalleriesIndex::class,
        Commands\Interviewee\SortStringMake::class,
        Commands\ElasticSearch\Test\RecordsIndex::class,
        Commands\ElasticSearch\Test\FragmentsIndex::class,
        Commands\ElasticSearch\Test\FragmentsSearch::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
