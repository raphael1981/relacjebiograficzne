var app = angular.module('app',['ngSanitize', 'ngRoute', 'xeditable','ngDialog', 'ui.select'], function($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});

app.config(['$httpProvider', function ($httpProvider) {
    $httpProvider.defaults.headers.common['X-CSRF-TOKEN'] = $('meta[name="csrf-token"]').attr('content');
    $httpProvider.defaults.useXDomain = true;
}]);

app.run(function(editableOptions, editableThemes) {
    editableOptions.theme = 'bs3'; // bootstrap3 theme. Can be also 'bs2', 'default'
    //console.log(editableThemes);
});

app.factory('AppService', function($location) {
    return {
        url : $location.protocol()+'://'+$location.host(),
        customerurl: 'http://adminrelacje.dsh.waw.pl'
    };
});


app.directive('loadingData', function() {
    return {
        templateUrl: 'templates/overload.html'
    };
});


app.directive('showPagination', function() {
    return {
        templateUrl: '/templates/admin/super/pagination.html'
    };
});


app.directive('showSearchCriteria', function() {
    return {
        templateUrl: '/templates/admin/super/employees/search.html'
    };
});

app.directive('statusBtnInList', function() {
    return {
        templateUrl: '/templates/admin/super/employees/status.html',
        link: function(scope, element, attributes, searchFactory){

            //console.log(attributes);
            scope.status = attributes.statusBtnInList;
            scope.id = attributes.artid;

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



app.directive('deleteElement', function(AppService, $http, $q, $filter, $route) {
    return {
        templateUrl: '/templates/admin/super/employees/delete.html',
        link: function(scope, element, attributes, searchFactory){



            //console.log(attributes);
            scope.id = attributes.id;

            scope.relations = [
                {
                    type:'manyToMany',
                    method:'records',
                    name:'Rekordy'
                }

            ];

            scope.model = 'App\\Entities\\User';

            scope.getBeforeDeleteRaport = function(){

                var deferred = $q.defer();

                $http.post(AppService.url+'/administrator/user/get/raport/before/delete', {relations:scope.relations, id:attributes.id})
                    .then(
                        function successCallback(response){
                            scope.raport = response.data;
                            deferred.resolve(1);
                        },
                        function errorCallback(reason){
                            deferred.reject(1);
                        }
                    );

                return deferred.promise;
            }


            scope.deleteElement = function(){

                var deferred = $q.defer();

                $http.put(AppService.url+'/administrator/delete/user', {relations:scope.relations, id:attributes.id, model:scope.model})
                    .then(
                        function successCallback(response){
                            console.log(response.data);
                            scope.showshadow = '';
                            $route.reload();
                            deferred.resolve(1);
                        },
                        function errorCallback(reason){
                            deferred.reject(1);
                        }
                    );

                return deferred.promise;

            }




        }
    };
});

app.filter('delraportview', function() { return function(obj) {

    var view = '';

    angular.forEach(obj, function(item, key){


        if(key!='undefined' || item!='undefined' || typeof item=='object') {
            view += '<h4><span class="label label-info">' + key + ': </span>&nbsp;&nbsp;&nbsp;';
            view += item+'<h4>';
        }

    });


    return view;

}});

app.controller('GlobalController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout) {

    $timeout(function(){

        angular.element(document.getElementById('body')).removeClass('alphaHide');
        angular.element(document.getElementById('body')).addClass('alphaShow');

    },1000)

}]);


app.controller('EmployeesController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams) {


    $scope.initData = function(){

        $scope.limit = 10;
        $scope.start = 0;
        $scope.frase = null;
        $scope.searchcolumns = {
            name:true,
            surname:true,
            email:true
        };

        $scope.filter = {
            status: {name:'Wszystkie', number:null},
        }

        $scope.statuses = [
            {name:'Wszystkie', number:null},
            {name:'Opublikowany', number:1},
            {name:'Nieopublikowany', number:0}
        ]

        $scope.getElements(0);

    }


    //Get Data Logic


    $scope.getElements = function(iterstart){

        $http.post(AppService.url + '/administrator/get/employees',
            {
                start:$scope.start,
                limit:$scope.limit,
                frase:$scope.frase,
                searchcolumns:$scope.searchcolumns,
                filter:$scope.filter
            })
            .then(
                function successCallback(response) {

                    $log.info(response.data);



                    $scope.count = response.data.count;

                    if(iterstart==null){
                        $scope.iterstart = $scope.start;
                    }else{
                        $scope.iterstart = iterstart;
                    }

                    $scope.elements = response.data.elements;
                    $scope.allpags = response.data.count/$scope.limit;
                    $scope.even = $scope.allpags%1;
                    $scope.pages = Math.floor($scope.allpags);
                    $scope.half = Math.floor($scope.pages/2);


                    if($scope.pages>(2*$scope.limit)){

                        $scope.first = [];
                        $scope.last = [];

                        if( iterstart>($scope.pages-5) && !$scope.halfclick) {


                            var iterlimit = 3;
                            if (iterstart >= ($scope.half - 2)) {
                                iterlimit = 5;
                            }


                            var x = 0;

                            for (var i = 0; i < iterlimit; i++) {

                                if (iterstart == i) {

                                    $scope.first[x] = {start: i, nr: (i + 1), pclass: 'active'};

                                } else {

                                    $scope.first[x] = {start: i, nr: (i + 1), pclass: ''};
                                }

                                x++;
                            }

                        }else if($scope.halfclick){


                            var x = 0;

                            for (var i = $scope.iterstart; i < ($scope.iterstart+3); i++) {

                                if (iterstart == i) {

                                    $scope.first[x] = {start: i, nr: (i + 1), pclass: 'active'};

                                } else {

                                    $scope.first[x] = {start: i, nr: (i + 1), pclass: ''};
                                }

                                x++;
                            }


                        }else {

                            var x = 0;

                            for (var i = iterstart; i < (iterstart + 3); i++) {


                                if(iterstart==i) {

                                    $scope.first[x] = {start: i, nr: (i + 1), pclass:'active'};

                                }else{

                                    $scope.first[x] = {start: i, nr: (i + 1), pclass:''};

                                }

                                x++;
                            }

                        }


                        ///////////////////////////Last Buttons/////////////////////////////////////////////////

                        var plusiter = 1;
                        if($scope.even==0){
                            plusiter = 0;
                        }

                        if( (iterstart<$scope.pages) && !$scope.halfclick) {

                            var y = 0;

                            var backfromend = 3;

                            //jeżeli z przodu jest jest już ottanie takie jakie z przodu
                            if(iterstart<$scope.pages && iterstart>($scope.pages-5)){
                                backfromend = 4;
                            }else{
                                backfromend = 3;
                            }
                            //jeżeli z przodu jest jest już ottanie takie jakie z przodu



                            for (var i = ($scope.pages + plusiter); i > ($scope.pages - backfromend); i--) {

                                if (iterstart == (i - 1)) {

                                    $scope.last[y] = {start: (i - 1), nr: (i), pclass: 'active'};


                                } else {

                                    $scope.last[y] = {start: (i - 1), nr: (i), pclass: ''};

                                }

                                y++;

                            }


                        }else if($scope.halfclick){


                            var y = 0;


                            for (var i = ($scope.pages + plusiter); i > ($scope.pages - 3); i--) {

                                if (iterstart == (i - 1)) {

                                    $scope.last[y] = {start: (i - 1), nr: (i), pclass: 'active'};


                                } else {

                                    $scope.last[y] = {start: (i - 1), nr: (i), pclass: ''};

                                }

                                y++;

                            }



                        }else {

                            //console.log($scope.pages);

                            var y = 0;

                            var minusright = $scope.pages-$scope.iterstart;

                            for (var i = (($scope.pages + plusiter)- minusright); i > ($scope.pages - 3 - minusright); i--) {

                                if (iterstart == (i - 1)) {

                                    $scope.last[y] = {start: (i - 1), nr: (i), pclass: 'active'};


                                } else {

                                    $scope.last[y] = {start: (i - 1), nr: (i), pclass: ''};

                                }

                                y++;

                            }


                        }

                        $scope.last.reverse();

                        $scope.minimalist = true;



                    }else{

                        var reduct = 0;

                        if($scope.even==0){
                            reduct = 1;
                        }


                        $scope.pag = [];

                        for(var i=0;i<=($scope.pages-reduct);i++){

                            if(iterstart==i) {

                                $scope.pag[i] = {start: i, nr: (i + 1), pclass:'active'};

                            }else{

                                $scope.pag[i] = {start: i, nr: (i + 1), pclass:''};

                            }
                        }

                        $scope.minimalist = false;

                    }


                },
                function errorCallback(response) {

                }
            );

    }


    $scope.changePage = function(start){

        $scope.halfclick = false;
        $scope.start = $scope.limit*start;
        $scope.getElements(start);


    }


    $scope.goToHalf = function(){

        $scope.halfclick = true;
        $scope.start = $scope.half;
        $scope.getElements($scope.start);

    }


    $scope.changePagePrev = function(){

        $scope.halfclick = false;
        var start = $scope.iterstart-1;
        $scope.start = $scope.limit*start;
        $scope.getElements(start);

    }


    $scope.changePageNext = function(){

        $scope.halfclick = false;
        var start = $scope.iterstart+1;
        $scope.start = $scope.limit*start;
        $scope.getElements(start);

    }


    $scope.changeShowFirst = function(){

        $scope.start = 0;
        $scope.getElements(0);

    }

    $scope.changeShowLast = function(){

        $scope.start = $scope.pages*$scope.limit;
        $scope.getElements($scope.pages);

    }


    //Get Data Logic

    $scope.searchSubmit = function(){

        $scope.start = 0;
        $scope.getElements(0);

    }


    $scope.changeEmployeeData = function(field, value, id){

        $http.put(AppService.url + '/administrator/update/employee/data',
            {
                field:field,
                value:value,
                id:id
            }
            )
            .then(
                function successCallback(response) {

                    console.log(response);
                    $scope.getElements($scope.iterstart);

                },
                function errorCallback(response) {

                }
            );

    }


}]);



