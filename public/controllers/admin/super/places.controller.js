var app = angular.module('app',['ngSanitize', 'ngRoute', 'ui.tinymce', 'xeditable', 'ui.bootstrap.datetimepicker','ngDialog','wysiwyg.module','ngFileUpload'], function($interpolateProvider) {
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


app.run(function(editableOptions) {
    editableOptions.theme = 'bs3'; // bootstrap3 theme. Can be also 'bs2', 'default'
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
        templateUrl: '/templates/admin/super/places/search.html'
    };
});


app.directive('deleteElement', function(AppService, $http, $q, $filter, $route) {
    return {
        templateUrl: '/templates/admin/super/places/delete.html',
        link: function(scope, element, attributes, searchFactory){



            //console.log(attributes);
            scope.id = attributes.id;

            scope.relations = [
                {
                    method:'fragments',
                    name:'Fragmenty',
                    extra: {method:'record',field:'title',key:'title'}
                }

            ];

            scope.model = 'App\\Entities\\Interval';

            scope.getBeforeDeleteRaport = function(){

                var deferred = $q.defer();

                $http.post(AppService.url+'/administrator/place/get/raport/before/delete', {relations:scope.relations, id:attributes.id})
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

                $http.put(AppService.url+'/administrator/delete/place', {relations:scope.relations, id:attributes.id, model:scope.model})
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

//app.filter('splitdate', function() { return function(obj) {
//
//    var dates = obj;
//
//    if(obj.start!=null){
//        var dstart = obj.start;
//        console.log(dstart);
//        dates.start = dstart;
//    }
//
//    if(obj.end!=null){
//
//        var dend = obj.end;
//        console.log(dend);
//        dates.end = dend;
//
//    }
//
//    return dates;
//}});


app.controller('GlobalController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout', 'ngDialog', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout, ngDialog) {

    $timeout(function(){

        angular.element(document.getElementById('body')).removeClass('alphaHide');
        angular.element(document.getElementById('body')).addClass('alphaShow');

    },500)

}]);


app.controller('PlacesController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams','$rootScope', 'ngDialog', '$sce', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams,$rootScope,ngDialog,$sce) {


    $scope.initData = function(){

        $scope.limit = 10;


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

        }else {
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

        $http.post(AppService.url + '/administrator/get/places',
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


    /*

       Change name of place

     */

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $scope.checkPlace = function($data, $index){

        console.log($data);

        var d = $q.defer();
        $http.post(AppService.url + '/administrator/check/is/place/exist', {value: $data, basevalue:$scope.basename[$index]})
            .then(
                function successCallback(response) {
                    //console.log(response);
                    if(response.data.success) { // {status: "ok"}
                        d.resolve();
                    } else {
                        d.resolve('Miejsce o podanej nazwie jest już w bazie');
                    }
                },
                function errorCallback(reason){

                }
            );

        return d.promise;

    }


    $scope.changePlace = function($data, id){
        console.log('change', $data);
        $http.put(AppService.url + '/administrator/change/place/data', {id:id,value:$data})
            .then(
                function successCallback(response){

                    console.log(response)

                },
                function errorCallback(reason){



                }
            )
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////






}]);




app.controller('NewPlaceController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams', '$rootScope', 'ngDialog', '$sce', 'Upload', '$interval', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams,$rootScope, ngDialog, $sce, Upload,$interval) {


    $scope.initData = function(){

        $scope.place = {
            name:'',
            lat:null,
            lng:null
        }

        $scope.valid = {
            name:false
        }

        $scope.classes = {
            name:''
        }


    }


    //$scope.$watch('$parent.windowWidth', function(oldV, newV){
    //    //console.log(oldV);
    //    //console.log(newV);
    //    console.log(Math.floor(angular.element(document.getElementById('canvasColId')).width()));
    //    $scope.canvas_width = Math.floor(angular.element(document.getElementById('canvasColId')).width());
    //
    //});



    /////////////////////////////////////////////////////////////////////////////

    $scope.checkName = function(){

        if($scope.place.name.length>1){

            $scope.valid.name = true;
            $scope.classes.name = 'has-success';

        }else{

            $scope.valid.name = false;
            $scope.classes.name = 'has-error';
        }

    }



    $scope.createNewPlace = function(){

        $scope.checkName();

        //console.log($scope.inter);

        if($filter('checkfalse')($scope.valid)){

            $scope.placesaving = '';

            $http.put(AppService.url+'/administrator/create/new/place', $scope.place)
                .then(
                    function successCallback(response){


                        $log.info(response.data);
                        if(response.data.success){
                            $scope.placesaving = 'hidden';
                            $scope.placesaved = '';
                            $scope.initData();
                        }


                    },
                    function errorCallback(reason){

                    }
                )

        }

    }


}]);


app.config(function($routeProvider, $locationProvider) {

    $routeProvider.
    when('/', {
        templateUrl: '/templates/admin/super/places/master.html',
        controller: 'PlacesController'
    }).
    when('/add', {
        templateUrl: '/templates/admin/super/places/new-place.html',
        controller: 'NewPlaceController'
    }).
    when('/edit/:id', {
        templateUrl: '/templates/admin/super/places/edit-place.html',
        controller: 'EditPlaceController'
    });
    //otherwise({redirectTo: '/'});
    //
    $locationProvider.html5Mode({
        enabled: false,
        requireBase: false
    });

});