var app = angular.module('app',['ngSanitize', 'ngRoute', 'ui.tinymce', 'xeditable', 'ui.bootstrap.datetimepicker','ngDialog','wysiwyg.module','ngFileUpload'], function($interpolateProvider) {
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

app.factory('DateService', function($location) {
    return {
        days_more : [
            1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31
        ],
        days_less : [
            1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30
        ],
        days_leap_feb : [
            1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29
        ],
        days_feb : [
            1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28
        ],
        months:
            [
                {value:1,name:'Styczeń',type:'more'},
                {value:2,name:'Luty',type:null},
                {value:3,name:'Marzec',type:'more'},
                {value:4,name:'Kwiecień',type:'less'},
                {value:5,name:'Maj',type:'more'},
                {value:6,name:'Czerwiec',type:'less'},
                {value:7,name:'Lipiec',type:'more'},
                {value:8,name:'Sierpień',type:'more'},
                {value:9,name:'Wrzesień',type:'less'},
                {value:10,name:'Październik',type:'more'},
                {value:11,name:'Listopad',type:'less'},
                {value:12,name:'Grudzień',type:'more'}
            ],
        createYearsFromTo: function(start, end){

            var years = [];

            for(var i=start;i<=end;i++){
                years.push(i);
            }

            return years;

        },
        checkIsLeapYear: function(rok){

            if(((rok % 4 == 0) && (rok % 100 != 0)) || (rok % 400 == 0)){
                return true;
            }else{
                return false;
            }
        }

    };
});

app.run(function(editableOptions) {
    editableOptions.theme = 'bs3'; // bootstrap3 theme. Can be also 'bs2', 'default'
});


app.directive('loadingData', function() {
    return {
        templateUrl: 'templates/overload.html'
    };
});


app.directive('showPaginationRoute', function() {
    return {
        templateUrl: '/templates/admin/super/pagination-route.html',
        controller: function($scope,$rootScope){

            $scope.goToPag = function(){
                if(/^[0-9]*$/.test($scope.gotopag)){

                    $rootScope.$broadcast('go-to-page',{nr:$scope.gotopag});

                }
            }

        }
    };
});


app.directive('showSearchCriteria', function() {
    return {
        templateUrl: '/templates/admin/super/interval/search.html'
    };
});


app.directive('createNewInterval', function(){
    return {
        templateUrl: '/templates/admin/super/interval/new-interval.html',
        controller: function($scope, $http, $rootScope, DateService, $q, AppService){

            $rootScope.$on('open-new-interval', function(event, args){
                $scope.intervaladd = 'active-add';
            });

            $scope.data = {
                name:'',
                begin:{
                    day:null,
                    month:null,
                    year:null
                },
                end:{
                    day:null,
                    month:null,
                    year:null
                }
            }

            $scope.is_some_error_add = false;
            $scope.is_data_not_much = false;
            $scope.is_intervals_exits = false;


            $scope.days_more = DateService.days_more;
            $scope.days_less = DateService.days_less;
            $scope.days_leap_feb = DateService.days_leap_feb;
            $scope.days_feb = DateService.days_feb;

            $scope.begin_vars = {};
            $scope.end_vars = {};

            $scope.begin_vars.days = $scope.days_more;

            $scope.begin_vars.months = DateService.months;

            $scope.begin_vars.years = DateService.createYearsFromTo(1900,new Date().getFullYear());

            $scope.is_year_exist_begin = false;

            $scope.is_month_exist_begin = false;



            $scope.end_vars.days = $scope.days_more;

            $scope.end_vars.months = DateService.months;

            $scope.end_vars.years = DateService.createYearsFromTo(1900,new Date().getFullYear());

            $scope.is_year_exist_end = false;

            $scope.is_month_exist_end = false;



            $scope.clearIntervalFields = function(){

                $scope.data = {
                    name:'',
                    begin:{
                        day:null,
                        month:null,
                        year:null
                    },
                    end:{
                        day:null,
                        month:null,
                        year:null
                    }
                }

                $scope.is_some_error_add = false;
                $scope.is_data_not_much = false;
                $scope.is_intervals_exits = false;

            }


            $scope.$watch('data.begin.year', function(nVal,oVal){

                if(nVal!=undefined && nVal!=null){
                    $scope.is_year_exist_begin = true;
                    $scope.check_is_leap_year_begin = DateService.checkIsLeapYear($scope.data.begin.year);
                    if($scope.is_month_exist_begin){
                        $scope.begin_vars.days = $scope.checkHowManyDaysMonth($scope.data.begin.month,$scope.check_is_leap_year_begin);
                    }
                }else{
                    $scope.is_year_exist_begin = false;
                }

            });


            $scope.$watch('data.begin.month', function(nVal,oVal){


                if(nVal!=undefined && nVal!=null){
                    $scope.is_month_exist_begin = true;
                    $scope.begin_vars.days = $scope.checkHowManyDaysMonth($scope.data.begin.month,$scope.check_is_leap_year_begin);
                }else{
                    $scope.is_month_exist_begin = false;
                }

            });


            $scope.$watch('data.end.year', function(nVal,oVal){

                if(nVal!=undefined && nVal!=null){
                    $scope.is_year_exist_end = true;
                    $scope.check_is_leap_year_end = DateService.checkIsLeapYear($scope.data.end.year);
                    if($scope.is_year_exist_end){
                        $scope.end_vars.days = $scope.checkHowManyDaysMonth($scope.data.end.month,$scope.check_is_leap_year_end);
                    }
                }else{
                    $scope.is_year_exist_end = false;
                }

            });


            $scope.$watch('data.end.month', function(nVal,oVal){

                if(nVal!=undefined && nVal!=null){
                    $scope.is_month_exist_end = true;
                    $scope.end_vars.days = $scope.checkHowManyDaysMonth($scope.data.end.month,$scope.check_is_leap_year_end);
                }else{
                    $scope.is_month_exist_end = false;
                }

            });


            $scope.checkHowManyDaysMonth = function(month,is_leap){

                var days = null;

                if(DateService.months[month-1].type=='more'){

                    days = DateService.days_more;

                }else if(DateService.months[month-1].type=='less'){

                    days = DateService.days_less;

                }else if(DateService.months[month-1].type==null){

                    if(is_leap){
                        days = DateService.days_leap_feb;
                    }else{
                        days = DateService.days_feb;
                    }

                }

                return days;
            }


            $scope.checkIsSomeIntervalExits = function(){

                var deffered = $q.defer();

                $http.post(AppService.url+'/administrator/check/is/interval/exists', {data:$scope.data})
                    .then(function(response){

                        deffered.resolve(response.data);
                    })

                return deffered.promise;

            }


            $scope.addIntervalToBase = function(){

                $http.post(AppService.url+'/administrator/add/not/linked/new/interval',{data:$scope.data})
                    .then(function(res){
                        location.reload();
                    })

            }


            $scope.startAddInterval = function(){

                if($scope.data.begin.year!=null && $scope.data.begin.year!=undefined){
                    $scope.is_data_not_much = false;
                    $scope.checkIsSomeIntervalExits()
                        .then(function(res){
                            //console.log(res.intervals);
                            if(res.intervals.length>0){
                                //console.log(res.intervals);
                                $scope.intervals = res.intervals;
                                $scope.is_some_error_add = true;
                                $scope.is_intervals_exits = true;
                            }else{
                                $scope.addIntervalToBase();
                            }
                        })
                }else{
                    $scope.is_some_error_add = true;
                    $scope.is_data_not_much = true;
                    $scope.is_intervals_exits = false;
                }

            }


        }
    };
});


app.directive('deleteElement', function(AppService, $http, $q, $filter, $route) {
    return {
        templateUrl: '/templates/admin/super/interval/delete.html',
        link: function(scope, element, attributes, searchFactory){



            //console.log(attributes);
            scope.id = attributes.id;

            scope.relations = [
                {
                    method:'fragments',
                    name:'Fragmenty',
                    extra: {method:'record',field:'title',key:'title'}

                }

            ];

            scope.model = 'App\\Entities\\Interval';

            scope.getBeforeDeleteRaport = function(){

                var deferred = $q.defer();

                $http.post(AppService.url+'/administrator/interval/get/raport/before/delete', {relations:scope.relations, id:attributes.id})
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

                $http.put(AppService.url+'/administrator/delete/interval', {relations:scope.relations, id:attributes.id, model:scope.model})
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


app.filter('checkfalsearray', function() { return function(array) {

    if (!(array instanceof Array)) return false;

    var bool = true;

    for(var i=0;i<array.length;i++){
        if(!array[i]){
            bool  = false;
        }
    }

    return bool;
}});


app.filter('dateformat', function() { return function(str) {

    var d = Date.parse(str);

    //console.log(d);

    return d;
}});

app.filter('splitdate', function() { return function(obj) {

    var dates = obj;

   if(obj.start!=null){
       var dstart = obj.start;
       console.log(dstart);
       dates.start = dstart;
   }

    if(obj.end!=null){

        var dend = obj.end;
        console.log(dend);
        dates.end = dend;

    }

    return dates;
}});


app.controller('GlobalController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout', 'ngDialog', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout, ngDialog) {

    $timeout(function(){

        angular.element(document.getElementById('body')).removeClass('alphaHide');
        angular.element(document.getElementById('body')).addClass('alphaShow');

    },500)

}]);


app.controller('IntervalsController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams','$rootScope', 'ngDialog', '$sce', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams,$rootScope,ngDialog,$sce) {


    $scope.initData = function(){

        $scope.limit = 10;
        $scope.tag = '';

        $scope.date = {
            start:null,
            end:null
        };

        if($routeParams.start || $routeParams.iterstart){


            $scope.basename = [];
            $scope.frase = null;
            $scope.start = parseInt($routeParams.start);

            if($routeParams.halfclick){
                $scope.halfclick = JSON.parse($routeParams.halfclick);
            }else{
                $scope.halfclick = false;
            }

            $scope.iterstart = parseInt($routeParams.iterstart);
            $scope.getElements($routeParams.iterstart);

            //console.log($routeParams);

            if($routeParams.frase && $routeParams.frase!=''){
                console.log('frase-exist',$routeParams.frase);
                $scope.frase = $routeParams.frase;
            }else{
                $scope.frase = null;
            }

            if($routeParams.begin){
                $scope.date.start = new Date(Date.parse($routeParams.begin));
            }

            if($routeParams.end){
                $scope.date.end = new Date(Date.parse($routeParams.end));
            }

            $scope.getElements($routeParams.iterstart);

        }else{
            $scope.basename = [];
            $scope.start = 0;

            if($routeParams.halfclick){
                $scope.halfclick = JSON.parse($routeParams.halfclick);
            }else{
                $scope.halfclick = false;
            }

            if($routeParams.frase && $routeParams.frase!=''){
                $scope.frase = $routeParams.frase;
            }else{
                $scope.frase = null;
            }

            if($routeParams.begin){
                $scope.date.start = new Date(Date.parse($routeParams.begin));
            }

            if($routeParams.end){
                $scope.date.end = new Date(Date.parse($routeParams.end));
            }

            $scope.getElements(0);
        }

    }


    $scope.getElements = function(iterstart){

        console.log($scope.frase);

        if(iterstart) {
            iterstart = parseInt(iterstart);
        }

        $http.post(AppService.url + '/administrator/get/intervals',
            {
                start:$scope.start,
                limit:$scope.limit,
                frase:$scope.frase,
                date:$filter('splitdate')($scope.date)
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


    $rootScope.$on('go-to-page',function(event,attr){

        //console.log(attr);
        //console.log($routeParams);
        $scope.halfclick = false;
        $scope.iterstart = attr.nr-1;
        $scope.start = $scope.limit*$scope.iterstart;

        var search = {start:$scope.start,iterstart:$scope.iterstart,halfclick:$scope.halfclick};

        if($scope.frase!=null || $scope.frase!=''){
            search.frase = $scope.frase;
        }

        if($scope.date.start!=null){
            search.begin = $scope.dateToStringUrl($scope.date.start);
        }

        if($scope.date.end!=null){
            search.end = $scope.dateToStringUrl($scope.date.end);
        }

        $location.path('/').search(search);

    });


    $scope.changePage = function(start){

        $scope.halfclick = false;
        $scope.start = $scope.limit*start;

        var search = {start:$scope.start,iterstart:start,halfclick:$scope.halfclick};

        if($scope.frase!=null || $scope.frase!=''){
            search.frase = $scope.frase;
        }

        if($scope.date.start!=null){
            search.begin = $scope.dateToStringUrl($scope.date.start);
        }

        if($scope.date.end!=null){
            search.end = $scope.dateToStringUrl($scope.date.end);
        }

        $location.path('/').search(search);


    }


    $scope.goToHalf = function(){

        $scope.halfclick = true;
        $scope.iterstart = $scope.start;
        $scope.start = $scope.half*$scope.limit;

        //$scope.getElements($scope.start);

        var search = {start:$scope.start,halfclick:$scope.halfclick.toString(),iterstart:$scope.half};

        if($scope.frase!=null || $scope.frase!=''){
            search.frase = $scope.frase;
        }

        if($scope.date.start!=null){
            search.begin = $scope.dateToStringUrl($scope.date.start);
        }

        if($scope.date.end!=null){
            search.end = $scope.dateToStringUrl($scope.date.end);
        }

        $location.path('/').search(search);


    }


    $scope.changePagePrev = function(){

        $scope.halfclick = false;
        var start = $scope.iterstart-1;
        $scope.start = $scope.limit*start;
        //$scope.getElements(start);

        var search = {start:$scope.start,iterstart:start,halfclick:$scope.halfclick};

        if($scope.frase!=null || $scope.frase!=''){
            search.frase = $scope.frase;
        }

        if($scope.date.start!=null){
            search.begin = $scope.dateToStringUrl($scope.date.start);
        }

        if($scope.date.end!=null){
            search.end = $scope.dateToStringUrl($scope.date.end);
        }

        $location.path('/').search(search);


    }


    $scope.changePageNext = function(){

        $scope.halfclick = false;
        var start = $scope.iterstart+1;
        $scope.start = $scope.limit*start;
        //$scope.getElements(start);
        var search = {start:$scope.start,iterstart:start,halfclick:$scope.halfclick};

        if($scope.frase!=null || $scope.frase!=''){
            search.frase = $scope.frase;
        }

        if($scope.date.start!=null){
            search.begin = $scope.dateToStringUrl($scope.date.start);
        }

        if($scope.date.end!=null){
            search.end = $scope.dateToStringUrl($scope.date.end);
        }

        $location.path('/').search(search);

    }


    $scope.changeShowFirst = function(){

        $scope.start = 0;
        //$scope.getElements(0);

        var search = {start:$scope.start,halfclick:$scope.halfclick,iterstart:$scope.start};

        if($scope.frase!=null || $scope.frase!=''){
            search.frase = $scope.frase;
        }

        if($scope.date.start!=null){
            search.begin = $scope.dateToStringUrl($scope.date.start);
        }

        if($scope.date.end!=null){
            search.end = $scope.dateToStringUrl($scope.date.end);
        }
        $location.path('/').search(search);

    }

    $scope.changeShowLast = function(){

        $scope.start = $scope.pages*$scope.limit;
        //$scope.getElements($scope.pages);
        var search = {start:$scope.start,halfclick:$scope.halfclick,iterstart:$scope.start};

        if($scope.frase!=null || $scope.frase!=''){
            search.frase = $scope.frase;
        }

        if($scope.date.start!=null){
            search.begin = $scope.dateToStringUrl($scope.date.start);
        }

        if($scope.date.end!=null){
            search.end = $scope.dateToStringUrl($scope.date.end);
        }
        $location.path('/').search(search);

    }


    //Get Data Logic



    $scope.searchSubmit = function(){

        console.log($scope.date);
        var dstart_string = null;
        if($scope.date.start!=null){
            dstart_string = $scope.dateToStringUrl($scope.date.start);
        }

        var dend_string = null;
        if($scope.date.end!=null){
            dend_string = $scope.dateToStringUrl($scope.date.end);
        }


        $scope.start = 0;
        $location.path('/').search({frase:$scope.frase,halfclick:$scope.halfclick,iterstart:$scope.start,begin:dstart_string,end:dend_string});
        //$scope.getElements(0);


    }



    $scope.dateToStringUrl = function(dt){
        var obj = new Date(dt);
        return obj.getFullYear()+'.'+(obj.getMonth()+1)+'.'+obj.getDate();
    }



    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    $scope.openNewIntervalElement = function(){

        $rootScope.$broadcast('open-new-interval', {});

    }



}]);


app.config(function($routeProvider, $locationProvider) {

    $routeProvider.
    when('/', {
        templateUrl: '/templates/admin/super/interval/master.html',
        controller: 'IntervalsController'
    });
    //when('/add', {
    //    templateUrl: '/templates/admin/super/articles/new_article.html',
    //    controller: 'NewArticleController'
    //}).
    //when('/edit/:id', {
    //    templateUrl: '/templates/admin/super/articles/edit_article.html',
    //    controller: 'EditArticleController'
    //});
    //otherwise({redirectTo: '/'});
    //
    $locationProvider.html5Mode({
        enabled: false,
        requireBase: false
    });

});