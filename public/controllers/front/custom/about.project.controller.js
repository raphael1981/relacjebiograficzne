var app = angular.module('app',['ngSanitize', 'ngRoute', 'duScroll', 'ngPhotoSwipe','perfect_scrollbar','ngCookies'], function($interpolateProvider) {
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


app.controller('GlobalController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$rootScope', 'AppService','$cookies', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$rootScope,AppService,$cookies) {

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




app.controller('AboutProjectController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$rootScope', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$rootScope) {

    $scope.initData = function(){

        $http.get(AppService.url+'/get/about-project/images')
            .then(
                function successCallback(response){

                    console.log(response.data);
                    //$scope.images = $filter('orderBy')(response.data, 'img', false);
                    $scope.images = response.data;
                    console.log($scope.images);
                },
                function errorCallback(response){

                }
            )

    }


    $scope.openAboutGallery = function(index){

        var pswpElement = document.querySelectorAll('.pswp')[0];


        var options = {
            // history & focus options are disabled on CodePen
            history: false,
            focus: false,
            captionEl: true,
            showAnimationDuration: 0,
            hideAnimationDuration: 0,
            index: index

        };

        var items = [];

        angular.forEach($scope.images, function(value, key){

            items.push({
                src: value.img,
                title:value.caption,
                w:value.size[0],
                h:value.size[1]
            });

        });


        gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
        gallery.init();

    }

}]);