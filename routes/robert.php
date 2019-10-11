<?php


$domains = config('services')['domains'];


Route::group(['domain' => $domains['admin']], function () {


    Route::group(['prefix' => '/administrator'], function() {

        Route::get('/galleries', 'Super\GalleryController@indexAction');        
		Route::get('/galleries/iptc', 'Super\GalleryController@getIPTC');
		Route::get('galleries/exif/{path}','Super\GalleryController@getExif');
		Route::get('galleries/data','Super\GalleryController@getPictures');
		Route::delete('galleries/data','Super\GalleryController@removePicture');
		Route::get('galleries/iptcinfo','Super\GalleryController@getIPTCdata');		
		
		Route::get('transcription','Super\FrontController@goToTranscription');
		 
		 //http://adminahm.zbiglem.pl/administrator/galleries/images/Kikiewicz_Zbigniew_026.jpg
		/*
		Route::get('/galleries/images/{filename}', function ($filename){
			     $path = '/usr/home/Wiliak/domains/videohistoria.zbiglem.pl/public_html/storage/app/photos' . '/' . $filename;
					if(!File::exists($path)) abort(404);
					$file = File::get($path);
					$type = File::mimeType($path);	
					$response = Response::make($file, 200);
					$response->header("Content-Type", $type);
					return $response;
              });
		*/
		//Route::resource('/galleries/images/{filename}/{basesize?}', 'Super\GalleryController@getImage');
        Route::get('photo/{filename}/{disk}/{basesize?}', 'Super\GalleryController@getImageFront');
        Route::post('/galleries/data/store','Super\GalleryController@store');		
        Route::get('/galleries/data/test','Super\GalleryController@test');		
		 Route::get('/galleries/data/persons','Super\GalleryController@getPersons');				 
		 Route::get('/galleries/data/subjects','Super\GalleryController@getSubjects');		 
		 Route::put('/galleries/data/subject','Super\GalleryController@loadPhotoGall');
		 Route::get('/galleries/data/names','Super\GalleryController@getGalleries');		 
		 Route::put('/galleries/person/order','Super\GalleryController@orderPictures');		
		 Route::put('/galleries/image/descript','Super\GalleryController@changeDescript');
		 Route::any('/galleries/data/galltitles','Super\GalleryController@setGallTitles');
		 Route::put('galleries/data/photoName','Super\GalleryController@setPhotographerName');
		 Route::put('galleries/data/caption','Super\GalleryController@setCaption');
         //Route::get('galleries/galleryid/{id}','Super\GalleryController@getSubjectGalleryPhotos');
       Route::get('galleries/galleryid/{id}','Super\GalleryController@getSubjectGalleryPhotos');
    });

});
Route::get('/elasticsearch', 'Test\SearchController@searchElasticIndex');
Route::get('/elasticsearch/data/{phrase}', 'Test\SearchController@getElasticSearch');
Route::post('/get/essearch','Test\SearchController@searchElasticForRecord');

Route::get('/articles', 'Test\GetOutside@getArticles');