var app = angular.module('app',['ngSanitize','ngRoute','ngAnimate','perfect_scrollbar'], function($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});

app.factory('AppService', function($location) {
    return {
        url : $location.protocol()+'://'+$location.host(),

        makeRegex: function (frase) {

            var reg = /\sdo\s|\sna\s|\si\s|\sz\s|\sod\s|\sw\s|\.\s|\,\s|\s/g;
            var words = frase.split(reg);
            var newWords = [];

            angular.forEach(words,function(item,key){
                if(item.length < 3){
                    newWords.push(item);
                }else if(item.length < 5){
                    newWords.push(item.substring(0, item.length - 2));
                }else if(item.length < 8){
                    newWords.push(item.substring(0, item.length - 3));
                }else if(item.length < 10){
                    newWords.push(item.substring(0, item.length - 5));
                }else {
                    newWords.push(item.substring(0, item.length - 6));
                }
            });


            var newregex = new RegExp(newWords.join('(.{0,10})')+'([^,\.\? ]+)','gi');

            return newregex;

        }
    };
});


app.factory('searchFactory', function(){

    service.getArtist = function(){
        return 11;
    }

});


app.factory('Search', function($http, $cacheFactory) {
    return {
        get: function(payload, successCallback, url){
            var key = 'search_' + payload.q;
            if($cacheFactory.get(key) == undefined || $cacheFactory.get(key) == ''){
                $http.get(url, {params: payload}).then(function(data){
                    $cacheFactory(key).put('result', data.data);
                    successCallback(data.data);
                });
            }else{
                successCallback($cacheFactory.get(key).get('result'));
            }
        }
    }
});


app.directive('loadingData', function() {
    return {
        templateUrl: 'templates/overload.html'
    };
});

app.directive('registerCustomerEnd', function() {
    return {
        templateUrl: 'templates/customerEndRegister.html'
    };
});


app.filter('unique', function() {
    return function(collection, keyname) {
        var output = [],
            keys = [];
        angular.forEach(collection, function(item) {
            var key = item[keyname];
            if(keys.indexOf(key) === -1) {
                keys.push(key);
                output.push(item);
            }
        });
        return output;
    };
});

app.directive('searchFragments', function() {
    return {
        templateUrl: 'templates/searchFragments.html',
        link: function(scope, element, attributes, searchFactory){

            //console.log(attributes);

            scope.fragments = JSON.parse(attributes.searchFragments);
            scope.record = JSON.parse(attributes.record);
            scope.frase = attributes.frase;
            scope.auth = attributes.auth;
            scope.stype = attributes.type;
            console.log(scope.stype);
            //AppService.makeRegex($scope.data.search.frase);

            //attributes.$observe('searchFragments', function(value){
            //    console.log(value);
            //});
            //
            //attributes.$observe('fragments', function(value){
            //    console.log(value);
            //});


        }
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




app.filter('timeToMinute', function() {
    return function(int) {
        if(int==0){
            return '00:00:00';
        }else{

            var date = new Date(int*1000);

            var hh = date.getUTCHours();
            var mm = date.getUTCMinutes();
            var ss = date.getSeconds();


            if (hh < 10) {hh = "0"+hh;}
            if (mm < 10) {mm = "0"+mm;}
            if (ss < 10) {ss = "0"+ss;}

            var t = hh+":"+mm+":"+ss;

            return t;
        }
    }

});




app.controller('GlobalController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout', '$rootScope', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout, $rootScope) {

    $timeout(function(){

        angular.element(document.getElementById('body')).removeClass('alphaHide');
        angular.element(document.getElementById('body')).addClass('alphaShow');

    },500)


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



app.controller('SearchNoAuthController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','Search', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,Search) {


    $scope.data = {};
    $scope.data.results = null;
    $scope.auth = false;

    $scope.initOnLoad = function(){

    }

    $scope.hideRoleta = function(model){
        $scope[model] = [];
    }

    $scope.getSuggest = function(){

    }


    $scope.$watch('searchTextTag', function (val) {
        console.log('val: ',val)
        var payload = {'q': val};
        if(val != '' && val != undefined && val.length > 2){
            angular.element('.well.roleta').css('visibility','visible');
            Search.get(payload, function(data){
                $scope.searchtag = [];
                var flat = [];
                var dat = [];
                for(var i=0; i<data.length; i++){
                    dat = angular.fromJson(data[i]);
                    for(var j=0; j<dat.length; j++){
                        flat.push(dat[j])
                    }
                }


                for(var i=0; i<flat.length; i++){
                    if(flat[i].text.toLowerCase().indexOf(val.toLowerCase())>-1){
                        $scope.searchtag.push(flat[i]);
                    }
                }
                $timeout(function(){console.log('czekam')},10)
                if($scope.searchtag.length>0){
                    $scope.tagclass = 'success';
                }else{
                    $scope.tagclass = 'error';
                }
            },'/bookstore/tags/list');
            console.log($scope.searchtag);
        }else{
            $scope.searchtag = [];
            $scope.tagclass = '';
        }
    },true)


    $scope.getSearchData = function(){
        $http.post(AppService.url + '/ahm/search/data', $scope.data.search)
            .then(
                function successCallback(response) {


                },
                function errorCallback(response) {

                }
            );

    }


    $scope.onSubmit = function(){
    }



}]);


app.config(function($routeProvider, $locationProvider) {
   $locationProvider.html5Mode({
        enabled: false,
        requireBase: false
    });

});