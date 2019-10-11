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


app.directive('showPagination', function() {
    return {
        templateUrl: '/templates/admin/super/pagination.html'
    };
});


app.directive('showSearchCriteria', function() {
    return {
        templateUrl: '/templates/admin/super/period/search.html'
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





app.controller('GlobalController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout', 'ngDialog', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout, ngDialog) {

    $timeout(function(){

        angular.element(document.getElementById('body')).removeClass('alphaHide');
        angular.element(document.getElementById('body')).addClass('alphaShow');

    },500)

}]);


app.controller('PeriodsController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams','$rootScope', 'ngDialog', '$sce', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams,$rootScope,ngDialog,$sce) {


    $scope.initData = function(){

        $scope.limit = 10;
        $scope.start = 0;
        $scope.frase = null;

        $scope.basename = [];

        $scope.getElements(0);

    }


    $scope.getElements = function(iterstart){

        $http.post(AppService.url + '/administrator/get/periods',
            {
                start:$scope.start,
                limit:$scope.limit,
                frase:$scope.frase
            })
            .then(
                function successCallback(response) {

                    //$log.info(response.data);


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


    $scope.changePage = function(start){

        $scope.halfclick = false;
        $scope.start = $scope.limit*start;
        $scope.getElements(start);


    }


    $scope.goToHalf = function(){

        $scope.halfclick = true;
        $scope.start = $scope.half;
        $scope.getElements($scope.start);

    }


    $scope.changePagePrev = function(){

        $scope.halfclick = false;
        var start = $scope.iterstart-1;
        $scope.start = $scope.limit*start;
        $scope.getElements(start);

    }


    $scope.changePageNext = function(){

        $scope.halfclick = false;
        var start = $scope.iterstart+1;
        $scope.start = $scope.limit*start;
        $scope.getElements(start);

    }


    $scope.changeShowFirst = function(){

        $scope.start = 0;
        $scope.getElements(0);

    }

    $scope.changeShowLast = function(){

        $scope.start = $scope.pages*$scope.limit;
        $scope.getElements($scope.pages);

    }


    //Get Data Logic



    $scope.searchSubmit = function(){

        $scope.start = 0;
        $scope.getElements(0);


    }


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $scope.checkPeriod = function($data, $index){

        //console.log($data);

        var d = $q.defer();
        $http.post(AppService.url + '/administrator/check/is/period/exist', {value: $data, basevalue:$scope.basename[$index]})
            .then(
                function successCallback(response) {
                    //console.log(response);
                    if(response.data.success) { // {status: "ok"}
                        d.resolve();
                    } else {
                        d.resolve('Epoka o podanej nazwie jest już w bazie');
                    }
                },
                function errorCallback(reason){

                }
        );

        return d.promise;

    }


    $scope.changePeriod = function($data, id){
        console.log('change', $data);
        $http.put(AppService.url + '/administrator/change/period/data', {id:id,value:$data})
            .then(
                function successCallback(response){

                    console.log(response)

                },
                function errorCallback(reason){



                }
            )
    }

}]);


app.controller('PeriodsLinkRecordsController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams','$rootScope', 'ngDialog', '$sce', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams,$rootScope,ngDialog,$sce) {

    $scope.initData = function(){

        //console.log($routeParams.id);
        $scope.data = {
            pid: $routeParams.id,
            period:null,
            records:null,
            selected_rec:null,
            rids:null,
            all_r_ids: null
        };

        $scope.baserecords = null;


        $scope.getFullPeriodData();
        $scope.getAllRecords();

    }


    $scope.getFullPeriodData = function(){

        $http.get(AppService.url+'/administrator/get/full/period/data/'+$scope.data.pid)
            .then(
                function successCallback(response){

                    //console.log(response.data);
                    $scope.data.period = response.data.period;
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

        $http.put(AppService.url+'/administrator/update/linked/periods/array', {n_val:$scope.data.selected_rec, o_val:$scope.data.all_r_ids, pid:$scope.data.pid})
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

        $http.put(AppService.url+'/administrator/remove/linked/period/record', tosave)
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


    $scope.removeLinkRecordTry = function (pid, rid) {

        var tosave = {pid:pid, rid:rid};

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
        templateUrl: '/templates/admin/super/period/master.html',
        controller: 'PeriodsController'
    }).
    when('/edit/links/:id', {
        templateUrl: '/templates/admin/super/period/edit-link-period-record.html',
        controller: 'PeriodsLinkRecordsController'
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