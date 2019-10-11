var app = angular.module('app',['ngSanitize', 'ngRoute', 'duScroll','perfect_scrollbar','ngCookies'], function($interpolateProvider) {
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


//app.directive('resize', function ($window) {
//    return function (scope, element) {
//        var w = angular.element($window);
//        scope.getWindowDimensions = function () {
//            return {
//                'h': w.height(),
//                'w': w.width()
//            };
//        };
//        scope.$watch(scope.getWindowDimensions, function (newValue, oldValue) {
//            scope.windowHeight = newValue.h;
//            scope.windowWidth = newValue.w;
//            //
//            //var aboutHeight = angular.element(document.getElementById('rightHome')).height();
//            //angular.element(document.getElementById('leftHome')).css({height:aboutHeight});
//            //console.log(aboutHeight);
//            //console.log(scope.windowWidth);
//
//            scope.style = function () {
//                return {
//                    'height': (newValue.h - 100) + 'px',
//                    'width': (newValue.w - 100) + 'px'
//                };
//            };
//
//        }, true);
//
//        w.bind('resize', function () {
//            scope.$apply();
//        });
//    }
//})



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


app.controller('IntervieweesController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$rootScope', '$document', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$rootScope,$document) {

    $scope.initData = function(id){

        $scope.data = {};
        $scope.current_index = null;

        $scope.getAlphabet()
            .then(
            function(data){
                //console.log(data);
                if(data==1){
                    //$scope.getFirstIndex();
                    $scope.getAllInterviewees();
                }
            },
            function(reason){
                console.log(reason);
            }
        );

    }


    $scope.getAlphabet = function(){

        var deferred = $q.defer();

        $http.get(AppService.url+'/json/alphabet.json')
            .then(

                function successCallback(response){

                    //$log.info(response);
                    $scope.data.alphabet_logic = response.data;
                    $scope.data.alphabet = Object.keys(response.data);
                    $scope.count_letters = $scope.data.alphabet.length;
                    //$scope.percent_width = 100/$scope.count_letters;
                    $scope.current_index = $scope.data.alphabet[0];

                    deferred.resolve(1);

                },
                function errorCallback(response){
                    deferred.reject();
                }

            );

        return deferred.promise;
    }


    $scope.getAllInterviewees = function(){

        $http.get(AppService.url+'/get/interviewees/all')
            .then(
                function successCallback(response){

                    $log.info(response);
                    $scope.data.interviewees = response.data;

                },
                function errorCallback(response){

                }
            )


    }


    $scope.getFirstIndex = function(){

        $http.post(AppService.url+'/get/interviewees/index/data', {index_data:$scope.data.alphabet_logic[$scope.current_index]})
            .then(
                function successCallback(response){

                    //$log.info(response);
                    $scope.data.interviewees = response.data;

                },
                function errorCallback(response){

                }
            )


    }


    $scope.changeIndex = function(letter, $event){

        angular.element(document.getElementsByClassName('a-letter')).find('.point').text('');
        angular.element($event.target).find('.point').append('.');

        $http.post(AppService.url+'/get/interviewees/index/data', {index_data:$scope.data.alphabet_logic[letter]})
            .then(
                function successCallback(response){

                    $log.info(response);
                    $scope.current_index = letter;
                    $scope.data.interviewees = response.data;

                },
                function errorCallback(response){

                }
            )


    }


    $scope.goToIntervieweeIntent = function(intent_record){

        console.log(intent_record);

    }





}]);

app.controller('IntervieweesAllController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$rootScope', '$document', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$rootScope,$document) {

    $scope.initData = function(id){

        $scope.data = {};
        $scope.current_index = null;

        $scope.getAlphabet()
            .then(
                function(data){
                    //console.log(data);
                    if(data==1){
                        //$scope.getFirstIndex();
                        $scope.getAllInterviewees();
                    }
                },
                function(reason){
                    console.log(reason);
                }
            );

    }


    $scope.getAlphabet = function(){

        var deferred = $q.defer();

        $http.get(AppService.url+'/json/alphabet.json')
            .then(

                function successCallback(response){

                    //$log.info(response);
                    $scope.data.alphabet_logic = response.data;
                    $scope.data.alphabet = Object.keys(response.data);
                    $scope.count_letters = $scope.data.alphabet.length;
                    //$scope.percent_width = 100/$scope.count_letters;
                    $scope.current_index = $scope.data.alphabet[0];

                    deferred.resolve(1);

                },
                function errorCallback(response){
                    deferred.reject();
                }

            );

        return deferred.promise;
    }


    $scope.getAllInterviewees = function(){

        $http.get(AppService.url+'/get/interviewees/all')
            .then(
                function successCallback(response){

                    $log.info(response);
                    $scope.data.interviewees = response.data;

                },
                function errorCallback(response){

                }
            )


    }


}]);


app.filter('find-current-letter', function(){
    return function (letter, alphabet){

        var key = letter.toUpperCase();
        var el;

        Object.keys(alphabet).forEach(function(k){
            if(k==key){
                el = alphabet[k];
            }
        });

        return el;

    }
})

app.controller('IntervieweesLetterController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$rootScope', '$document', '$route', '$routeParams', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$rootScope,$document, $route, $routeParams) {


    $scope.initData = function(){

        $scope.letter = $routeParams.letter;

        $scope.data = {};
        $scope.current_index = null;

        $scope.getAlphabet()
            .then(
                function(data){

                    $scope.getIntervieweesByLetter(data);

                },
                function(reason){
                    console.log(reason);
                }
            );

    }



    $scope.getAlphabet = function(){

        var deferred = $q.defer();

        $http.get(AppService.url+'/json/alphabet.json')
            .then(

                function successCallback(response){

                    $log.info(response.data);
                    $scope.data.alphabet_logic = response.data;
                    $scope.data.alphabet = Object.keys(response.data);
                    $scope.count_letters = $scope.data.alphabet.length;
                    //$scope.percent_width = 100/$scope.count_letters;
                    var letter_array = $filter('find-current-letter')($scope.letter,$scope.data.alphabet_logic);
                    $scope.current_index = $scope.data.alphabet[0];

                    deferred.resolve(letter_array);

                },
                function errorCallback(response){
                    deferred.reject();
                }

            );

        return deferred.promise;
    }


    $scope.getIntervieweesByLetter = function(letters){

        $http.post(AppService.url+'/get/interviewees/index/data', {index_data:letters})
            .then(
                function successCallback(response){

                    $log.info(response);
                    $scope.data.interviewees = response.data;

                },
                function errorCallback(response){

                }
            )

    }


}]);

app.config(function($routeProvider, $locationProvider) {

    $routeProvider.
    when('/', {
        templateUrl: '/templates/front/interviewees/all.html',
        controller: 'IntervieweesAllController'
    }).when('/:letter', {
        templateUrl: '/templates/front/interviewees/by-letter.html',
        controller: 'IntervieweesLetterController'
    })

    $locationProvider.html5Mode({
        enabled: false,
        requireBase: false
    });

});