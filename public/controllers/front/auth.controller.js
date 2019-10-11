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

app.directive('registerCustomerEnd', function() {
    return {
        templateUrl: 'templates/customerEndRegister.html'
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


app.filter('refactorArraySelect', function() { return function(array) {

    var narray = [];

    narray[0] = {id:0, name:'Wybierz opcję'};

    array.forEach(function(el, index){

        narray[el.id] = {id:el.id, name:el.name};

    });


    return narray;


}});

app.filter('refactorArraySelectNumber', function() { return function(array) {

    var narray = [];

    narray[0] = {id:0, name:'Wybierz opcję'};

    array.forEach(function(el, index){

        narray[index+1] = {id:index+1, name:el};

    });


    return narray;


}});



app.filter('propsFilter', function() {
    return function(items, props) {
        var out = [];

        if (angular.isArray(items)) {
            var keys = Object.keys(props);

            items.forEach(function(item) {
                var itemMatches = false;

                for (var i = 0; i < keys.length; i++) {
                    var prop = keys[i];
                    var text = props[prop].toLowerCase();

                    if (item[prop].toString().toLowerCase().indexOf(text) !== -1) {
                        itemMatches = true;
                        break;
                    }

                }

                if (itemMatches) {
                    out.push(item);
                }
            });
        } else {
            // Let the output be the input untouched
            out = items;
        }


        return out;
    };
});


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




app.controller('RegisterFormController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout) {


    var vm = this;


    $scope.captcha_error = false;
    $scope.captcha_error_message = null;


    //get init data for my form and wait to and get

    $scope.initData = function() {


        var deferred = $q.defer();

        $http.get('/get/auth/customer/config')
            .success(function (data) {

                //console.log(data);

                $scope.form = {
                    data: {},
                    valid: {},
                    classes: {},
                    vmessage:{},
                    vmessagetext:{}
                };


                Object.keys(data).forEach(function($key, index) {


                    $scope.form.data[$key] = '';

                    if(data[$key].require){
                        $scope.form.valid[$key] = false;
                    }else{
                        $scope.form.valid[$key] = true;
                    }

                    if(data[$key].type=='radio' && data[$key].has_oblique){

                        $scope.form.data[$key] = data[$key].selected;

                        Object.keys(data[$key].input_oblique).forEach(function($k, i) {

                            if(typeof data[$key].input_oblique[$k][$key]=='object'){

                                var arrayKey = data[$key].input_oblique[$k][$key].name;

                                $scope.form.data[arrayKey] = '';
                                $scope.form.classes[arrayKey] = '';
                                if(data[$key].input_oblique[$k][$key].require) {
                                    $scope.form.valid[arrayKey] = false;
                                }else{
                                    $scope.form.valid[arrayKey] = true;
                                }
                            }

                        });
                    }

                    $scope.form.classes[$key] = '';
                    $scope.form.vmessage[$key] = '';
                    $scope.form.vmessagetext[$key] = '';

                });


                deferred.resolve(1);

            }).error(function(){
                deferred.reject();
            });

        return deferred.promise;

    }



    $scope.getOtherDataAfterInit = function(){

        //$http.get('/get/regions')
        //    .success(function(data){
        //
        //        $scope.woj = $filter('refactorArraySelect')(data);
        //        //console.log($scope.woj);
        //        $scope.form.data.woj = $scope.woj[0];
        //        $scope.form.valid.woj = false;
        //        $scope.form.classes.woj = '';
        //
        //
        //    })
        //    .error(function(){
        //
        //    });
        //
        //
        //$http.get('/get/ocupation')
        //    .success(function(data){
        //
        //        //$scope.ocupations = $filter('refactorArraySelectNumber')(data);
        //        //console.log(data);
        //        //$scope.form.data.ocupation = $scope.ocupations[0];
        //        //$scope.form.valid.ocupation = false;
        //        //$scope.form.classes.ocupation = '';
        //
        //    })
        //    .error(function(){
        //
        //    });


        $http.get('/get/register/targets')
            .success(function(data){

                //console.log(data);
                //delete vm.usersAsync.selected;
                vm.usersAsync = data;

            })
            .error(function(){

            });

    }






    ///Init data of form getting config by route /get/auth/customer/config

    var init = $scope.initData();

    init.then(function(){

            ///START of INIT DATA Valid AND Register Functions

            $scope.getOtherDataAfterInit();

            $scope.emailregex = /^[0-9a-z_.-]+@[0-9a-z.-]+\.[a-z]{2,3}$/i;
            //start from Uppercase or lower case letter maybe number at last or bettween - min 3 characters
            $scope.passregex = /^(?=^.{5,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/i



            ////////////////////////////////////////////////////////////////////////////////////////////////////////////


            $scope.checkName = function(){

                if($scope.form.data.name.length>1){

                    $scope.form.valid.name = true;
                    $scope.form.classes.name = 'has-success';
                    $scope.form.vmessage.name = '';
                    $scope.form.vmessagetext.name = '';

                }else{

                    $scope.form.valid.name = false;
                    $scope.form.classes.name = 'has-error';
                    $scope.form.vmessage.name = '';
                    $scope.form.vmessagetext.name = '';

                }

            }


            $scope.checkSurname = function(){

                if($scope.form.data.surname.length>1){

                    $scope.form.valid.surname = true;
                    $scope.form.classes.surname = 'has-success';
                    $scope.form.vmessage.surname = '';
                    $scope.form.vmessagetext.surname = '';

                }else{

                    $scope.form.valid.surname = true;
                    $scope.form.classes.surname = 'has-error';
                    $scope.form.vmessage.surname = '';
                    $scope.form.vmessagetext.surname = '';

                }

            }

            $scope.$watch('form.data.customer_type', function(newValue, oldValue) {

                if(newValue=='instytucja'){
                    $scope.form.valid.institution = false;
                }else{
                    $scope.form.valid.institution = true;
                }

            });


            $scope.checkInstitution = function(){

                //console.log($scope.form.data);

                if($scope.form.data.customer_type=='instytucja'){

                    if($scope.form.data.institution.length>2){
                        $scope.form.classes.institution = 'has-success';
                        $scope.form.valid.institution = true;
                    }else{
                        $scope.form.classes.institution = 'has-error';
                        $scope.form.valid.institution = false;
                    }

                }else{
                    $scope.form.classes.institution = '';
                    $scope.form.valid.institution = true;

                }

            }


            $scope.checkRegister_target = function(){

                console.log(vm.usersAsync.selected);

                if(vm.usersAsync.selected){

                    $scope.form.data.register_target = vm.usersAsync.selected;

                    $scope.form.classes.register_target = 'has-success';
                    $scope.form.valid.register_target = true;

                }else{

                    $scope.form.data.register_target = '';

                    $scope.form.classes.register_target = 'has-error';
                    $scope.form.valid.register_target = false;

                }


            }


            $scope.checkEmail = function(){

                var deferred = $q.defer();

                if($scope.emailregex.test($scope.form.data.email)){


                    $http.post(AppService.url +'/is/free/email', {email:$scope.form.data.email})
                        .success(function(data){

                            if(data==1){

                                $scope.form.valid.email = true;
                                $scope.form.classes.email = 'has-success';
                                $scope.form.vmessage.email = '';
                                $scope.form.vmessagetext.email = '';
                                deferred.resolve(1);

                            }else{

                                $scope.form.valid.email = true;
                                $scope.form.classes.email = 'has-error';
                                $scope.form.vmessage.email = 'help-block';
                                $scope.form.vmessagetext.email = 'Email jest już w bazie';
                                deferred.reject(0);

                            }

                        })
                        .error(function(data){

                        });

                    return deferred.promise;

                }else{

                    $scope.form.valid.email = false;
                    $scope.form.classes.email = 'has-error';
                    $scope.form.vmessage.email = '';
                    $scope.form.vmessagetext.email = '';
                    deferred.reject(0);
                    return deferred.promise;

                }



            }


            $scope.checkPassword = function(){

                if($scope.passregex.test($scope.form.data.password)){

                    $scope.form.valid.password = true;
                    $scope.form.classes.password = 'has-success';
                    $scope.form.vmessage.password = '';
                    $scope.form.vmessagetext.password = '';

                }else{

                    $scope.form.valid.password = false;
                    $scope.form.classes.password = 'has-error';
                    $scope.form.vmessage.password = 'help-block';
                    $scope.form.vmessagetext.password = 'Hasło musi zawierać przynajmiej jedną cyfrę';

                }

            }


            $scope.checkRepassword = function(){

                if($scope.passregex.test($scope.form.data.repassword)){

                    if($scope.form.valid.password){
                        $scope.form.valid.repassword = true;
                        $scope.form.classes.repassword = 'has-success';
                        $scope.form.vmessage.repassword = '';
                        $scope.form.vmessagetext.repassword = '';
                    }


                }else{

                    $scope.form.valid.repassword = false;
                    $scope.form.classes.repassword = 'has-error';
                    $scope.form.vmessage.repassword = 'help-block';
                    if($scope.form.valid.password) {
                        $scope.form.vmessagetext.repassword = 'Hasło niezgodne';
                    }

                }

            }


            $scope.checkRegulations = function(){

                if($scope.form.data.regulations){
                    $scope.form.valid.regulations = true;
                    $scope.form.classes.regulations = '';
                }else{
                    $scope.form.valid.regulations = false;
                    $scope.form.classes.regulations = 'has-error';
                }

            }


            $scope.checkPrivatePolicy = function(){

                if($scope.form.data.privacy_policy){
                    $scope.form.valid.privacy_policy = true;
                    $scope.form.classes.privacy_policy = '';
                }else{
                    $scope.form.valid.privacy_policy = false;
                    $scope.form.classes.privacy_policy = 'has-error';
                }

            }



            //$scope.checkIsSelectWoj = function(){
            //
            //    if($scope.form.data.woj.id==0){
            //
            //        $scope.form.valid.woj = false;
            //        $scope.form.classes.woj = 'has-error';
            //
            //    }else{
            //
            //        $scope.form.valid.woj = true;
            //        $scope.form.classes.woj = 'has-success';
            //
            //    }
            //
            //}





            //$scope.checkIsSelectOcupation = function(){
            //
            //
            //    if($scope.form.data.ocupation.id==0){
            //
            //        $scope.form.valid.ocupation = false;
            //        $scope.form.classes.ocupation = 'has-error';
            //
            //    }else{
            //
            //        $scope.form.valid.ocupation = true;
            //        $scope.form.classes.ocupation = 'has-success';
            //
            //    }
            //
            //
            //}


            $scope.registerCustomer = function(){

                $scope.loading = '';

                var deferred = $q.defer();

                $http.put(AppService.url +'/customer/register', $scope.form.data)
                    .success(function(data){

                        //console.log(data);
                        $scope.loading = 'hidden';
                        $scope.initData();
                        $scope.getOtherDataAfterInit();
                        $scope.regcustomerend = '';
                        //grecaptcha.reset();

                    })
                    .error(function(data) {



                    })


            }

            $scope.checkCaptchaAjaxRequest = function(){

                var deferred = $q.defer();


                $http.post(AppService.url +'/customer/recaptcha/check', {cresponse:grecaptcha.getResponse()})
                    .success(function(data){

                        //console.log(data);

                        if(data=='set_captcha'){
                            $scope.captcha_error = true;
                            $scope.captcha_error_message = 'Kliknij "Nie jestem robotem"';
                            deferred.reject(0);
                        }else if(data=='captcha_ok'){
                            $scope.captcha_error = false;
                            $scope.captcha_error_message = null;
                            deferred.reject(1);
                        }else if(data=='bad_captcha'){
                            grecaptcha.reset();
                            $scope.captcha_error = true;
                            $scope.captcha_error_message = 'Błędny wybór captcha spróbuj ponownie';
                            deferred.reject(0);
                        }


                    })
                    .error(function(data) {



                    });


                return deferred.promise;

            }



            $scope.registerSubmit = function(){

                var checkemail = $scope.checkEmail();
                $scope.checkPassword();
                $scope.checkRepassword();
                $scope.checkName();
                $scope.checkSurname();
                $scope.checkInstitution();
                $scope.checkRegister_target();
                $scope.checkRegulations();
                $scope.checkPrivatePolicy();

                //$scope.checkCaptchaAjaxRequest();


                //$scope.checkIsSelectWoj();
                //$scope.checkIsSelectOcupation();

                //console.log($scope.form.valid);

                checkemail.then(
                    function(data){

                        if(data==1){

                            //$log.info($scope.form.valid);

                            if($filter('checkfalse')($scope.form.valid)){

                                $scope.registerCustomer();

                            }

                        }

                    },
                    function(reason){
                        console.log(reason);
                    }
                );
            }


            ///END of INIT DATA Valid AND Register Functions

        },
        function(reason){

        }
    );





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

        console.log($scope.intent_link);

        var authcheck = $scope.checkAuth();


        authcheck.then(
            function(data){

                console.log(data);
                console.log($scope.intent_link);

                $scope.loading = 'hidden';

                if(data==1){

                    if($scope.intent_link!=''){

                        if($scope.intent_hash!=''){
                            $window.location.href = AppService.url+'/'+$scope.intent_link+$scope.intent_hash;
                        }else{
                            $window.location.href = AppService.url+'/'+$scope.intent_link;
                        }

                        //$scope.sendPostToRecord($scope.intent_link);

                    }else{

                        $window.location.href = '/';

                    }


                }else{

                }
            },
            function(reason){
                console.log(reason);
            }
        );


    }


    $scope.sendPostToRecord = function(link){

        var fdata = [
            {name:'frase', value: $scope.frase}
        ];

        var form = document.createElement('form');
        form.action = '/'+link;
        form.method = 'POST';

        var tinput = document.createElement('input');
        tinput.type = 'hidden';
        tinput.name = '_token';
        tinput.value = $('meta[name="csrf-token"]').attr('content');
        form.appendChild(tinput);


        for (var i in fdata) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = fdata[i].name;
            input.value = fdata[i].value;
            form.appendChild(input);
        }

        var button = document.createElement("input");
        button.setAttribute('type', "submit");
        button.className = 'hidden';
        form.appendChild(button);
        document.body.appendChild(form);
        form.submit();

    }


}]);