var app = angular.module('app',['ngSanitize', 'ngRoute', 'duScroll', 'ngAnimate','ngPhotoSwipe','perfect_scrollbar','ngCookies'], function($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});




app.factory('AppService', function($location) {
    return {
        url : $location.protocol()+'://'+$location.host()
    };
});



app.directive('loadingData', function() {
    return {
        templateUrl: 'templates/overload.html'
    };
});





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


app.filter('capitalize', function() {
    return function(input) {
        return (!!input) ? input.charAt(0).toUpperCase() + input.substr(1).toLowerCase() : '';
    }
});


app.controller('GlobalController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$rootScope','$cookies', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$rootScope,$cookies) {

    $scope.initWcag = function(){

        var body = angular.element(document.getElementById('body'));


        if($cookies.get('wcagstyle')){

            var wcag = JSON.parse($cookies.get('wcagstyle'));

            if(wcag.contrast){
                body.addClass('contrast-plus');
            }else{
                body.removeClass('contrast-plus');
            }

            if(wcag.font){
                body.addClass('font-plus');
            }else{
                body.removeClass('font-plus');
            }
        }

    }

    $timeout(function(){

        angular.element(document.getElementById('body')).removeClass('alphaHide');
        angular.element(document.getElementById('body')).addClass('alphaShow');

    },500);

    $scope.changeWcagStyle = function(type){

        console.log($cookies.get('wcagstyle'));
        if( $cookies.get('wcagstyle') ){
            var wcag = $cookies.get('wcagstyle');
            wcag = JSON.parse(wcag);
        }else{
            var wcag = {
                contrast:false,
                font:false
            }
        }

        var body = angular.element(document.getElementById('body'));

        switch (type){

            case 'cplus':

                body.addClass('contrast-plus');
                wcag.contrast = true;
                $cookies.put('wcagstyle', JSON.stringify(wcag));

                break;

            case 'cminus':

                body.removeClass('contrast-plus');
                wcag.contrast = false;
                $cookies.put('wcagstyle', JSON.stringify(wcag));

                break;

            case 'fplus':

                body.addClass('font-plus');
                wcag.font = true;
                $cookies.put('wcagstyle', JSON.stringify(wcag));

                break;

            case 'fminus':

                body.removeClass('font-plus');
                wcag.font = false;
                $cookies.put('wcagstyle', JSON.stringify(wcag));

                break;

        }

    }

    $http.get(AppService.url+'/get/ajax/auth')
        .then(

            function successCallback(response) {

                //$log.info(response);
                $rootScope.auth = response.data.boolean;

            },
            function errorCallback(response) {

            }
        );



}]);


