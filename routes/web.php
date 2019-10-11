<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/



$domains = config('services')['domains'];

Route::group(['https'], function() use($domains) {


});




//Route::group(['domain' => $domains['customers']], function () {
$appRoutes = function(){


    /*
     * Front Page
     */

    Route::get('/', 'Customer\FrontController@indexAction')->middleware('prevent-back-history');
    Route::get('/get/articles/{cid}/{start}/{limit}', 'Customer\AjaxController@getArticles');
    Route::get('/get/last/records/{start}/{limit}', 'Customer\AjaxController@getLastFrontRecords');

    /*
     * Front Page
     */


    /*
     * Search and Auth
     */



    Route::any('/autoryzacja', 'Customer\LoginController@showAuthForms')->middleware('prevent-back-history')->name('authroute');
    Route::get('/customer/verify/{token}', 'Customer\LoginController@verifyCustomer');
    Route::post('/is/free/email', 'Customer\LoginController@checkIsEmailFree');
    Route::put('/customer/register', 'Customer\LoginController@registerCustomer');
    Route::post('/customer/login', 'Customer\LoginController@loginCustomer');
////////////////////////////////////////////////////////
    Route::put('/test', function(){return "ala ma kota";});
///////////////////////////////////////////////////////
    Route::post('/customer/logout', 'Customer\LoginController@logout')->middleware('prevent-back-history');
    Route::post('/customer/recaptcha/check', 'Customer\LoginController@checkReCaptcha');
    Route::any('/przypomnienie/hasla', 'Customer\LoginController@rememberPassword');
    Route::any('/zmien/haslo/{token}', 'Customer\LoginController@changePassword');
    Route::post('/check/reset/customer/email', 'Customer\LoginController@checkIsEmailCanReset');
    Route::get('/customer/reset/{token}', 'Customer\LoginController@resetPassword');
    Route::post('/change/customer/password', 'Customer\LoginController@changePassword');






    /*
     * Transkrypcje
     */

    Route::any('/{type}/{slug}/{time?}', 'Customer\TranscriptionController@indexTranscription')
        ->where(['type' => '(audio|video)', 'slug' => '([0-9]+)\-([a-z0-9-]+)'])->middleware('prevent-back-history');
    Route::any('/demo/{type}/{slug}/{time?}', 'Customer\TranscriptionController@indexNoAuthTranscription')
        ->where(['type' => '(audio|video)', 'slug' => '([0-9]*)\-([a-z0-9-]+)']);
    Route::get('/get/linked/records/{id}', 'Customer\AjaxController@getLinkedRecords');


    /*
     * Transkrypcje
     */


    /*
     * Images Frontend Routes And Backend
    */

    Route::get('/image/{filename}/{disk}/{basesize?}/{crop?}/{position?}/{width?}/{height?}/{bottom?}/{left?}', 'ImageController@getImageFront');

    Route::get('/pic/{filename}/{disk}/{basesize?}', 'ImageController@getImage');

    Route::get('/img/filter/{filename}/{disk}/{basesize?}/{filter?}', 'ImageController@getImageFilterSize');
    Route::get('/interviewee/images/get/{disk}/{id}', 'ImageController@getIntervieweeImage');

    /*
     * Images Frontend Routes And Backend
     */


    /*
     * News Routes
     */

    Route::get('/{category_slug}/{article_slug}/', 'Customer\FrontController@indexArticle')
        ->where(['category_slug' => '([0-9]+)\-([a-z0-9-]+)', 'article_slug' => '([0-9]+)\-([a-z0-9-]+)']);
    Route::get('/get/article/data/{aid}', 'Customer\AjaxController@getArticleGalleryData');

    /*
     * News Routes
     */

    /*
     * Interviewees Routes
     */

    Route::get('/swiadkowie', 'Customer\FrontController@indexInterviewees');
    Route::post('/get/interviewees/index/data', 'Customer\AjaxController@getIntevieweesIndexData');
    Route::get('/get/interviewees/all', 'Customer\AjaxController@getAllInterviewees');

    /*
     * Interviewees Routes
     */


    /*
     * Galleries Routes
     */

//    Route::get('/galerie', 'Customer\FrontController@indexGalleries');
    Route::get('/galeria/{slug}', 'Customer\FrontController@indexGallery')
        ->where(['slug' => '([0-9]+)\-([a-z0-9-]+)']);
    Route::get('/get/galleries/{start}/{limit}', 'Customer\AjaxController@getGalleries');

    Route::get('/get/gallery/{id}/{start}/{limit}', 'Customer\AjaxController@getGallery');
    Route::get('/get/full/gallery/{id}', 'Customer\AjaxController@getFullGallery');

    Route::get('/get/iptc/gallery/{id}/{mode}', 'Customer\AjaxController@getGalleryIptc');
    /*
     * Galleries Routes
     */



    /*
     * Threads routes
     */

    Route::get('/tematy', 'Customer\FrontController@indexThreads');
    Route::get('/temat/{thred_slug}', 'Customer\FrontController@indexThread')
        ->where(['thred_slug' => '([0-9]+)\-([a-z0-9-]+)']);
    Route::get('/get/all/threads', 'Customer\AjaxController@getAllThreads');
    Route::get('/get/thread/records/{id}', 'Customer\AjaxController@getThreadRecords');


    /*
     * Threads routes
     */


    /*
     * Project Routes
     */

    Route::get('/projekt', 'Customer\FrontController@indexProject');

    /*
     * Project Routes
     */


    /*
     * Cms Routes
     */

    Route::get('/{slug}', 'Customer\FrontController@indexHookCms')
        ->where(['slug' => '([0-9]+)\-([a-z0-9-]+)']);

    /*
     * Cms Routes
     */


    /*
      *
      * Images Search
      *
      */

    Route::get('/images/search', 'Customer\FrontController@indexImages');
    Route::post('/get/search/images/by/criteria', 'Customer\AjaxController@getSearchImages');

    /*
     *
     * Images Search
     *
     */


    /*
    * Search
    */
//    Route::get('/wyszukiwanie', 'Customer\FrontController@indexSearch');
//    Route::post('/ahm/search/data', 'Search\AjaxController@getData');
//    Route::post('/ahm/search/cache/result', 'Search\AjaxController@cacheData');
//    Route::get('/ahm/search/cache/get', 'Search\AjaxController@getNextCacheData');
    /*
     * Search
     */


    /*
     * Advanced Search
     */

    Route::get('/advanced/search', 'Customer\FrontController@indexAdvancedSearch');
    Route::get('/get/all/places', 'Customer\AjaxController@getAllPlaces');
    Route::post('/ajax/advanced/search/chunk', 'Search\AdvancedAjaxController@searchByCriteria');
    Route::post('/ajax/advanced/search/all', 'Search\AdvancedAjaxController@searchByCriteriaAll');


    /*
     * Advanced Search
     */



    /*
     * Elasticsearch
     */

    Route::get('/galerie', 'Customer\FrontController@indexImagesElasticsearch')->name('search');
    Route::post('/get/search/elastic/images/by/criteria', 'Search\ElasticSearchAjax\ImagesController@getSearchImagesElastic');

    Route::get('/wyszukiwanie', 'Customer\FrontController@indexSearchElasticsearch');
    Route::post('/ajax/elasticsearch/by/phrase', 'Search\ElasticSearchAjax\NormalController@searchByCriteria');
    Route::post('/ajax/elasticsearch/index/keywords', 'Search\ElasticSearchAjax\NormalController@searchByIndexCriteria');
    Route::post('/ajax/elasticsearch/index/by/criteria', 'Search\ElasticSearchAjax\NormalController@searchByIndexCriteriaNew');

    Route::get('/ajax/get/alphabet','Search\ElasticSearchAjax\NormalController@getAlphabetIndex');


    Route::post('/ajax/get/places/by/letter','Search\ElasticSearchAjax\NormalController@getPlacesByLetter');
    Route::post('/ajax/get/tags/by/letter','Search\ElasticSearchAjax\NormalController@getTagsByLetter');
    Route::post('/ajax/get/intervals/by/letter','Search\ElasticSearchAjax\NormalController@getIntervalsByLetter');


    /*
     * Elasticsearch
     */



};

