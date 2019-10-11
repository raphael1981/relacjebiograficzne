var app = angular.module('app',['ngSanitize', 'ngRoute', 'duScroll','ngPhotoSwipe','perfect_scrollbar','ngCookies'], function($interpolateProvider) {
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


//app.directive('articleGallery', function() {
//    return {
//        templateUrl: '/templates/front/articles/gallery.html',
//        link: function(scope, element, attributes){
//
//            //console.log(attributes);
//            scope.gallerydata = JSON.parse(attributes.articleGallery);
//
//
//        }
//    };
//});





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


app.controller('ArticleController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$rootScope','$cookies', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$rootScope,$cookies) {

    var vm = this;

    vm.title = 'ngPhotoswipe';

    vm.opts = {
        index: 0
    };


    $scope.initData = function(id){

        $scope.data = null;

        $scope.art_id = id;

        $scope.getArticleFullData()
            .then(
            function(data){
                //console.log(data);
                $timeout(function () {
                    $scope.$broadcast('rebuild:me');
                }, 0);

            },
            function(reason){
                //console.log(reason);
            }
        );

    }

    $scope.getArticleFullData = function(){

        var deferred = $q.defer();

        $http.get(AppService.url+'/get/article/data/'+$scope.art_id)
            .then(
                function successCallback(response){

                    //$log.info(response.data);
                    $scope.data = response.data;
                    $scope.refactorGalleryPicture(response.data.galleries);
                    deferred.resolve(1);

                },
                function errorCallback(response){
                    deferred.reject();
                }
            )

        return deferred.promise;

    }


    $scope.refactorGalleryPicture = function(galleries){

        $scope.slides = [];

        Object.keys(galleries).forEach(function (key) {

            $scope.slides[key] = [];



            for(var i=0;i<galleries[key].pictures.length;i++){

                $scope.slides[key][i] = {
                    src: AppService.url +'/image/'+galleries[key].pictures[i].source+'/'+galleries[key].pictures[i].disk,
                    safeSrc: AppService.url +'/image/'+galleries[key].pictures[i].source+'/'+galleries[key].pictures[i].disk,
                    thumb: AppService.url +'/image/'+galleries[key].pictures[i].source+'/'+galleries[key].pictures[i].disk+'/150',
                    caption: 'Lorem Ipsum Dolor',
                    size: galleries[key].pictures[i].size[0]+'x'+galleries[key].pictures[i].size[1],
                    type: 'image'
                }

            }


        });


        //console.log($scope.slides);

    }

    var screenSize = function (width, height) {
        var x = width ? width : $window.innerWidth;
        var y = height ? height : $window.innerHeight;

        return x + 'x' + y;
    };

    $scope.images = [];

    var sizes = [
        {w: 400, h: 300},
        {w: 480, h: 360},
        {w: 640, h: 480},
        {w: 800, h: 600},
        {w: 480, h: 360}
    ];

    for (var i = 1; i <= 5; i++) {
        $scope.images.push({
            src: 'http://lorempixel.com/' + sizes[i - 1].w + '/' + sizes[i - 1].h + '/cats',
            safeSrc: 'http://lorempixel.com/' + sizes[i - 1].w + '/' + sizes[i - 1].h + '/cats',
            thumb: 'http://lorempixel.com/' + sizes[i - 1].w + '/' + sizes[i - 1].h + '/cats',
            caption: 'Lorem Ipsum Dolor',
            size: screenSize(sizes[i - 1].w, sizes[i - 1].h),
            type: 'image'
        });
    }


    //console.log($scope.images);




}]);



app.config(function($routeProvider, $locationProvider) {

    $locationProvider.html5Mode({
        enabled: false,
        requireBase: false
    });

});