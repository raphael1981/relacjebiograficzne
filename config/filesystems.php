<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. A "local" driver, as well as a variety of cloud
    | based drivers are available for your choosing. Just store away!
    |
    | Supported: "local", "ftp", "s3", "rackspace"
    |
    */

    'default' => 'local',

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => 's3',

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => 'your-key',
            'secret' => 'your-secret',
            'region' => 'your-region',
            'bucket' => 'your-bucket',
        ],
		
		
		 's3' => [
		 'driver' => 's3',
		 'key'    => env('AHM_KEY'),
		 'secret' => env('AHM_SECRET'),
		 'region' => env('AHM_REGION'),
		 'bucket' => env('AHM_BUCKET'),
		 ],
		
		'photos' => [
				'driver' => 'local',
               	'root' => storage_path('app/photos'),
				'visibility' => 'public',
		],
		'pictures' => [
				'driver' => 'local',
				'root' => storage_path('app/pictures'),
				'visibility' => 'public',
		],
		'portraits' => [
				'driver' => 'local',
				'root' => storage_path('app/portraits'),
				'visibility' => 'public',
		],
		
		'primaryphoto' => [
				'driver' => 'local',
				'root' => storage_path('app/primaryphoto'),
				'visibility' => 'public',
		],
		
		'data' => [
				'driver' => 'local',
				'root' => storage_path('app/data'),
				'visibility' => 'public',
		],
		'es_prop' => [
				'driver' => 'local',
				'root' => storage_path('app/es_prop'),
				'visibility' => 'public',
		],

    ],

];