Route::middleware('site-request-monitor')->group(function() use ($domains,$appRoutes){

});

Route::group(array('domain' => $domains['customers']), $appRoutes);
Route::group(array('domain' => 'www.'.$domains['customers']), $appRoutes);



/*
 * Config Routes Ajax Get
 */


Route::get('/get/auth/customer/config', function (\App\ConfigClasses\CustomerAuthConfig $customer) {
    return $customer->inputs;
});


Route::get('/get/ocupation', function (\App\ConfigClasses\JsonData $jsonData) {
    return $jsonData->getOcupations();
});


Route::get('/get/about-project/images', function (\App\ConfigClasses\JsonData $jsonData) {
    return $jsonData->getImagesFromPublicFolderToSwipe('photos');
});


Route::get('/get/register/targets', function (\App\ConfigClasses\JsonData $jsonData) {
    return $jsonData->getRegisterTargets();
});


Route::get('/get/customer/statuses', function (\App\ConfigClasses\JsonData $jsonData) {
    return $jsonData->getCustomerStatuses();
});

Route::get('/get/article/categories', function (\App\ConfigClasses\JsonData $jsonData) {
    return $jsonData->getArticleCategories();
});

Route::get('/get/redactors/professions', function (\App\ConfigClasses\JsonData $jsonData) {
    return $jsonData->getRedactorsProfessions();
});


