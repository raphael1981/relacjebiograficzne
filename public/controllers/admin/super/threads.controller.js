var app = angular.module('app',['ngSanitize', 'ngRoute', 'ui.tinymce', 'xeditable', 'ui.bootstrap.datetimepicker','ngDialog','wysiwyg.module','ngFileUpload','ui.select'], function($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});

app.config(['$httpProvider', function ($httpProvider) {
    $httpProvider.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');
    $httpProvider.defaults.useXDomain = true;
}]);


app.factory('AppService', function($location) {
    return {
        url : $location.protocol()+'://'+$location.host()
    };
});


app.run(function(editableOptions, editableThemes) {
    editableOptions.theme = 'bs3'; // bootstrap3 theme. Can be also 'bs2', 'default'
    //console.log(editableThemes);
});


app.directive('loadingData', function() {
    return {
        templateUrl: 'templates/overload.html'
    };
});


app.directive('showPaginationRoute', function() {
    return {
        templateUrl: '/templates/admin/super/pagination-route.html',
        controller: function($scope,$rootScope){

            $scope.goToPag = function(){
                if(/^[0-9]*$/.test($scope.gotopag)){

                    $rootScope.$broadcast('go-to-page',{nr:$scope.gotopag});

                }
            }

        }
    };
});


app.directive('showSearchCriteria', function() {
    return {
        templateUrl: '/templates/admin/super/thread/search.html'
    };
});


app.filter('getids', function() { return function(obj) {

    var nra = [];

    angular.forEach(obj, function(item, iter){
        nra.push(item.id);
    });


    return nra;

}});


app.filter('checkfalse', function() { return function(obj) {

    if (!(obj instanceof Object)) return false;

    var bool = true;

    Object.keys(obj).forEach(function(key) {

        if(!obj[key]){
            bool = false;
        }

    });

    return bool;
}});


app.filter('checkfalsearray', function() { return function(array) {

    if (!(array instanceof Array)) return false;

    var bool = true;

    for(var i=0;i<array.length;i++){
        if(!array[i]){
            bool  = false;
        }
    }

    return bool;
}});


app.filter('dateformat', function() { return function(str) {

    var d = Date.parse(str);

    //console.log(d);

    return d;
}});


app.filter('propsFilter', function() {
    return function(items, props) {
        var out = [];

        if (angular.isArray(items)) {
            var keys = Object.keys(props);

            items.forEach(function(item) {
                var itemMatches = false;

                for (var i = 0; i < keys.length; i++) {
                    var prop = keys[i];
                    var text = props[prop].toLowerCase();

                    if (item[prop].toString().toLowerCase().indexOf(text) !== -1) {
                        itemMatches = true;
                        break;
                    }

                }

                if (itemMatches) {
                    out.push(item);
                }
            });
        } else {
            // Let the output be the input untouched
            out = items;
        }

        return out;
    };
});



app.directive('deleteElement', function(AppService, $http, $q, $filter, $route) {
    return {
        templateUrl: '/templates/admin/super/thread/delete.html',
        link: function(scope, element, attributes, searchFactory){



            //console.log(attributes);
            scope.id = attributes.id;

            scope.relations = [
                {
                    method:'records',
                    name:'Nagrania'
                }

            ];

            scope.model = 'App\\Entities\\Thread';

            scope.getBeforeDeleteRaport = function(){

                var deferred = $q.defer();

                $http.post(AppService.url+'/administrator/thread/get/raport/before/delete', {relations:scope.relations, id:attributes.id})
                    .then(
                        function successCallback(response){
                            scope.raport = response.data;
                            deferred.resolve(1);
                        },
                        function errorCallback(reason){
                            deferred.reject(1);
                        }
                    );

                return deferred.promise;
            }


            scope.deleteElement = function(){

                var deferred = $q.defer();

                $http.put(AppService.url+'/administrator/delete/thread', {relations:scope.relations, id:attributes.id, model:scope.model})
                    .then(
                        function successCallback(response){
                            console.log(response.data);
                            scope.showshadow = '';
                            $route.reload();
                            deferred.resolve(1);
                        },
                        function errorCallback(reason){
                            deferred.reject(1);
                        }
                    );

                return deferred.promise;

            }




        }
    };
});


