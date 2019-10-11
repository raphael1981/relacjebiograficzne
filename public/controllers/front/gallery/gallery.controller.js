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


app.controller('GalleryController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout', '$interval', '$document','$rootScope', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout, $interval, $document,$rootScope) {



    $scope.initData = function(){

        $scope.start = 0;
        $scope.limit = 16;

        $log.info($scope.mode);

        switch ($scope.mode){

            case 'datebase':

                $scope.getInitGalleryDatebase().then(
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

                break;

            case 'iptcauthor':

                break;

            case 'iptccategory':


                $scope.getInitGalleryIptcCategory().then(
                    function(data){
                        //console.log(data);
                        //if(data==1){
                        //    $timeout(function () {
                        //        $scope.$broadcast('rebuild:me');
                        //    }, 0);
                        //}
                    },
                    function(reason){
                        console.log(reason);
                    }
                );


                break;

        }



    }


    $scope.getInitGalleryDatebase = function(){


        var deferred = $q.defer();


        $http.get(AppService.url+'/get/gallery/'+$scope.gid+'/'+$scope.start+'/'+$scope.limit)
        //$http.get(AppService.url+'/get/full/gallery/'+$scope.gid)
            .then(
                function successCallback(response){

                    $log.info(response.data);
                    $scope.refactorDataToGallery(response.data);
                    //$scope.gallery = response.data;

                    deferred.resolve(1);



                },
                function errorCallback(response){
                    deferred.reject();
                }
            )

        return deferred.promise;

    }


    $scope.refactorDataToGallery = function(data){

        //console.log(data);

        $scope.gallery = [];

        for(var i=0;i<data.length;i++){

            $scope.gallery[i] = {
                src: AppService.url +'/image/'+data[i].source+'/'+data[i].disk,
                safeSrc: AppService.url +'/image/'+data[i].source+'/'+data[i].disk,
                thumb: AppService.url +'/image/'+data[i].source+'/'+data[i].disk+'/550',
                caption: data[i].caption,
                size: data[i].size[0]+'x'+data[i].size[1],
                type: 'image',
                orientation: data[i].orientation
            }

        }

        //console.log($scope.gallery);

    }


    $scope.refactorDataToGalleryReturn = function(data){


        for(var i=0;i<data.length;i++){

            $scope.gallery.push({
                src: AppService.url +'/image/'+data[i].source+'/'+data[i].disk,
                safeSrc: AppService.url +'/image/'+data[i].source+'/'+data[i].disk,
                thumb: AppService.url +'/image/'+data[i].source+'/'+data[i].disk+'/550',
                caption: '',
                size: data[i].size[0]+'x'+data[i].size[1],
                type: 'image'
            });

        }


    }




    $scope.getInitGalleryIptcCategory = function(){


        var deferred = $q.defer();


        $http.get(AppService.url+'/get/iptc/gallery/'+$scope.gid+'/'+$scope.mode)
            //$http.get(AppService.url+'/get/full/gallery/'+$scope.gid)
            .then(
                function successCallback(response){

                    //$log.info('gallery',response.data);
                    //$scope.refactorDataToGalleryIptc(response.data);
                    //$scope.gallery = response.data;
                    $scope.gallery = response.data._source.images;
                    $log.info('gallery',$scope.gallery);

                    deferred.resolve(1);



                },
                function errorCallback(response){
                    deferred.reject();
                }
            )

        return deferred.promise;

    }


    $scope.refactorDataToGalleryIptc = function(data){

        //console.log(data);

        $scope.gallery = [];

        for(var i=0;i<data.length;i++){

            $scope.gallery[i] = {
                src: AppService.url +'/image/'+data[i].source+'/'+data[i].gallery.disk,
                safeSrc: AppService.url +'/image/'+data[i].source+'/'+data[i].gallery.disk,
                thumb: AppService.url +'/image/'+data[i].source+'/'+data[i].gallery.disk+'/550',
                caption: data[i].caption,
                size: data[i].size[0]+'x'+data[i].size[1],
                type: 'image',
                orientation: data[i].orientation
            }

        }

        console.log($scope.gallery);

    }





    ///////////////////////////////////////////////////////////////////////////////////////////////////




    $scope.pushMoreArticles = function(){

        if($scope.start==0){
            $scope.start = 16;
            $scope.limit=8;
        }else{
            $scope.start+=8;
        }


        var deferred = $q.defer();

        $http.get(AppService.url+'/get/gallery/'+$scope.gid+'/'+$scope.start+'/'+$scope.limit)
            .then(
                function successCallback(response){

                    //$log.info(response);
                    $scope.refactorDataToGalleryReturn(response.data);
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


        if (angular.element(window).scrollTop() == angular.element(document).height() - angular.element(window).height()) {

            $scope.pushMoreArticles().then(

                function(data){
                    //console.log(data);
                    if(data==1){
                        $timeout(function () {
                            $scope.$broadcast('rebuild:me');
                            $timeout($scope.checkIsScrollOnDown(),2000);
                        }, 0);
                    }

                },
                function(reason){
                    console.log(reason);
                }
            );

        }

    }


    //Jeżeli scroll leży za długo na samym dole


    $document.on('scroll', function() {


        if ( angular.element(window).scrollTop() >= (angular.element(document).height() - angular.element(window).height()) ) {

            $scope.pushMoreArticles().then(

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


    });




}]);