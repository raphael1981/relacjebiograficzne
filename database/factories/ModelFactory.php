<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {

//    static $password;

    return [
        'name' => $faker->firstName,
        'surname' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt('wiliak100'),
        'permission' => $faker->regexify('(employee|super)'),
        'status' => 1,
        'remember_token' => str_random(10),
    ];

});


$factory->define(App\Entities\Customer::class, function (Faker\Generator $faker) {

    $type = $faker->regexify('(osoba prywatna|instytucja)');

    return [
        'name'=>$faker->firstName,
        'surname'=>$faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt('wiliak100'),
        'phone' => $faker->phoneNumber,
        'customer_type' => $type,
        'institution_name' => ($type=='instytucja')?$faker->company:'',
        'register_target'=>'artykuły naukowe',
        'status' => 1,
        'verification_token'=>'',
        'remember_token' => str_random(10),
    ];
});


$factory->define(App\Entities\Record::class, function (Faker\Generator $faker) {

    $title = $faker->realText(100);

    return [
        'title'=>$title,
        'alias'=>str_slug($title),
        'signature' => str_slug($faker->text(27)),
        'source' => '/data/...',
        'xmltrans'=>'/data/'.str_slug($faker->text(5)).'.xml',
        'description' => $faker->sentence(200,255),
        'summary'=>$faker->sentence(270,355),
        'type' => $faker->regexify('(audio|video)'),
        'status' => 1,
        'published_at'=>$faker->dateTime
    ];

});


$factory->define(App\Entities\Fragment::class, function (Faker\Generator $faker) {

    return [
        'content'=>$faker->realText(1700).' tipo'
    ];

});


$factory->define(App\Entities\Interviewee::class, function (Faker\Generator $faker) {

    return [
        'name'=>$faker->firstName,
        'surname'=>$faker->lastName,
        'biography'=>$faker->sentence(200,true),
        'portrait'=>'witkacy.jpg',
        'disk'=>'portraits',
        'status'=>1
    ];

});


$factory->define(App\Entities\Article::class, function (Faker\Generator $faker) {

    $title = $faker->sentence(6,10);


    $path = storage_path().'/app/pictures/';
    $array = glob($path.'*',GLOB_BRACE);
    $images = [];
    foreach($array as $key=>$value){

        $images[$key] = new \stdClass();
        $images[$key]->fullpath = $value;
        $images[$key]->filename = str_replace($path,'',$value);

    }

    //'intro_image'=>($faker->boolean)?$faker->image(storage_path().'/app/pictures/', 1024, $faker->numberBetween(390,900), 'nature', false):'',


    return [
        'category_id'=>1,
        'title'=>$title,
        'alias'=>str_slug($title,'-'),
        'intro_image'=>($faker->boolean)?$images[$faker->numberBetween(0,55)]->filename:'',
        'disk'=>'pictures',
        'intro'=>$faker->sentence(10,30),
        'content'=>$faker->sentence(70,300),
        'status'=>1,
        'published_at'=>$faker->dateTime
    ];

});


$factory->define(App\Entities\Gallery::class, function (Faker\Generator $faker) {

    $name = $faker->sentence(6,10);

    return [
        'name'=>$name,
        'alias'=>str_slug($name),
        'description'=>$faker->sentence(40,100),
        'photos'=>'',
        'regexstamp'=>'',
        'status'=>1
    ];

});



$factory->define(App\Entities\Picture::class, function (Faker\Generator $faker) {

    $path = storage_path().'/app/pictures/';
    $array = glob($path.'*',GLOB_BRACE);
    $images = [];
    foreach($array as $key=>$value){

        $images[$key] = new \stdClass();
        $images[$key]->fullpath = $value;
        $images[$key]->filename = str_replace($path,'',$value);

    }

    return [
        'source'=>$images[$faker->numberBetween(0,55)]->filename,
        'disk'=>'pictures',
        'description'=>$faker->sentence(40,100)
    ];

});


$factory->define(App\Entities\Redactor::class, function (Faker\Generator $faker) {

    return [
        'name'=>$faker->firstName,
        'surname'=>$faker->lastName,
        'email'=>$faker->email,
        'profession'=>$faker->regexify('(fotograf|dzienikarz)'),
        'status'=>1
    ];

});


$factory->define(App\Entities\Period::class, function (Faker\Generator $faker) {

    $name = $faker->sentences(1,2);

    return [
        'name'=>$name,
        'alias'=>str_slug($name,'-')
    ];

});


$factory->define(App\Entities\Interval::class, function (Faker\Generator $faker) {

    $name = $faker->sentences(1,2);

    $start = \Carbon\Carbon::now();
    $stop = \Carbon\Carbon::now()->addMonth();

    $start_string = $start->year.'-'.$start->month.'-'.$start->day;
    $stop_string = $stop->year.'-'.$stop->month.'-'.$stop->day;

    return [
        'name'=>$name,
        'alias'=>str_slug($name,'-'),
        'begin'=>$start_string,
        'end'=>$stop_string
    ];

});


$factory->define(App\Entities\Thread::class, function (Faker\Generator $faker) {

    $name = $faker->sentences(1,2);

    return [
        'name'=>$name,
        'alias'=>str_slug($name,'-')
    ];

});


$factory->define(App\Entities\Place::class, function (Faker\Generator $faker){

    $name = ($faker->boolean)?$faker->city:$faker->address;

    $is_geo = $faker->boolean;

    return [
        'name' => $name,
        'alias' => str_slug($name,'-'),
        'lat'=>($is_geo)?$faker->latitude:null,
        'lng'=>($is_geo)?$faker->longitude:null
    ];
});