app.filter('delraportview', function() { return function(obj) {

    var view = '';

    angular.forEach(obj, function(item, key){


        if(key!='undefined' || item!='undefined' || typeof item=='object') {
            view += '<h4><span class="label label-info">' + key + ': </span>&nbsp;&nbsp;&nbsp;';
            view += item+'<h4>';
        }

    });


    return view;

}});


app.controller('GlobalController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout', 'ngDialog', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout, ngDialog) {

    $timeout(function(){

        angular.element(document.getElementById('body')).removeClass('alphaHide');
        angular.element(document.getElementById('body')).addClass('alphaShow');

    },500)

}]);


app.controller('ThreadsController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams','$rootScope', 'ngDialog', '$sce', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams,$rootScope,ngDialog,$sce) {


    $scope.initData = function(){

        $scope.limit = 10;
        $scope.tag = '';
        $scope.nthreadclass = '';

        if($routeParams.start){

            $scope.basename = [];
            if($routeParams.frase){
                $scope.frase = $routeParams.frase;
            }else{
                $scope.frase = null;
            }
            $scope.start = parseInt($routeParams.start);
            $scope.halfclick = JSON.parse($routeParams.halfclick);
            $scope.iterstart = parseInt($routeParams.iterstart);
            $scope.getElements($routeParams.iterstart);

        }else{
            $scope.basename = [];
            $scope.start = 0;
            if($routeParams.frase && $routeParams.frase!=''){
                $scope.frase = $routeParams.frase;
            }else{
                $scope.frase = null;
            }

            $scope.getElements(0);
        }

    }


    $scope.getElements = function(iterstart){

        if(iterstart) {
            iterstart = parseInt(iterstart);
        }

        $http.post(AppService.url + '/administrator/get/threads',
            {
                start:$scope.start,
                limit:$scope.limit,
                frase:$scope.frase
            })
            .then(
                function successCallback(response) {

                    $log.info(response.data);


                    $scope.count = response.data.count;

                    if(iterstart==null){
                        $scope.iterstart = $scope.start;
                    }else{
                        $scope.iterstart = iterstart;
                    }

                    $scope.elements = response.data.elements;
                    $scope.allpags = response.data.count/$scope.limit;
                    $scope.even = $scope.allpags%1;
                    $scope.pages = Math.floor($scope.allpags);
                    $scope.half = Math.floor($scope.pages/2);


                    if($scope.pages>(2*$scope.limit)){

                        $scope.first = [];
                        $scope.last = [];

                        if( iterstart>($scope.pages-5) && !$scope.halfclick) {


                            var iterlimit = 3;
                            if (iterstart >= ($scope.half - 2)) {
                                iterlimit = 5;
                            }


                            var x = 0;

                            for (var i = 0; i < iterlimit; i++) {

                                if (iterstart == i) {

                                    $scope.first[x] = {start: i, nr: (i + 1), pclass: 'active'};

                                } else {

                                    $scope.first[x] = {start: i, nr: (i + 1), pclass: ''};
                                }

                                x++;
                            }

                        }else if($scope.halfclick){


                            var x = 0;

                            for (var i = $scope.iterstart; i < ($scope.iterstart+3); i++) {

                                if (iterstart == i) {

                                    $scope.first[x] = {start: i, nr: (i + 1), pclass: 'active'};

                                } else {

                                    $scope.first[x] = {start: i, nr: (i + 1), pclass: ''};
                                }

                                x++;
                            }


                        }else {


                            var x = 0;

                            for (var i = iterstart; i < (iterstart + 3); i++) {

                                console.log(i)

                                if(iterstart==i) {

                                    $scope.first[x] = {start: i, nr: (i + 1), pclass:'active'};

                                }else{

                                    $scope.first[x] = {start: i, nr: (i + 1), pclass:''};

                                }

                                x++;
                            }



                        }


                        ///////////////////////////Last Buttons/////////////////////////////////////////////////

                        var plusiter = 1;
                        if($scope.even==0){
                            plusiter = 0;
                        }

                        if( (iterstart<$scope.pages) && !$scope.halfclick) {

                            var y = 0;

                            var backfromend = 3;

                            //jeżeli z przodu jest jest już ottanie takie jakie z przodu
                            if(iterstart<$scope.pages && iterstart>($scope.pages-5)){
                                backfromend = 4;
                            }else{
                                backfromend = 3;
                            }
                            //jeżeli z przodu jest jest już ottanie takie jakie z przodu



                            for (var i = ($scope.pages + plusiter); i > ($scope.pages - backfromend); i--) {

                                if (iterstart == (i - 1)) {

                                    $scope.last[y] = {start: (i - 1), nr: (i), pclass: 'active'};


                                } else {

                                    $scope.last[y] = {start: (i - 1), nr: (i), pclass: ''};

                                }

                                y++;

                            }


                        }else if($scope.halfclick){


                            var y = 0;


                            for (var i = ($scope.pages + plusiter); i > ($scope.pages - 3); i--) {

                                if (iterstart == (i - 1)) {

                                    $scope.last[y] = {start: (i - 1), nr: (i), pclass: 'active'};


                                } else {

                                    $scope.last[y] = {start: (i - 1), nr: (i), pclass: ''};

                                }

                                y++;

                            }



                        }else {

                            //console.log($scope.pages);

                            var y = 0;

                            var minusright = $scope.pages-$scope.iterstart;

                            for (var i = (($scope.pages + plusiter)- minusright); i > ($scope.pages - 3 - minusright); i--) {

                                if (iterstart == (i - 1)) {

                                    $scope.last[y] = {start: (i - 1), nr: (i), pclass: 'active'};


                                } else {

                                    $scope.last[y] = {start: (i - 1), nr: (i), pclass: ''};

                                }

                                y++;

                            }


                        }

                        $scope.last.reverse();

                        $scope.minimalist = true;



                    }else{

                        var reduct = 0;

                        if($scope.even==0){
                            reduct = 1;
                        }


                        $scope.pag = [];

                        for(var i=0;i<=($scope.pages-reduct);i++){

                            if(iterstart==i) {

                                $scope.pag[i] = {start: i, nr: (i + 1), pclass:'active'};

                            }else{

                                $scope.pag[i] = {start: i, nr: (i + 1), pclass:''};

                            }
                        }

                        $scope.minimalist = false;

                    }


                },
                function errorCallback(response) {

                }
            );

    }


    $rootScope.$on('go-to-page',function(event,attr){

        //console.log(attr);
        //console.log($routeParams);
        $scope.halfclick = false;
        $scope.iterstart = attr.nr-1;
        $scope.start = $scope.limit*$scope.iterstart;

        if($routeParams.frase){

            var search = {start:$scope.start,iterstart:$scope.iterstart,halfclick:$scope.halfclick};

            if($routeParams.frase){
                search.frase = $routeParams.frase;
            }

            $location.path('/').search(search);

        }else{
            $location.path('/').search({start:$scope.start,halfclick:$scope.halfclick,iterstart:$scope.iterstart});
        }

    });


    $scope.changePage = function(start){

        $scope.halfclick = false;
        $scope.start = $scope.limit*start;
        //$scope.getElements(start);
        if($scope.frase!=null || $scope.frase!=''){
            $location.path('/').search({start:$scope.start,iterstart:start,halfclick:$scope.halfclick,frase:$scope.frase});
        }else{
            $location.path('/').search({start:$scope.start,halfclick:$scope.halfclick,iterstart:start});
        }



    }


    $scope.goToHalf = function(){

        $scope.halfclick = true;
        $scope.iterstart = $scope.start;
        $scope.start = $scope.half*$scope.limit;
        //$scope.getElements($scope.start);
        if($scope.frase!=null || $scope.frase!=''){
            $location.path('/').search({start:$scope.start,iterstart:$scope.half,halfclick:$scope.halfclick,frase:$scope.frase});
        }else{
            $location.path('/').search({start:$scope.start,halfclick:$scope.half,iterstart:$scope.iterstart});
        }

    }


    $scope.changePagePrev = function(){

        $scope.halfclick = false;
        var start = $scope.iterstart-1;
        $scope.start = $scope.limit*start;
        //$scope.getElements(start);
        if($scope.frase!=null || $scope.frase!=''){
            $location.path('/').search({start:$scope.start,iterstart:start,halfclick:$scope.halfclick,frase:$scope.frase});
        }else{
            $location.path('/').search({start:$scope.start,halfclick:$scope.halfclick,iterstart:start});
        }

    }


    $scope.changePageNext = function(){

        $scope.halfclick = false;
        var start = $scope.iterstart+1;
        $scope.start = $scope.limit*start;
        //$scope.getElements(start);
        if($scope.frase!=null || $scope.frase!=''){
            $location.path('/').search({start:$scope.start,iterstart:start,halfclick:$scope.halfclick,frase:$scope.frase});
        }else{
            $location.path('/').search({start:$scope.start,halfclick:$scope.halfclick,iterstart:start});
        }

    }


    $scope.changeShowFirst = function(){

        $scope.start = 0;
        //$scope.getElements(0);
        if($scope.frase!=null || $scope.frase!=''){
            $location.path('/').search({start:$scope.start,iterstart:$scope.start,halfclick:$scope.halfclick,frase:$scope.frase});
        }else{
            $location.path('/').search({start:$scope.start,halfclick:$scope.halfclick,iterstart:$scope.start});
        }

    }

    $scope.changeShowLast = function(){

        $scope.start = $scope.pages*$scope.limit;
        //$scope.getElements($scope.pages);
        if($scope.frase!=null || $scope.frase!=''){
            $location.path('/').search({start:$scope.start,iterstart:$scope.start,halfclick:$scope.halfclick,frase:$scope.frase});
        }else{
            $location.path('/').search({start:$scope.start,halfclick:$scope.halfclick,iterstart:$scope.pages});
        }

    }


    //Get Data Logic



    $scope.searchSubmit = function(){

        $scope.start = 0;
        $location.path('/').search({frase:$scope.frase,halfclick:$scope.halfclick,iterstart:$scope.start});
        //$scope.getElements(0);


    }


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $scope.checkThread = function($data, $index){

        //console.log($data);

        var d = $q.defer();
        $http.post(AppService.url + '/administrator/check/is/thread/exist', {value: $data, basevalue:$scope.basename[$index]})
            .then(
                function successCallback(response) {
                    //console.log(response);
                    if(response.data.success) { // {status: "ok"}
                        d.resolve();
                    } else {
                        d.resolve('Temat o podanej nazwie jest już w bazie');
                    }
                },
                function errorCallback(reason){

                }
        );

        return d.promise;

    }


    $scope.changeThread = function($data, id){
        //console.log('change', $data);
        $http.put(AppService.url + '/administrator/change/thread/data', {id:id,value:$data})
            .then(
                function successCallback(response){

                    console.log(response)

                },
                function errorCallback(reason){



                }
            )
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////


    $scope.checkThread = function(){

        if($scope.thread!=''){
            $scope.nthreadclass = 'has-success';
        }else{
            $scope.nthreadclass = 'has-error';
        }

    }


    $scope.addNewTread = function(){

        if($scope.thread!='') {

            $scope.nthreadclass = '';

            $http.put(AppService.url + '/administrator/add/new/tread', {name: $scope.thread})
                .then(
                    function successCallback(response) {

                        //console.log(response.data);
                        if(response.data.success){
                            $scope.getElements();
                        }

                    },
                    function errorCallback(reason) {

                    }
                )

        }else{

            $scope.nthreadclass = 'has-error';

        }
    }


    $scope.removeTread = function(){


    }

}]);


