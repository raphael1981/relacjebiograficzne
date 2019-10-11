var app = angular.module('app',['ngSanitize'], function($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});


app.config(['$httpProvider', function ($httpProvider) {
    $httpProvider.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');
    $httpProvider.defaults.useXDomain = true;
}]);


app.factory('AppService', function($location) {
    return {
        url : $location.protocol()+'://'+$location.host(),
        customerurl: 'http://ahm.spaceforweb.pl'
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


app.controller('GlobalController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout) {

    $timeout(function(){

        angular.element(document.getElementById('body')).removeClass('alphaHide');
        angular.element(document.getElementById('body')).addClass('alphaShow');

    },1000)

}]);


app.controller('LoginFormController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', function($scope, $http, $log, $q, $location,AppService, $window, $filter) {

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

            $http.post(AppService.url + '/login', $scope.form.data)
                .success(function (data) {
                    //$log.info(data);

                    if(data==1){
                        $scope.login = true;
                        deferred.resolve(1);
                    }else{
                        $scope.login = false;
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

                $scope.loading = 'hidden';

                if(data==1){

                    $window.location.reload();

                }else{

                }
            },
            function(reason){
                console.log(reason);
            }
        );


    }




}]);