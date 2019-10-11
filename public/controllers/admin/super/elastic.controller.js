var app = angular.module('app',['ngSanitize', 'ngRoute'], function($interpolateProvider) {
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



app.directive('loadingData', function() {
    return {
        templateUrl: 'templates/overload.html'
    };
});




app.controller('GlobalController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout) {

    $timeout(function(){

        angular.element(document.getElementById('body')).removeClass('alphaHide');
        angular.element(document.getElementById('body')).addClass('alphaShow');

    },500)

}]);


app.controller('ElasticController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams) {


    $scope.initData = function(){

        $scope.raports = {
            records:null,
            images:null,
            gallery:null
        }

        $scope.btnrecords = true;
        $scope.btnimages = true;
        $scope.btngallery = true;

        $scope.getLastRecordsIndex()
            .then(function(res){
                //console.log(res);
                if(res!='') {
                    if(res.end_at!=null && res.status==1){
                        $scope.btnrecords = false
                    }
                    res.start_at = Date.parse(res.start_at)
                    res.end_at = Date.parse(res.end_at)
                }else{
                    $scope.btnrecords = false;
                }
                $scope.raports.records = res;
            });

        $scope.getLastImagesIndex()
            .then(function(res){
                //console.log(res);
                if(res!='') {
                    if(res.end_at!=null && res.status==1){
                        $scope.btnimages = false
                    }
                    res.start_at = Date.parse(res.start_at)
                    res.end_at = Date.parse(res.end_at)
                }else{
                    $scope.btnimages = false
                }
                $scope.raports.images = res;
            });

        $scope.getLastGalleryIndex()
            .then(function(res){
                //console.log(res);
                if(res!='') {
                    if(res.end_at!=null && res.status==1){
                        $scope.btngallery = false
                    }
                    res.start_at = Date.parse(res.start_at)
                    res.end_at = Date.parse(res.end_at)
                }else{
                    $scope.btngallery = false
                }
                $scope.raports.gallery = res;
            });

    }


    $scope.getLastRecordsIndex = function(){

        var deffer = $q.defer();

        $http.get(AppService.url+'/administrator/elasticsearch/get/records/index')
            .then(function(res){
                deffer.resolve(res.data);
            })

        return deffer.promise;

    }


    $scope.getLastImagesIndex = function(){

        var deffer = $q.defer();

        $http.get(AppService.url+'/administrator/elasticsearch/get/images/index')
            .then(function(res){
                deffer.resolve(res.data);
            })

        return deffer.promise;

    }


    $scope.getLastGalleryIndex = function(){

        var deffer = $q.defer();

        $http.get(AppService.url+'/administrator/elasticsearch/get/gallery/index')
            .then(function(res){
                deffer.resolve(res.data);
            })

        return deffer.promise;

    }


    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $scope.getMonitorById = function(id){

        var deffer = $q.defer();

        $http.get(AppService.url+'/administrator/elasticsearch/get/monitor/by/'+id)
            .then(function(response){
                deffer.resolve(response.data);
            })

        return deffer.promise;
    }

    $scope.makeIndexRecords = function(){


        $http.get(AppService.url+'/administrator/elasticsearch/make/records/index')
            .then(function(response){

                $scope.raports.records = null;
                $scope.btnrecords = true;
                $scope.getMonitorById(response.data.raport)
                    .then(function(res){
                        if(res!='') {
                            res.start_at = Date.parse(res.start_at)
                            res.end_at = Date.parse(res.end_at)
                        }
                        $scope.raports.records = res;
                    })

            })

    }


    $scope.makeIndexImages = function(){


        $http.get(AppService.url+'/administrator/elasticsearch/make/images/index')
            .then(function(response){

                $scope.raports.images = null;
                $scope.btnimages = true;
                $scope.getMonitorById(response.data.raport)
                    .then(function(res){
                        if(res!='') {
                            res.start_at = Date.parse(res.start_at)
                            res.end_at = Date.parse(res.end_at)
                        }
                        $scope.raports.images = res;
                    })

            })

    }


    $scope.makeIndexGallery = function(){


        $http.get(AppService.url+'/administrator/elasticsearch/make/gallery/index')
            .then(function(response){

                $scope.raports.gallery = null;
                $scope.btngallery = true;
                $scope.getMonitorById(response.data.raport)
                    .then(function(res){
                        if(res!='') {
                            res.start_at = Date.parse(res.start_at)
                            res.end_at = Date.parse(res.end_at)
                        }
                        $scope.raports.gallery = res;
                    })

            })

    }


    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $scope.forceIndexRecords = function(){

        $http.get(AppService.url+'/administrator/elasticsearch/force/make/records/index')
            .then(function(response){

                $scope.raports.records = null;
                $scope.btnrecords = true;
                $scope.getMonitorById(response.data.raport)
                    .then(function(res){
                        if(res!='') {
                            res.start_at = Date.parse(res.start_at)
                            res.end_at = Date.parse(res.end_at)
                        }
                        $scope.raports.records = res;
                    })

            })


    }


    $scope.forceIndexImages = function(){

        $http.get(AppService.url+'/administrator/elasticsearch/force/make/images/index')
            .then(function(response){

                $scope.raports.images = null;
                $scope.btnimages = true;
                $scope.getMonitorById(response.data.raport)
                    .then(function(res){
                        if(res!='') {
                            res.start_at = Date.parse(res.start_at)
                            res.end_at = Date.parse(res.end_at)
                        }
                        $scope.raports.images = res;
                    })

            })

    }


    $scope.forceIndexGallery = function(){


        $http.get(AppService.url+'/administrator/elasticsearch/force/make/gallery/index')
            .then(function(response){

                $scope.raports.gallery = null;
                $scope.btngallery = true;
                $scope.getMonitorById(response.data.raport)
                    .then(function(res){
                        if(res!='') {
                            res.start_at = Date.parse(res.start_at)
                            res.end_at = Date.parse(res.end_at)
                        }
                        $scope.raports.gallery = res;
                    })

            })


    }


}]);


app.config(function($routeProvider, $locationProvider) {

    $routeProvider.
    when('/', {
        templateUrl: '/templates/admin/super/elastic/master.html',
        controller: 'ElasticController'
    });

    $locationProvider.html5Mode({
        enabled: false,
        requireBase: false
    });

});