app.controller('NewEmployeeController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams', '$rootScope', '$sce', '$interval', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams,$rootScope, $sce, $interval) {


    $scope.initData = function(){

        $scope.employee = {
            name:'',
            surname:'',
            email:'',
            password:'',
            permission:{name:'Redaktor transkrypcji', type:'employee'},
            records:[]
        }

        $scope.valid = {
            name:false,
            surname:false,
            email:'',
            password:false
        }

        $scope.classes = {
            name:'',
            surname:'',
            email:'',
            password:''
        }

        $scope.permissions = [
            {name:'Super admin', type:'super'},
            {name:'Redaktor transkrypcji', type:'employee'}
        ]

        $scope.records = null;

        $scope.emailregex = /^[0-9a-z_.-]+@[0-9a-z.-]+\.[a-z]{2,3}$/i;
        $scope.passregex = /^(?=^.{5,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/i

        $scope.getListRecords()
            .then(
                function(response){

                }
            )


    }


    $scope.getListRecords = function(){

        var deferred = $q.defer();

        $http.get(AppService.url+'/get/all/records')
            .then(
                function successCallback(response){
                    //console.log(response.data);
                    $scope.records = response.data;
                    deferred.resolve();

                },
                function errorCallback(reason){
                    deferred.reject();
                }
            )

        return deferred.promise;

    }



    $scope.checkName = function(){

        if($scope.employee.name.length>1){

            $scope.valid.name = true;
            $scope.classes.name = 'has-success';

        }else{

            $scope.valid.name = false;
            $scope.classes.name = 'has-error';
        }

    }


    $scope.checkSurname = function(){

        if($scope.employee.surname.length>1){

            $scope.valid.surname = true;
            $scope.classes.surname = 'has-success';

        }else{

            $scope.valid.surname = false;
            $scope.classes.surname = 'has-error';
        }

    }


    $scope.checkEmail = function(){

        var deferred = $q.defer();

        if($scope.emailregex.test($scope.employee.email)){

            $scope.valid.email = true;
            $scope.classes.email = 'has-success';
            deferred.resolve();

        }else{

            $scope.valid.email = false;
            $scope.classes.email = 'has-error';
            deferred.reject();
        }

        return deferred.promise;

    }


    $scope.checkIsEmailInBase = function(){

        var deferred = $q.defer();

        $http.post(AppService.url+'/administrator/check/is/employee/in/base', {email:$scope.employee.email})
            .then(
                function(response){

                    if(response.data==0){
                        $scope.valid.email = true;
                        $scope.classes.email = 'has-success';
                        deferred.resolve(response.data);
                    }else{
                        $scope.valid.email = false;
                        $scope.classes.email = 'has-error';
                        $scope.raportemail = 'Email jest już w bazie';
                        deferred.reject();
                    }

                }
            )

        return deferred.promise;

    }


    $scope.checkPassword = function(){

        if($scope.passregex.test($scope.employee.password)){

            $scope.valid.password = true;
            $scope.classes.password = 'has-success';
            $scope.raportpassword = '';

        }else{

            $scope.valid.password = false;
            $scope.classes.password = 'has-error';
            $scope.raportpassword = 'Hasło słabe musi zawierać cyfry i litery';
        }

    }





    $scope.createNewEmployee = function(){

        $scope.checkName();
        $scope.checkSurname();
        $scope.checkPassword();


        $scope.checkEmail()
            .then(
                function(response){

                    $scope.checkIsEmailInBase()
                        .then(
                            function(response){
                                $scope.addEmployeeToBase();
                            }
                        )

                }
            )

    }


    $scope.addEmployeeToBase = function(){

        if($filter('checkfalse')($scope.valid)){

            $scope.emplsaving = '';

            $http.put(AppService.url+'/administrator/create/new/employee', $scope.employee)
                .then(
                    function successCallback(response){

                        $log.info(response.data);
                        if(response.data.success){
                            $scope.emplsaving = 'hidden';
                            $scope.emplsaved = '';
                            $location.path('/edit/'+response.data.id).search('action=added');
                        }


                    },
                    function errorCallback(reason){

                    }
                )

        }

    }



}]);


