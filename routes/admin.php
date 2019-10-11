<?php



$domains = config('services')['domains'];

Route::group(['https'], function() use($domains) {


});


Route::group(['domain' => $domains['admin']], function () {

//    Route::auth();

    Route::get('/', 'Auth\LoginController@showLoginForm');
    Route::post('/login', 'Auth\LoginController@login');
    Route::post('/logout', 'Auth\LoginController@logout');

    Route::get('password/email', 'Auth\ForgotPasswordController@showLinkRequestForm');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');


    /*
     * Ajax
     */


    Route::get('get/media/images/{srctype}/{disk?}', 'Super\AjaxController@getMediaImages');
    Route::post('upload/file/{disk}', 'Super\AjaxController@uploadImageTo');
    Route::post('get/image/by/data', 'Super\AjaxController@getImageByData');
    Route::post('upload/filegallery/{disk}', 'Super\AjaxController@uploadPrimary');
    Route::get('get/tags/by/query', 'Super\AjaxController@getTagsByQuery');
    Route::get('get/places/by/query', 'Super\AjaxController@getPlacesByQuery');
    Route::get('get/all/tags', 'Super\AjaxController@getAllTags');
    Route::get('get/all/places', 'Super\AjaxController@getAllPlaces');
    Route::post('get/media/sources', 'Super\AjaxController@getMediaSources');
    Route::get('get/all/interviewees', 'Super\AjaxController@getAllInterviewees');
    Route::post('upload/file/record/xml', 'Super\AjaxController@uploadRecordXml');
    Route::put('remove/xml/file', 'Super\AjaxController@removeRecordXml');
    Route::get('get/all/records', 'Super\AjaxController@getAllRecords');
    Route::post('upload/image/to/{disk}', 'Super\AjaxController@uploadImageToDisk');
    Route::put('remove/image/from/{disk}', 'Super\AjaxController@removeImageFromDisk');
    Route::get('get/all/redactors', 'Super\AjaxController@getAllRedactors');

    /*
     * Ajax
     */

    /*
     * Images Backend Routes
     */


    /*
     * Images Backend Routes
     */


    /*customer verification link*/

    Route::get('/customer/link/akcept/{cid}/{token}', 'Super\CustomersController@akceptCustomerRegisterByEmail');

    /*cutomer verification link*/


    Route::group(['prefix' => '/administrator'], function() {

        Route::get('/', 'Super\FrontController@indexAction');
        Route::get('/customers', 'Super\CustomersController@indexAction');
        Route::get('/interviewees', 'Super\IntervieweesController@indexAction');
        Route::get('/redactors', 'Super\RedactorsController@indexAction');
        Route::get('/employees', 'Super\EmployeeController@indexAction');
        Route::get('/catergories', 'Super\CategoriesController@indexAction');
        Route::get('/articles', 'Super\ArticlesController@indexAction');
        Route::get('/records', 'Super\RecordsController@indexAction');
        Route::get('/periods', 'Super\PeriodsController@indexAction');
        Route::get('/intervals', 'Super\IntervalsController@indexAction');
        Route::get('/tags', 'Super\TagsController@indexAction');
        Route::get('/threads', 'Super\ThreadsController@indexAction');
        Route::get('/places', 'Super\PlacesController@indexAction');
        Route::get('/elasticsearch', 'Admin\Super\ElasticSearchController@indexAction');


        /*
         * Cutomers routes
         */
        Route::post('/get/customers', 'Super\CustomersController@getCustomers');
        Route::put('/akcept/customer/register', 'Super\CustomersController@akceptRegister');


        /*
         * Articles routes
         */
        Route::post('/get/articles', 'Super\ArticlesController@getArticles');
        Route::put('/update/article/data', 'Super\ArticlesController@updateData');
        Route::put('/create/new/article', 'Super\ArticlesController@createNewAritcle');
        Route::put('/update/article/full/data', 'Super\ArticlesController@updateAritcleFullData');
        Route::get('/get/article/{id}', 'Super\ArticlesController@getArticle');
        Route::put('/update/publish/date/{id}', 'Super\ArticlesController@updatePublishDate');
        Route::put('/update/main/article', 'Super\ArticlesController@markMainArticle');
        Route::post('/article/get/raport/before/delete', 'Super\ArticlesController@getRaportBeforeDelete');
        Route::put('/delete/article', 'Super\ArticlesController@deleteRecord');


        /*
         * Interviewees routes
         */
        Route::post('/get/interviewees', 'Super\IntervieweesController@getInterviewees');
        Route::put('/update/interviewee/data', 'Super\IntervieweesController@updateData');
        Route::put('/create/new/interviewee', 'Super\IntervieweesController@createNewInterviewee');
        Route::get('/get/interviewee/{id}', 'Super\IntervieweesController@getIntervieweeData');
        Route::put('/update/full/interviewee/data/{id}', 'Super\IntervieweesController@updateFullData');
        Route::post('/interviewee/get/raport/before/delete', 'Super\IntervieweesController@getRaportBeforeDelete');
        Route::put('/delete/interviewee', 'Super\IntervieweesController@deleteInterviewee');


        /*
         * Employee routes
         */
        Route::post('/get/employees', 'Super\EmployeeController@getEmployees');
        Route::put('/update/employee/data', 'Super\EmployeeController@updateData');
        Route::put('/create/new/employee', 'Super\EmployeeController@createNewEmployee');
        Route::put('/update/data/employee', 'Super\EmployeeController@updateEmployee');
        Route::post('/check/is/employee/in/base', 'Super\EmployeeController@checkIsEmailEmployeeExist');
        Route::post('/check/is/employee/in/base/except/current', 'Super\EmployeeController@checkIsEmailEmployeeExistExceptCurrent');
        Route::get('/get/full/employee/data/by/id/{id}', 'Super\EmployeeController@getFullEmployeeData');
        Route::post('/user/get/raport/before/delete', 'Super\EmployeeController@getRaportBeforeDelete');
        Route::put('/delete/user', 'Super\EmployeeController@deleteEmployee');
        Route::put('/change/employee/password', 'Super\EmployeeController@changePassword');


        /*
         * Records routes
         */

        Route::post('/get/records', 'Super\RecordsController@getRecords');
        Route::put('/update/record/data', 'Super\RecordsController@updateData');
        Route::get('/get/full/record/data/{id}', 'Super\RecordsController@getFullRecordData');
        Route::get('/get/full/record/data/intervals/{id}', 'Super\RecordsController@getFullRecordDataIntervals');
        Route::put('/update/record/tags', 'Super\RecordsController@updateRecordTags');
        Route::put('/update/fragment/tags', 'Super\RecordsController@updateFragmentTags');
        Route::put('/update/fragment/places', 'Super\RecordsController@updateFragmentPlaces');
        Route::put('/add/new/record', 'Super\RecordsController@addNewRecord');
        Route::put('/update/edit/record', 'Super\RecordsController@updateEditRecord');
        Route::get('/get/record/data/{id}', 'Super\RecordsController@getRecord');
        Route::get('/get/record/interviewees/{id}', 'Super\RecordsController@getRecordInterviewees');
        Route::get('/get/record/redactors/{id}', 'Super\RecordsController@getRecordRedactors');
        Route::post('/check/is/interval', 'Super\RecordsController@checkIsInterval');
        Route::post('/add/new/interval', 'Super\RecordsController@addInterval');
        Route::put('/link/fragment/by/interval', 'Super\RecordsController@linkFragmentByInterval');
        Route::put('/link/fragment/interval/remove', 'Super\RecordsController@linkFragmentIntervalRemove');
        Route::put('/update/fragment/intervals/get', 'Super\RecordsController@updateGetFragmentIntervals');
        Route::post('/record/get/raport/before/delete', 'Super\RecordsController@getRaportBeforeDelete');
        Route::put('/delete/record', 'Super\RecordsController@deleteRecord');
        Route::delete('/del/fragment/{rid}', 'Super\RecordsController@deleteFragment');
        Route::post('update/record/fragment','Super\RecordsController@updateContentFragment');
        Route::put('update/time/fragment','Super\RecordsController@updateTimeFragment');


        /*
         * Redactors routes
         */

        Route::post('/get/redactors', 'Super\RedactorsController@getRedactors');
        Route::put('/update/redactor/data', 'Super\RedactorsController@updateData');
        Route::put('/create/new/redactor', 'Super\RedactorsController@createNewRedactor');
        Route::put('/update/redactor/all', 'Super\RedactorsController@updateAllDataRedactor');
        Route::get('/get/redactor/{id}', 'Super\RedactorsController@getRedactor');
        Route::post('/redactor/get/raport/before/delete', 'Super\RedactorsController@getRaportBeforeDelete');
        Route::put('/delete/redactor', 'Super\RedactorsController@deleteRedactor');


        /*
         * Period routes
         */

        Route::post('/get/periods', 'Super\PeriodsController@getPeriods');
        Route::post('/check/is/period/exist', 'Super\PeriodsController@checkIsPeriodExist');
        Route::put('/change/period/data', 'Super\PeriodsController@changePeriodName');
        Route::get('/get/full/period/data/{id}', 'Super\PeriodsController@getFullPeriodData');
        Route::get('/get/all/records/to/link', 'Super\PeriodsController@recordsGetAll');
        Route::put('/remove/linked/period/record', 'Super\PeriodsController@removeLinkedPeriod');
        Route::put('/update/linked/periods/array', 'Super\PeriodsController@updateLinkedRecordsArray');


        /*
         * Interval routes
         */


        Route::post('/get/intervals', 'Super\IntervalsController@getIntervals');
        Route::post('/interval/get/raport/before/delete', 'Super\IntervalsController@getRaportBeforeDelete');
        Route::put('/delete/interval', 'Super\IntervalsController@deleteInterval');
        Route::post('check/is/interval/exists', 'Super\IntervalsController@checkIsIntervalExist');
        Route::post('/add/not/linked/new/interval', 'Super\IntervalsController@addInterval');


        /*
        * Tag routes
        */

        Route::post('/get/tags', 'Super\TagsController@getTags');
        Route::post('/check/is/tag/exist', 'Super\TagsController@checkIsTagExist');
        Route::put('/change/tag/data', 'Super\TagsController@changeTagName');
        Route::get('/get/full/tag/data/{id}', 'Super\TagsController@getFullTagData');
        Route::put('/add/new/tag', 'Super\TagsController@addNewTag');
        Route::post('/tag/get/raport/before/delete', 'Super\TagsController@getRaportBeforeDelete');
        Route::put('/delete/tag', 'Super\TagsController@deleteTag');


        /*
         * Thread routes
         */

        Route::post('/get/threads', 'Super\ThreadsController@getThreads');
        Route::post('/check/is/thread/exist', 'Super\ThreadsController@checkIsThreadExist');
        Route::put('/change/thread/data', 'Super\ThreadsController@changeThreadName');
        Route::get('/get/full/thread/data/{id}', 'Super\ThreadsController@getFullThreadData');
        Route::put('/remove/linked/thread/record', 'Super\ThreadsController@removeLinkedThread');
        Route::put('/update/linked/threads/array', 'Super\ThreadsController@updateLinkedRecordsArray');
        Route::put('/add/new/tread', 'Super\ThreadsController@addNewThread');
        Route::post('/thread/get/raport/before/delete', 'Super\ThreadsController@getRaportBeforeDelete');
        Route::put('/delete/thread', 'Super\ThreadsController@deleteThread');


        /*
         * Places routes
         */

        Route::post('/get/places', 'Super\PlacesController@getPlaces');
        Route::post('/check/is/place/exist', 'Super\PlacesController@checkIsPlaceExist');
        Route::put('/change/place/data', 'Super\PlacesController@changePlaceName');
        Route::put('/create/new/place', 'Super\PlacesController@createNewPlace');
        Route::post('/place/get/raport/before/delete', 'Super\PlacesController@getRaportBeforeDelete');
        Route::put('/delete/place', 'Super\PlacesController@deletePlace');




        /*
         * ElasticSearch
         */

        Route::get('/elasticsearch/make/records/index', 'Admin\Super\ElasticSearchController@indexMakeRecords');
        Route::get('/elasticsearch/get/records/index', 'Admin\Super\ElasticSearchController@indexGetRecords');
        Route::get('/elasticsearch/force/make/records/index', 'Admin\Super\ElasticSearchController@indexForceMakeRecords');


        Route::get('/elasticsearch/make/images/index', 'Admin\Super\ElasticSearchController@indexMakeImages');
        Route::get('/elasticsearch/get/images/index', 'Admin\Super\ElasticSearchController@indexGetImages');
        Route::get('/elasticsearch/force/make/images/index', 'Admin\Super\ElasticSearchController@indexForceMakeImages');


        Route::get('/elasticsearch/make/gallery/index', 'Admin\Super\ElasticSearchController@indexMakeGallery');
        Route::get('/elasticsearch/get/gallery/index', 'Admin\Super\ElasticSearchController@indexGetGallery');
        Route::get('/elasticsearch/force/make/gallery/index', 'Admin\Super\ElasticSearchController@indexForceMakeGallery');


        Route::get('/elasticsearch/get/monitor/by/{id}', 'Admin\Super\ElasticSearchController@getMonitorById');

        /*
         * ElasticSearch
         */


    });



    /*
     * Images Frontend Routes And Backend
    */

    Route::get('/image/{filename}/{disk}/{basesize?}/{crop?}/{position?}/{width?}/{height?}/{bottom?}/{left?}', 'ImageController@getImageFront');
    Route::get('/img/filter/{filename}/{disk}/{basesize?}/{filter?}', 'ImageController@getImageFilterSize');
    Route::get('/interviewee/images/get/{disk}/{id}', 'ImageController@getIntervieweeImage');

    /*
     * Images Frontend Routes And Backend
     */




    Route::group(['prefix' => '/redactor'], function() {

        Route::get('/', 'Employee\FrontController@indexAction');

    });


});