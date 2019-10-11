'use strict';

var app = angular.module('app',['ngSanitize','ngRoute','ngDialog','perfect_scrollbar','ngCookies'], function($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});


app.factory('AppService', function($location) {
    return {
        url : $location.protocol()+'://'+$location.host()
    };
});


app.factory('mySharedService', function($rootScope) {
    var sharedService = {};
    sharedService.data = {};

    sharedService.prepForBroadcast = function(data) {
        this.data = data;
        this.broadcastItem();
    };

    sharedService.broadcastItem = function() {
        $rootScope.$broadcast('handleBroadcast');
    };

    return sharedService;
});


app.controller('GlobalController',['$scope',
    '$http',
    '$log',
    '$q',
    '$location',
    'AppService',
    '$window',
    '$filter',
    '$timeout',
    'mySharedService',
    '$rootScope',
    '$cookies',
    function($scope, $http,
             $log, $q,
             $location,AppService,
             $window, $filter,
             $timeout,mySharedService,
             $rootScope,$cookies) {

        $scope.currTime = 0;

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

        $timeout(function(){

            angular.element(document.getElementById('body')).removeClass('alphaHide');
            angular.element(document.getElementById('body')).addClass('alphaShow');

        },500)

    }]);



app.controller('RecordNoAuthController',['$scope',
    '$sce',
    'mySharedService',
    '$rootScope',
    '$timeout',
    'ngDialog',
    function($scope,$sce, mySharedService,$rootScope,$timeout,ngDialog){


    $scope.goToLoginWithIntent = function(data, phrase){


        //var fdata = [
        //    {name:'id', value:data.id},
        //    {name:'alias', value:data.alias},
        //    {name:'type', value:data.type}
        //];

        var fdata = [
            {name:'intent_uri', value:data}
        ];

        var form = document.createElement('form');
        form.action = '/autoryzacja';
        form.method = 'POST';

        var tinput = document.createElement('input');
        tinput.type = 'hidden';
        tinput.name = '_token';
        tinput.value = $('meta[name="csrf-token"]').attr('content');
        form.appendChild(tinput);

        //var inputtype = document.createElement('input');
        //inputtype.type = 'hidden';
        //inputtype.name = 'linktype';
        //inputtype.value = 'record';
        //form.appendChild(inputtype);
        //
        //var inputtype = document.createElement('input');
        //inputtype.type = 'hidden';
        //inputtype.name = 'linktype';
        //inputtype.value = 'nofrase_record';
        //form.appendChild(inputtype);

        console.log(form);

        for (var i in fdata) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = fdata[i].name;
            input.value = fdata[i].value;
            form.appendChild(input);
        }


        form.submit();


    }


}]);


app.config(function($routeProvider, $locationProvider) {

    $locationProvider.html5Mode({
        enabled: false,
        requireBase: false
    });

});