app.controller('EditEmployeeController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams', '$rootScope', '$sce', '$interval', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams,$rootScope, $sce, $interval) {


    $scope.initData = function(){

        $scope.employee = {
            id:$routeParams.id,
            name:'',
            surname:'',
            email:'',
            password:'',
            permission:{name:'Redaktor transkrypcji', type:'employee'},
            records:[]
        }

        $scope.valid = {
            name:false,
            surname:false,
            email:''
        }

        $scope.classes = {
            name:'',
            surname:'',
            email:''
        }

        $scope.permissions = [
            {name:'Super admin', type:'super'},
            {name:'Redaktor transkrypcji', type:'employee'}
        ]

        $scope.records = null;

        $scope.emailregex = /^[0-9a-z_.-]+@[0-9a-z.-]+\.[a-z]{2,3}$/i;
        $scope.passregex = /^(?=^.{5,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/i

        $scope.getListRecords()
            .then(
                function(response){
                    $scope.getFullEmployeeData();
                }
            )


    }


    $scope.getFullEmployeeData = function(){

        $http.get(AppService.url+'/administrator/get/full/employee/data/by/id/'+$scope.employee.id)
            .then(
                function(response){
                    console.log(response);
                    $scope.employee = response.data.employee;

                    angular.forEach($scope.permissions, function(item, i){

                        if(item.type==$scope.employee.permission){
                            $scope.employee.permission = $scope.permissions[i];
                        }

                    });

                }
            )

    }


    $scope.getListRecords = function(){

        var deferred = $q.defer();

        $http.get(AppService.url+'/get/all/records')
            .then(
                function successCallback(response){
                    //console.log(response.data);
                    $scope.records = response.data;
                    deferred.resolve();

                },
                function errorCallback(reason){
                    deferred.reject();
                }
            )

        return deferred.promise;

    }



    $scope.checkName = function(){

        if($scope.employee.name.length>1){

            $scope.valid.name = true;
            $scope.classes.name = 'has-success';

        }else{

            $scope.valid.name = false;
            $scope.classes.name = 'has-error';
        }

    }


    $scope.checkSurname = function(){

        if($scope.employee.surname.length>1){

            $scope.valid.surname = true;
            $scope.classes.surname = 'has-success';

        }else{

            $scope.valid.surname = false;
            $scope.classes.surname = 'has-error';
        }

    }


    $scope.checkEmail = function(){

        var deferred = $q.defer();

        if($scope.emailregex.test($scope.employee.email)){

            $scope.valid.email = true;
            $scope.classes.email = 'has-success';
            deferred.resolve();

        }else{

            $scope.valid.email = false;
            $scope.classes.email = 'has-error';
            deferred.reject();
        }

        return deferred.promise;

    }


    $scope.checkIsEmailInBase = function(){

        var deferred = $q.defer();

        $http.post(AppService.url+'/administrator/check/is/employee/in/base/except/current', $scope.employee)
            .then(
                function(response){

                    console.log(response);

                    if(response.data==0){
                        $scope.valid.email = true;
                        $scope.classes.email = 'has-success';
                        $scope.raportemail = '';
                        deferred.resolve(response.data);
                    }else{
                        $scope.valid.email = false;
                        $scope.classes.email = 'has-error';
                        $scope.raportemail = 'Email jest już w bazie';
                        deferred.reject();
                    }

                }
            )

        return deferred.promise;

    }


    $scope.checkPassword = function($data){


        if($scope.passregex.test($data)){

            $scope.valid.password = true;
            $scope.classes.password = 'has-success';
            //$scope.raportpassword = '';

        }else{

            $scope.valid.password = false;
            $scope.classes.password = 'has-error';
            //$scope.raportpassword = 'Hasło słabe musi zawierać cyfry';
            return 'Hasło słabe musi zawierać cyfry i litery';

        }



    }


    $scope.changePassword = function($data){

        console.log($data);
        var deferred = $q.defer();

        $http.put(AppService.url+'/administrator/change/employee/password', {password:$data, id:$scope.employee.id})
            .then(
                function successCallback(response){

                    $log.info(response.data);
                    $scope.raportpassword = 'Hasło zostało zmienione';
                    deferred.resolve();

                },
                function errorCallback(reason){
                    deferred.reject();
                }
            )


        //return deferred.promise;

    }


    $scope.updateEmployee = function(){

        $scope.checkName();
        $scope.checkSurname();


        $scope.checkEmail()
            .then(
                function(response){

                    $scope.checkIsEmailInBase()
                        .then(
                            function(response){
                                $scope.addEmployeeDataToBase();
                            }
                        )

                }
            )

    }


    $scope.addEmployeeDataToBase = function(){

        if($filter('checkfalse')($scope.valid)){

            $scope.emplsaving = '';

            $http.put(AppService.url+'/administrator/update/data/employee', $scope.employee)
                .then(
                    function successCallback(response){

                        $log.info(response.data);

                        if(response.data.success){
                            $scope.emplsaving = 'hidden';
                            $scope.emplsaved = '';
                            //$scope.initData();
                            $location.path('/edit/'+response.data.id).search('action=added');
                        }


                    },
                    function errorCallback(reason){

                    }
                )

        }

    }



}]);



app.config(function($routeProvider, $locationProvider) {

    $routeProvider.
    when('/', {
        templateUrl: '/templates/admin/super/employees/master.html',
        controller: 'EmployeesController'
    }).
    when('/add', {
        templateUrl: '/templates/admin/super/employees/new-employee.html',
        controller: 'NewEmployeeController'
    }).
    when('/edit/:id', {
        templateUrl: '/templates/admin/super/employees/edit-employee.html',
        controller: 'EditEmployeeController'
    });
    //when('/:id', {
    //    templateUrl: '/templates/stockroom.html',
    //    controller: 'StockRoomController'
    //}).
    //otherwise({redirectTo: '/'});
    //
    //$locationProvider.html5Mode({
    //    enabled: false,
    //    requireBase: false
    //});

});