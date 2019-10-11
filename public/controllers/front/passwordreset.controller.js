var app = angular.module('app',['ngSanitize', 'ui.select', 'ui.bootstrap.tpls','perfect_scrollbar','ngCookies'], function($interpolateProvider) {
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






app.controller('GlobalController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$cookies', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$cookies) {

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

    },1000)

}]);



app.controller('EmailPasswordController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout) {

    $scope.emailregex = /^[0-9a-z_.-]+@[0-9a-z.-]+\.[a-z]{2,3}$/i;

    $scope.emailCheck = function(){

        var deferred = $q.defer();

        if($scope.emailregex.test($scope.emailforremember)) {

            $http.post(AppService.url+'/check/reset/customer/email', {email:$scope.emailforremember})
                .success(function (data) {

                    //$log.info(data);
                    if(data==1){
                        deferred.resolve(1);
                    }else{
                        deferred.resolve(0);
                    }



                }).error(function () {

                deferred.reject();

            });

        }else{

            deferred.resolve(0);

        }

        return deferred.promise;

    }


    $scope.onSubmit = function(){

        $scope.emailCheck().then(
            function(data){

                $log.info(data);

                if(data==1){

                    $scope.alertclass = 'hidden';
                    $scope.emailclass = '';
                    $scope.emailwassend = '';

                }else{

                    $scope.emailclass = 'has-error';
                    $scope.alertclass = '';
                    $scope.emailwassend = 'hidden';

                }

            },
            function(reason){
                console.log(reason);
            }
        );

    }


}]);


app.controller('ResetPasswordController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout) {

    $scope.passregex = /^(?=^.{5,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/i

    $scope.onSubmit = function(){

        if($scope.passregex.test($scope.npassword)){

            if($scope.verifynewpassword==''){

                $scope.badpass = 'hidden';
                $scope.verifyerror = 'hidden';
                $scope.verify = '';
                return;
            }

            if($scope.npassword==$scope.verifynewpassword){

                $scope.badpass = 'hidden';
                $scope.verifyerror = 'hidden';
                $scope.verify = 'hidden';

                $http.post('/change/customer/password', {id:$scope.cid, token: $scope.token, password:$scope.npassword})
                    .then(
                        function successCallback(response){

                            //$log.info(response);
                            if(response.data==1){
                                $scope.passchange = '';
                            }

                        },
                        function errorCallback(response){

                        }
                    )

            }else{

                $scope.badpass = 'hidden';
                $scope.verifyerror = '';
                $scope.verify = 'hidden';
                return;
            }

        }else{
            $scope.badpass = '';
            $scope.verifyerror = 'hidden';
            $scope.verify = 'hidden';
            return;
        }

    }


}]);