Route::get('/get/regions', function (\App\ConfigClasses\BaseDataService $instance) {
    return $instance->getRegions();
});

Route::get('/get/ajax/auth', function (\App\Helpers\AuthAjaxCheck $instance) {
    return $instance->auth;
});

Route::get('/get/tag/by/id/{id}', function($id){
    return \App\Entities\Tag::find($id);
});

Route::get('/get/place/by/id/{id}', function($id){
    return \App\Entities\Place::find($id);
});

Route::get('/get/interval/by/id/{id}', function($id){
    return \App\Entities\Interval::find($id);
});

/*
 * Config Routes Ajax Get
 */







//////////////////////////Test Routes///////////////////////////////////

Route::group(['domain' => $domains['admin']], function () {

    Route::get('testowa', 'TestController@indexAdmin');

});


Route::group(['domain' => $domains['customers']], function () {

    Route::get('test/tags/txt', 'TestController@tagsFromFile');

    Route::get('test2', 'TestController@indexCustomers');

    Route::get('test3/{id}', 'TestController@testThree');

    Route::get('/testrans/{id}', 'TestController@indexCustomers');

    Route::get('/index/show', 'TestController@indexTest');
    Route::get('/index/adv', 'TestController@indexTest2');

//    Route::any('/posttest', 'TestController@postTest')->middleware('cors');

});

Route::group(['domain' => $domains['customers']], function () {

    Route::any('/posttest', 'TestController@postTest')->middleware('cors');
    Route::any('/test/xml/send', 'TestController@postCzasooznaczacz')->middleware('cors');

});

Route::put('/putest',function(){return 'put is good';});

//Route::get('/', function () {
//
//
//        $record = new \App\Entities\Record();
//        $record->save();
//        $record = \App\Entities\Record::find(5);
//        $interviewee = new \App\Entities\Interviewee();
//        $interviewee->save();
//
//        $fragment = new \App\Entities\Fragment();
//        $fragment->save();
//
//        $interviewee = \App\Entities\Interviewee::find(3);
//
//        $fragment->interviewees()->save($interviewee);
//
//    return view('welcome');
//});