app.controller('ThreadsLinkRecordsController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams','$rootScope', 'ngDialog', '$sce', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams,$rootScope,ngDialog,$sce) {

    $scope.initData = function(){

        //console.log($routeParams.id);
        $scope.data = {
            tid: $routeParams.id,
            thread:null,
            records:null,
            selected_rec:null,
            rids:null,
            all_r_ids: null
        };

        $scope.baserecords = null;


        $scope.getFullThreadData();
        $scope.getAllRecords();

    }


    $scope.getFullThreadData = function(){

        $http.get(AppService.url+'/administrator/get/full/thread/data/'+$scope.data.tid)
            .then(
                function successCallback(response){

                    //console.log(response.data);
                    $scope.data.thread = response.data.thread;
                    $scope.data.records = response.data.records;
                    $scope.data.selected_rec = response.data.rids.reverse();
                    $scope.data.all_r_ids = $filter('getids')($scope.data.records);
                    //console.log($scope.data.records);

                },
                function errorCallback(reason){

                }
            )

    }



    $scope.getAllRecords = function(){

        $http.get(AppService.url+'/get/all/records')
            .then(
                function successCallback(response){

                    $scope.baserecords = response.data;
                },
                function errorCallback(reason){

                }
            )

    }


    ////////////////////////////////////////////////////////////////////////////


    $scope.findAndChange = function(){
        //console.log($scope.data.selected_rec);
        //console.log($filter('getids')($scope.data.records));

        $http.put(AppService.url+'/administrator/update/linked/threads/array', {n_val:$scope.data.selected_rec, o_val:$scope.data.all_r_ids, tid:$scope.data.tid})
            .then(
                function successCallback(response){

                    //console.log(response);
                    $scope.initData();
                },
                function errorCallback(reason){

                }
            );

    }


    /////////////////////Roberto Dialog///////////////////////////////////////


    $scope.openSubWindow = function(temp,klasa){
        ngDialog.open({
            scope: $scope,
            template: temp,
            className: klasa,
            cache: true,
            overlay: false
        });
        $scope.$on('ngDialog.closed', function (e, $dialog) {

        });
    }


    $scope.removeLinkRecord = function(tosave){
        //console.log(tosave);

        $http.put(AppService.url+'/administrator/remove/linked/thread/record', tosave)
            .then(
                function successCallback(response){
                    //console.log(response);
                    $scope.initData();
                },
                function errorCallback(reason){

                }
            );

    }


    $scope.delegate = function(fn,data){
        fn(data);
    }


    $scope.toTrustedHTML = function( html ){
        return $sce.trustAsHtml( html );
    }


    $scope.removeLinkRecordTry = function (tid, rid) {

        var tosave = {tid:tid, rid:rid};

        $scope.forConfirmData = {
            fn: $scope.removeLinkRecord,
            item: tosave,
            query: "Czy chcesz odpiąć nagranie od epoki?"
        };
        $scope.openSubWindow('/templates/confirm_renderer.html','ngdialog-theme-dialog');

    }


}]);


app.config(function($routeProvider, $locationProvider) {

    $routeProvider.
    when('/', {
        templateUrl: '/templates/admin/super/thread/master.html',
        controller: 'ThreadsController'
    }).
    when('/edit/links/:id', {
        templateUrl: '/templates/admin/super/thread/edit-link-thread-record.html',
        controller: 'ThreadsLinkRecordsController'
    });
    //when('/edit/:id', {
    //    templateUrl: '/templates/admin/super/articles/edit_article.html',
    //    controller: 'EditArticleController'
    //});
    //otherwise({redirectTo: '/'});
    //
    $locationProvider.html5Mode({
        enabled: false,
        requireBase: false
    });

});