app.controller('GalleriesController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout', '$interval', '$document','$rootScope', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout, $interval, $document,$rootScope) {



    $scope.initData = function(){

        $scope.start = 0;
        $scope.limit = 16;


        $scope.getInitGalleries().then(
            function(data){
                //console.log(data);
                if(data==1){
                    $timeout(function () {
                        $scope.$broadcast('rebuild:me');
                    }, 0);
                }
            },
            function(reason){
                console.log(reason);
            }
        );



    }


    $scope.getInitGalleries = function(){

        var deferred = $q.defer();

        $http.get(AppService.url+'/get/galleries/'+$scope.start+'/'+$scope.limit)
            .then(
                function successCallback(response){

                    //$log.info(response.data);

                    $scope.galleries = response.data;

                    deferred.resolve(1);

                    //if(response.data.length>=4){
                    //    $scope.is_more_then_three = true;
                    //    $scope.articles = response.data;
                    //    $scope.first_article = $scope.articles[0];
                    //    $scope.first_three_article = [
                    //        $scope.articles[1],
                    //        $scope.articles[2],
                    //        $scope.articles[3]
                    //    ];
                    //    $scope.articles = [];
                    //}else{
                    //    $scope.is_more_then_three = false;
                    //    $scope.articles = response.data;
                    //    $scope.first_article = $scope.articles[0];
                    //    $scope.articles.shift();
                    //    deferred.resolve(1);
                    //}


                },
                function errorCallback(response){
                    deferred.reject();
                }
            )

        return deferred.promise;

    }


    $scope.pushMoreArticles = function(){

        if($scope.start==0){
            $scope.start = 16;
            $scope.limit=8;
        }else{
            $scope.start+=8;
        }


        var deferred = $q.defer();

        $http.get(AppService.url+'/get/galleries/'+$scope.start+'/'+$scope.limit)
            .then(
                function successCallback(response){

                    $log.info(response);
                    response.data.forEach(function(data){
                        $scope.galleries.push(data);
                    });
                    deferred.resolve(1);


                },
                function errorCallback(response){
                    deferred.reject();
                }
            )

        return deferred.promise;

    }


    //Jeżeli scroll leży za długo na samym dole

    $scope.checkIsScrollOnDown = function(){


        //if (angular.element(window).scrollTop() == angular.element(document).height() - angular.element(window).height()) {
        //
        //    $scope.pushMoreArticles().then(
        //
        //        function(data){
        //            //console.log(data);
        //            if(data==1){
        //                $timeout(function () {
        //                    $scope.$broadcast('rebuild:me');
        //                    $timeout($scope.checkIsScrollOnDown(),2000);
        //                }, 0);
        //            }
        //
        //        },
        //        function(reason){
        //            console.log(reason);
        //        }
        //    );
        //
        //}

    }


    //Jeżeli scroll leży za długo na samym dole


    $document.on('scroll', function() {


        //if ( angular.element(window).scrollTop() >= (angular.element(document).height() - angular.element(window).height()) ) {
        //
        //    $scope.pushMoreArticles().then(
        //
        //        function(data){
        //            //console.log(data);
        //            if(data==1){
        //                $timeout(function () {
        //                    $scope.$broadcast('rebuild:me');
        //                }, 0);
        //            }
        //
        //        },
        //        function(reason){
        //            console.log(reason);
        //        }
        //    );
        //
        //
        //}


    });


    $scope.onSubmit = function(){

        $location.path('/szukaj').search('q='+$scope.data.search.frase);
        $scope.query = $scope.data.search.frase;

    }




}]);



app.filter('contentTrim', function() {

    return function(content,words) {

        var trim_content = '';

        var spl = content.split(' ');
        for(var i=0;i<words;i++){

            if(i!=0){
                trim_content += ' '+spl[i];
            }else{
                trim_content += spl[i];
            }

        }

        return trim_content+' ...';
    }

});


app.controller('ImagesSearchController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout', '$interval', '$document','$rootScope','$routeParams', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout, $interval, $document,$rootScope,$routeParams) {

    $scope.initData = function(){

        $scope.data = {
            search:{
                frase:''
            },
            images:null
        }


        if($routeParams.q){
            $scope.query = $routeParams.q;
            $scope.data.search.frase = $routeParams.q;
        }else{
            $scope.query = '';
            $scope.data.search.frase = '';
        }


    }


    $scope.$watch('query', function(nVal, oVal){

        if(nVal!='') {

            if($routeParams.q){
                $scope.query = $routeParams.q;
                $scope.data.search.frase = $routeParams.q;
            }else{
                $scope.query = '';
                $scope.data.search.frase = '';
            }

            $http.post(AppService.url + '/get/search/elastic/images/by/criteria', {frase: $routeParams.q})
                .then(
                    function successCallback(response) {
                        console.log(response);
                        $scope.data.images = response.data;
                    },
                    function errorCallback(reason) {

                    }
                );
        }

    });


    $scope.onSubmit = function(){

        $location.path('/szukaj').search('q='+$scope.data.search.frase);
        $scope.query = $scope.data.search.frase;

    }




}]);



app.config(function($routeProvider, $locationProvider) {

    $routeProvider.
    when('/', {
        templateUrl: '/templates/front/elasticsearch/images/galleries.html',
        controller: 'GalleriesController'
    }).when('/szukaj', {
        templateUrl: '/templates/front/elasticsearch/images/search.html',
        controller: 'ImagesSearchController'
    });

    $locationProvider.html5Mode({
        enabled: false,
        requireBase: false
    });

});