var app = angular.module('app',['ngSanitize'], function($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});

app.config(['$httpProvider', function ($httpProvider) {
    $httpProvider.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');
    $httpProvider.defaults.useXDomain = true;
}]);


app.factory('AppService', function($location,$rootScope) {
    return {
        url : $location.protocol()+'://'+$location.host(),
        customerurl: 'http://adminahm.zbiglem.pl'
    };
});

app.controller('GlobalController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout) {

    $timeout(function(){

        angular.element(document.getElementById('body')).removeClass('alphaHide');
        angular.element(document.getElementById('body')).addClass('alphaShow');

    },1000)

}]);