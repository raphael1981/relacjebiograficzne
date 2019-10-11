<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//         $this->call(RegionsTableSeeder::class);
//         $this->call(UsersTableSeeder::class);
//         $this->call(CustomersTableSeeder::class);
//         $this->call(RecordsTableSeeder::class);
//         $this->call(IntervieweesTableSeeder::class);
//         $this->call(CategoriesTableSeeder::class);
//         $this->call(ArticlesTableSeeder::class);
//         $this->call(PicturesTableSeeder::class);
//         $this->call(GalleriesTableSeeder::class);
//         $this->call(HookContentsTableSeeder::class);
//        $this->call(RecordgablesTableSeeder::class);
//        $this->call(PeriodsTableSeeder::class);
//        $this->call(TagsTableSeeder::class);
//        $this->call(IntervalsTableSeeder::class);
//        $this->call(ThreadsTableSeeder::class);
        $this->call(IntervalsTableSeeder::class);
//        $this->call(PlacesTableSeeder::class);
//        $this->call(PeriodgablesTableSeeder::class);
//        $this->call(IntervalgablesTableSeeder::class);
//        $this->call(ThreadgablesTableSeeder::class);
//        $this->call(PlacegablesTableSeeder::class);
//        $this->call(TaggablesTableSeeder::class);

        $this->call(TagFragmentTableSeeder::class);
        $this->call(IntervalFragmentTableSeeder::class);
        $this->call(PlaceFragmentTableSeeder::class);
        

    }
}
