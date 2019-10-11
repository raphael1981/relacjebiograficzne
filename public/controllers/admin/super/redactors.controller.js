var app = angular.module('app',['ngSanitize', 'ngRoute', 'ui.tinymce', 'xeditable', 'ui.bootstrap.datetimepicker','ngDialog','wysiwyg.module','ngFileUpload', 'ngTagsInput', 'ui.select'], function($interpolateProvider) {
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

app.directive('showPagination', function() {
    return {
        templateUrl: '/templates/admin/super/pagination.html'
    };
});

app.directive('showSearchCriteria', function() {
    return {
        templateUrl: '/templates/admin/super/redactors/search.html'
    };
});


app.directive('statusBtnInList', function() {
    return {
        templateUrl: '/templates/admin/super/redactors/status.html',
        link: function(scope, element, attributes, searchFactory){

            //console.log(attributes);
            scope.status = attributes.statusBtnInList;
            scope.id = attributes.redid;

        }
    };
});


app.directive('deleteElement', function(AppService, $http, $q, $filter, $route) {
    return {
        templateUrl: '/templates/admin/super/redactors/delete.html',
        link: function(scope, element, attributes, searchFactory){



            //console.log(attributes);
            scope.id = attributes.id;

            scope.relations = [
                {
                    method:'records',
                    name:'Nagrania'
                }

            ];

            scope.model = 'App\\Entities\\Redactor';

            scope.getBeforeDeleteRaport = function(){

                var deferred = $q.defer();

                $http.post(AppService.url+'/administrator/redactor/get/raport/before/delete', {relations:scope.relations, id:attributes.id})
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

                $http.put(AppService.url+'/administrator/delete/redactor', {relations:scope.relations, id:attributes.id, model:scope.model})
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


app.controller('GlobalController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout) {

    $timeout(function(){

        angular.element(document.getElementById('body')).removeClass('alphaHide');
        angular.element(document.getElementById('body')).addClass('alphaShow');

    },1000)

}]);


app.controller('RedactorsController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams) {


    $scope.initData = function(){

        $scope.limit = 10;
        $scope.start = 0;
        $scope.frase = null;
        $scope.searchcolumns = {
            name:true,
            surname:true,
            email:true,
            profession:true,
        };

        $scope.datainstock = [];
        $scope.calclass = [];

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


    $scope.getElements = function(iterstart){

        $http.post(AppService.url + '/administrator/get/redactors',
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


    /////////////////////////////////

    $scope.changeRedactorData = function(field, value, id){

        $http.put(AppService.url + '/administrator/update/redactor/data',
            {
                field:field,
                value:value,
                id:id
            }
            )
            .then(
                function successCallback(response) {

                    //console.log(response);
                    $scope.getElements($scope.iterstart);

                },
                function errorCallback(response) {

                }
            );

    }


}]);

app.controller('NewRedactorController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams', '$rootScope', 'ngDialog', '$sce', 'Upload', '$interval', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams,$rootScope, ngDialog, $sce, Upload,$interval) {


    $scope.initData = function(){

        $scope.red = {
            name:'',
            surname:'',
            email:'',
            profession:null,
            records:[],
        }

        $scope.valid = {
            name:false,
            surname:false
        }

        $scope.classes = {
            name:'',
            surname:''
        }

        $scope.records = null;
        $scope.professions = null;

        $scope.getListRecords();
        $scope.getListProfessions();


    }


    //$scope.$watch('$parent.windowWidth', function(oldV, newV){
    //    //console.log(oldV);
    //    //console.log(newV);
    //    console.log(Math.floor(angular.element(document.getElementById('canvasColId')).width()));
    //    $scope.canvas_width = Math.floor(angular.element(document.getElementById('canvasColId')).width());
    //
    //});

    $scope.getListRecords = function(){

        $http.get(AppService.url+'/get/all/records')
            .then(
                function successCallback(response){
                    //console.log(response.data);
                    $scope.records = response.data;
                },
                function errorCallback(reason){

                }
            )

    }

    $scope.getListProfessions = function(){

        $http.get(AppService.url+'/get/redactors/professions')
            .then(
                function successCallback(response){
                    console.log(response.data);
                    $scope.professions = response.data;
                    $scope.red.profession = $scope.professions[0];
                },
                function errorCallback(reason){

                }
            )

    }


   /////////////////////////////////////////////////////////////////////////////

    $scope.checkName = function(){

        if($scope.red.name.length>1){

            $scope.valid.name = true;
            $scope.classes.name = 'has-success';

        }else{

            $scope.valid.name = false;
            $scope.classes.name = 'has-error';
        }

    }


    $scope.checkSurname = function(){

        if($scope.red.surname.length>1){

            $scope.valid.surname = true;
            $scope.classes.surname = 'has-success';

        }else{

            $scope.valid.surname = false;
            $scope.classes.surname = 'has-error';
        }

    }


    $scope.createNewRedactor = function(){

        $scope.checkName();
        $scope.checkSurname();

        //console.log($scope.inter);

        if($filter('checkfalse')($scope.valid)){

            $scope.redsaving = '';

            $http.put(AppService.url+'/administrator/create/new/redactor', $scope.red)
                .then(
                    function successCallback(response){


                        $log.info(response.data);
                        if(response.data.success){
                            $scope.redsaving = 'hidden';
                            $scope.redsaved = '';
                            $scope.initData();
                        }


                    },
                    function errorCallback(reason){

                    }
                )

        }

    }


}]);


app.controller('EditRedactorController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams', '$rootScope', 'ngDialog', '$sce', 'Upload', '$interval', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams,$rootScope, ngDialog, $sce, Upload,$interval) {


    $scope.initData = function(){


        $scope.red = {
            id:$routeParams.id,
            name:'',
            surname:'',
            email:'',
            profession:null,
            records:[],
        }

        $scope.valid = {
            name:false,
            surname:false
        }

        $scope.classes = {
            name:'',
            surname:''
        }

        $scope.records = null;
        $scope.professions = null;



        $scope.getRedactorData()
            .then(
                function(response){

                    //console.log(response);
                    if(response==1) {
                        $scope.getListRecords();
                        $scope.getListProfessions();
                    }

                },
                function(reason){

                }
            );


    }


    $scope.getRedactorData = function(){

        var deferred = $q.defer();

        $http.get(AppService.url+'/administrator/get/redactor/'+$scope.red.id)
            .then(
            function successCallback(response){
                $log.info(response);
                $scope.red = response.data;
                deferred.resolve(1);
            },
            function errorCallback(reason){
                deferred.resolve(0);
            }
        );

        return deferred.promise;

    }


    $scope.getListRecords = function(){

        $http.get(AppService.url+'/get/all/records')
            .then(
                function successCallback(response){
                    //console.log(response.data);
                    $scope.records = response.data;
                },
                function errorCallback(reason){

                }
            )

    }

    $scope.getListProfessions = function(){

        $http.get(AppService.url+'/get/redactors/professions')
            .then(
                function successCallback(response){
                    console.log(response.data);
                    $scope.professions = response.data;
                    $scope.red.profession = {id:$scope.red.profession, name:$scope.red.profession};
                    console.log($scope.red.profession);
                },
                function errorCallback(reason){

                }
            )

    }



    /////////////////////////////////////////////////////////////////////////////

    $scope.checkName = function(){

        if($scope.red.name.length>1){

            $scope.valid.name = true;
            $scope.classes.name = 'has-success';

        }else{

            $scope.valid.name = false;
            $scope.classes.name = 'has-error';
        }

    }


    $scope.checkSurname = function(){

        if($scope.red.surname.length>1){

            $scope.valid.surname = true;
            $scope.classes.surname = 'has-success';

        }else{

            $scope.valid.surname = false;
            $scope.classes.surname = 'has-error';
        }

    }



    $scope.updateRedactor = function(){

        $scope.checkName();
        $scope.checkSurname();

        //console.log($scope.inter);

        if($filter('checkfalse')($scope.valid)){

            $scope.redsaving = '';

            $http.put(AppService.url+'/administrator/update/redactor/all', $scope.red)
                .then(
                    function successCallback(response){


                        $log.info(response.data);
                        if(response.data.success){
                            $scope.redsaving = 'hidden';
                            $scope.redsaved = '';
                            $scope.initData();
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
        templateUrl: '/templates/admin/super/redactors/master.html',
        controller: 'RedactorsController'
    }).when('/add', {
        templateUrl: '/templates/admin/super/redactors/new-redactor.html',
        controller: 'NewRedactorController'
    }).
    when('/edit/:id', {
        templateUrl: '/templates/admin/super/redactors/edit-redactor.html',
        controller: 'EditRedactorController'
    });
    //otherwise({redirectTo: '/'});
    //
    //$locationProvider.html5Mode({
    //    enabled: false,
    //    requireBase: false
    //});

});