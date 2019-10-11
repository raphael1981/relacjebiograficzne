var app = angular.module('app',['ngSanitize', 'ngRoute', 'duScroll', 'ngAnimate','perfect_scrollbar','ngCookies'], function($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});


app.directive('resize', function ($window,$timeout) {
    return function (scope, element, attr) {

        var w = angular.element($window);
        scope.$watch(function () {
            return {
                'h': w.height(),
                'w': w.width()
            };
        }, function (newValue, oldValue) {

            scope.windowHeight = newValue.h;
            scope.windowWidth = newValue.w;

            $timeout(function(){

                //if(angular.element(window).width()>768) {
                    var circle_height = angular.element(document.getElementById('about-home-content')).height();
                    angular.element(document.getElementById('circleID')).find('img').height(circle_height);
                //}else{
                //    angular.element(document.getElementById('circleID')).find('img').addClass('img-responsive');
                //}

            },500);



            if(scope.windowWidth>1199) {
                var marginRight = angular.element(document.getElementsByClassName('more-width')).css('margin-right');
                angular.element(document.getElementsByClassName('less-width')).css({paddingTop: marginRight});
            }else{
                angular.element(document.getElementsByClassName('less-width')).css({paddingTop: 0});
            }

        }, true);

        w.bind('resize', function () {
            scope.$apply();
        });
    }
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


app.filter('dateformat', function() { return function(str) {

    var d = Date.parse(str);

    //console.log(d);

    return d;
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

        //if(angular.element(window).width()>768) {
            angular.element(document.getElementById('body')).removeClass('alphaHide');
            angular.element(document.getElementById('body')).addClass('alphaShow');
        //}else{
        //    angular.element(document.getElementById('circleID')).find('img').addClass('img-responsive');
        //}

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



app.controller('HomeController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout', '$interval', '$document','$rootScope', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout, $interval, $document,$rootScope) {



    $scope.initData = function(){

        $scope.start = 0;
        $scope.limit = 4;
        $scope.is_more_then_three = true;
        $scope.defaultimage = 'image/default.jpg/pictures';
        //var aboutHeight = angular.element(document.getElementById('rightHome')).height();
        //angular.element(document.getElementById('leftHome')).height(aboutHeight)

        $scope.getInitArticles().then(
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


    $scope.getInitArticles = function(){

        var deferred = $q.defer();

        $http.get(AppService.url+'/get/articles/1/'+$scope.start+'/'+$scope.limit)
            .then(
                function successCallback(response){

                    //$log.info(response.data);

                    if(response.data.length>=4){
                        $scope.is_more_then_three = true;
                        $scope.articles = response.data;
                        $scope.first_article = $scope.articles[0];
                        $scope.first_three_article = [
                            $scope.articles[1],
                            $scope.articles[2],
                            $scope.articles[3]
                        ];
                        $scope.articles = [];
                    }else{
                        $scope.is_more_then_three = false;
                        $scope.articles = response.data;
                        $scope.first_article = $scope.articles[0];
                        $scope.articles.shift();
                        deferred.resolve(1);
                    }

                    //$log.info($scope.first_three_article);

                },
                function errorCallback(response){
                    deferred.reject();
                }
            )

        return deferred.promise;

    }


    $scope.pushMoreArticles = function(){

        if($scope.start==0){
            $scope.start = 4;
            $scope.limit=2;
        }else{
            $scope.start+=2;
        }


        var deferred = $q.defer();

        $http.get(AppService.url+'/get/articles/1/'+$scope.start+'/'+$scope.limit)
            .then(
                function successCallback(response){

                    //$log.info(response);
                    response.data.forEach(function(data){
                        $scope.articles.push(data);
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

    //$scope.checkIsScrollOnDown = function(){
    //
    //
    //    if (angular.element(window).scrollTop() == angular.element(document).height() - angular.element(window).height()) {
    //
    //        $scope.pushMoreArticles().then(
    //
    //            function(data){
    //                //console.log(data);
    //                if(data==1){
    //                    $timeout(function () {
    //                        $scope.$broadcast('rebuild:me');
    //                        $timeout($scope.checkIsScrollOnDown(),2000);
    //                    }, 0);
    //                }
    //
    //            },
    //            function(reason){
    //                console.log(reason);
    //            }
    //        );
    //
    //    }
    //
    //}


    //Jeżeli scroll leży za długo na samym dole


    angular.element(document.getElementById('scrollID')).scroll(function(){

        var cont = angular.element(document.getElementById('scrollID'));
        var yscroll = angular.element(document.getElementsByClassName('ps-scrollbar-y'));

        //console.log(cont.height());
        //console.log(yscroll.position().top+yscroll.height());

        if((cont.height()-20)<=(yscroll.position().top+yscroll.height())){

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


    $document.on('scroll', function() {

        //console.log(angular.element(window).scrollTop());

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

app.filter('secondsToTime', function() {

    function padTime(t) {
        return t < 10 ? "0"+t : t;
    }

    return function(_seconds) {
        if (typeof _seconds !== "number" || _seconds < 0)
            return "00:00:00";

        var hours = Math.floor(_seconds / 3600),
            minutes = Math.floor((_seconds % 3600) / 60),
            seconds = Math.floor(_seconds % 60);

        return padTime(hours) + ":" + padTime(minutes) + ":" + padTime(seconds);
    };
});

app.controller('LastRecordsController', ['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout) {

    $scope.initData = function () {

        $scope.start = 0;
        $scope.limit = 10;
        $scope.records = [];

        $scope.getLastRecords().then(
            function (data) {
                //console.log(data);
                if (data == 1) {
                    $timeout(function () {
                        $scope.$broadcast('rebuild:me');
                    }, 0);
                }
            },
            function (reason) {
                console.log(reason);
            }
        );


    }


    $scope.getLastRecords = function () {

        var deferred = $q.defer();

        $http.get(AppService.url + '/get/last/records/' + $scope.start + '/' + $scope.limit)
            .then(
                function successCallback(response) {

                    //$log.info(response.data);
                    $scope.records = response.data;
                    deferred.resolve(1);

                },
                function errorCallback(response) {
                    deferred.reject();
                }
            )

        return deferred.promise;

    }


    $scope.sendPostIntentToLogin = function (params) {


        var fdata = [
            {name: 'id', value: params.id},
            {name: 'alias', value: params.alias},
            {name: 'type', value: params.type}
        ];

        var form = document.createElement('form');
        form.action = '/autoryzacja';
        form.method = 'POST';

        var tinput = document.createElement('input');
        tinput.type = 'hidden';
        tinput.name = '_token';
        tinput.value = $('meta[name="csrf-token"]').attr('content');
        form.appendChild(tinput);


        var inputtype = document.createElement('input');
        inputtype.type = 'hidden';
        inputtype.name = 'linktype';
        inputtype.value = 'nofrase_record';
        form.appendChild(inputtype);


        for (var i in fdata) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = fdata[i].name;
            input.value = fdata[i].value;
            form.appendChild(input);
        }


        form.submit();

    }

    $scope.initTest = function()
    {
        $http.put(AppService.url + '/test')
            .then(
                function successCallback(response) {
                    console.log('dobre dane', response.data);
            },
                function errorCallback(response) {
                    console.log('error', response.data)
            }
        );
    }

}]);





app.controller('LoginCloudFormController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', function($scope, $http, $log, $q, $location,AppService, $window, $filter) {

    $scope.emailregex = /^[0-9a-z_.-]+@[0-9a-z.-]+\.[a-z]{2,3}$/i;
    //start from Uppercase or lower case letter maybe number at last or bettween - min 3 characters
    $scope.passregex = /^(?=^.{5,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/i

    $scope.form = {

        data:{
            email:'',
            password:'',
            remeber: false
        },
        valid:{
            email:false,
            password:false
        },
        classes:{
            email:'',
            password:''
        }

    };


    $scope.checkEmail = function(){

        if($scope.emailregex.test($scope.form.data.email)){
            $scope.form.valid.email = true;
        }else{
            $scope.form.valid.email = false;
        }

    }

    $scope.checkPassword = function(){

        if($scope.passregex.test($scope.form.data.password)){
            $scope.form.valid.password = true;
        }else{
            $scope.form.valid.password = false;
        }

    }

    $scope.checkAuth = function(){

        var deferred = $q.defer();

        $scope.checkEmail();
        $scope.checkPassword();


        if($scope.form.valid.email && $scope.form.valid.password) {

            $scope.loading = '';

            $http.post(AppService.url + '/customer/login', $scope.form.data)
                .success(function (data) {
                    $log.info(data);

                    if(data==1){
                        $scope.login = true;
                        deferred.resolve(1);
                    }else{
                        $scope.login = false;
                        $scope.badlogin = '';
                        deferred.reject(0);
                    }


                })
                .error(function (error) {
                    //$log.info(error);
                    deferred.resolve(error);
                });

            return deferred.promise

        }else{

            deferred.reject(0);
            return deferred.promise
        }

    }


    $scope.loginSubmit = function(){

        var authcheck = $scope.checkAuth();


        authcheck.then(
            function(data){

                console.log(data);
                console.log($scope.intent_link);

                $scope.loading = 'hidden';

                if(data==1){


                    $window.location.href = '/';


                }else{

                }
            },
            function(reason){
                console.log(reason);
            }
        );


    }





}]);





app.config(function($routeProvider, $locationProvider) {

    $locationProvider.html5Mode({
        enabled: false,
        requireBase: false
    });

});