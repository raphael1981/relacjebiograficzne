var app = angular.module('app',['ngSanitize','ngRoute','ngAnimate','perfect_scrollbar','ngPhotoSwipe','ngCookies'], function($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});

app.factory('AppService', function($location) {
    return {
        url: $location.protocol() + '://' + $location.host(),
    }
});

app.factory('AlphabetService', function($http,$q,AppService) {

    return {

        getAlphabetByIndex: function(){

            var deffer = $q.defer();

            $http.get(AppService.url+'/ajax/get/alphabet')
                .then(function(response){
                    deffer.resolve(response.data);
                });

            return deffer.promise;

        }
    }

});




app.factory('DateService', function($location) {
    return {
        days_more : {
            first:1,
            last:31
        },
        days_less : {
            first:1,
            last:30
        },
        days_leap_feb : {
            first:1,
            last:29
        },
        days_feb : {
            first:1,
            last:28
        },
        months:
            [
                {type:'more'},
                {type:null},
                {type:'more'},
                {type:'less'},
                {type:'more'},
                {type:'less'},
                {type:'more'},
                {type:'more'},
                {type:'less'},
                {type:'more'},
                {type:'less'},
                {type:'more'}
            ],

        months_array:[

            {index:0,number:1,month_string:"01"},
            {index:1,number:2,month_string:"02"},
            {index:2,number:3,month_string:"03"},
            {index:3,number:4,month_string:"04"},
            {index:4,number:5,month_string:"05"},
            {index:5,number:6,month_string:"06"},
            {index:6,number:7,month_string:"07"},
            {index:7,number:8,month_string:"08"},
            {index:8,number:9,month_string:"09"},
            {index:9,number:10,month_string:"10"},
            {index:10,number:11,month_string:"11"},
            {index:11,number:12,month_string:"12"}

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
        },

        makeDaysCollectionByLeap: function(is_leap){
            var days_count = [];
            angular.forEach(this.months, function(item,i){

                if(is_leap){
                    if(item.type==null) {
                        days_count.push({
                            days:29
                        });
                    }else{
                        days_count.push({
                            days:(item.type=='more')?31:30
                        });
                    }
                }else{
                    if(item.type==null) {
                        days_count.push({
                            days:28
                        });
                    }else{
                        days_count.push({
                            days:(item.type=='more')?31:30
                        });
                    }
                }

            });

            return days_count;

        },

        isDateOkToSearch: function(dates){

            var bool = false;

            if(dates.date.begin.year.length==4 || dates.date.end.year.length==4){
                bool = true;
            }else{
                bool = false;
            }

            return bool;

        }
    };
});

app.factory('IndexElementsService', function($location,$http,$q,AppService) {
    return {
        getTagObject: function(id){

            var deffer = $q.defer();

            $http.get(AppService.url+'/get/tag/by/id/'+id)
                .then(function(res){
                    deffer.resolve(res.data);
                })

            return deffer.promise;

        },
        getPlaceObject: function(id){

            var deffer = $q.defer();

            $http.get(AppService.url+'/get/place/by/id/'+id)
                .then(function(res){
                    deffer.resolve(res.data);
                })

            return deffer.promise;

        },
        getIntervalObject: function(id){

            var deffer = $q.defer();

            $http.get(AppService.url+'/get/interval/by/id/'+id)
                .then(function(res){
                    deffer.resolve(res.data);
                })

            return deffer.promise;

        }
    }
})

app.filter('create_days_array', function(){
    return function(d_count){

        d_collect = [];

        for (var i=0;i<d_count;i++){
            d_collect.push({
                index:i,
                day:i+1,
                day_string:((i+1)>9)? (i+1).toString():"0"+(i+1)
            })
        }

        return d_collect;
    }
})


app.directive('indexPlaceCloud', function() {
    return {
        templateUrl: '/templates/front/elasticsearch/search/parts/index-places.html',
        controller: ['$scope', '$http', 'AppService', 'DateService', '$filter', 'AlphabetService', function ($scope, $http, AppService, DateService, $filter, AlphabetService) {

            $scope.$on('init-place-data', function(){

                $scope.initData();

            });

            $scope.initData = function(){

                $scope.alphabet = [];
                $scope.places = [];

                AlphabetService.getAlphabetByIndex()
                    .then(
                        function(response){
                            $scope.alphabet = response;
                            $scope.getPlacesByLetter($scope.alphabet[0]);
                        }
                    )
            }

            $scope.getPlacesByLetter = function(letter){

                $http.post(AppService.url+'/ajax/get/places/by/letter',{letter:letter})
                    .then(
                        function(response){
                            $scope.places = $filter('cut-collection-on-half')(response.data);
                        }
                    )
            }


            $scope.setPlaceToSearch = function(place){
                $scope.indexdata.place = place;
                $scope.not_empty_index.place = true;
                $scope.indexview.interval = 'hidden';
                $scope.indexview.places = 'hidden';
                $scope.indexview.tags = 'hidden';
                $scope.searchDelegate();
                //$scope.search('new');
                //$scope.$broadcast('route-update',{type:"place",data:$scope.indexdata.place});
            }

            $scope.clearClosePlace = function(){
                $scope.indexdata.place = null;
                $scope.not_empty_index.place = false;

                $scope.indexview.interval = 'hidden';
                $scope.indexview.places = 'hidden';
                //$scope.indexview.tags = 'hidden';
                //$scope.search('new');
                $scope.searchDelegate();
            }



        }]
    }

});


app.directive('indexTagsCloud', function() {
    return {
        templateUrl: '/templates/front/elasticsearch/search/parts/index-tags.html',
        controller: ['$scope', '$http', 'AppService', 'DateService', '$filter', 'AlphabetService', '$location', '$routeParams', function ($scope, $http, AppService, DateService, $filter, AlphabetService,$location,$routeParams) {

            $scope.$on('init-tags-data', function(){

                $scope.initData();

            });


            $scope.initData = function(){

                $scope.alphabet = [];
                $scope.tags = [];

                AlphabetService.getAlphabetByIndex()
                    .then(
                        function(response){
                            $scope.alphabet = response;
                            $scope.getTagsByLetter($scope.alphabet[0]);
                        }
                    )
            }


            $scope.getTagsByLetter = function(letter){

                console.log(letter);

                $http.post(AppService.url+'/ajax/get/tags/by/letter',{letter:letter})
                    .then(
                        function(response){
                            //console.log(response.data);
                            $scope.tags = $filter('cut-collection-on-half')(response.data);
                        }
                    )

            }

            $scope.setTagToSearch = function(tag){

                //$location.path('/i').search('tag='+tag.id);
                $scope.indexdata.tag = tag;
                $scope.not_empty_index.tag = true;
                $scope.indexview.interval = 'hidden';
                $scope.indexview.places = 'hidden';
                $scope.indexview.tags = 'hidden';
                $scope.searchDelegate();
                //$scope.$broadcast('route-update',{type:"tag",data:$scope.indexdata.tag});
                //$scope.indexview.tags = 'hidden';
                //$scope.search('new');
            }

            $scope.clearCloseTag = function(){
                $scope.indexdata.tag = null;
                $scope.not_empty_index.tag = false;

                $scope.indexview.interval = 'hidden';
                $scope.indexview.places = 'hidden';
                $scope.indexview.tags = 'hidden';
                //$scope.search('new');
                $scope.searchDelegate();
            }



        }]
    }

});

app.directive('indexFormShow', function(){
    return {
        templateUrl:'/templates/front/elasticsearch/search/parts/index-form.html',
        controller: ['$scope', '$rootScope', '$http','AppService','DateService','$filter','$location', '$routeParams','IndexElementsService','$q','$timeout',function($scope,$rootScope,$http,AppService,DateService,$filter,$location,$routeParams,IndexElementsService,$q,$timeout) {

            $scope.not_empty_index = {
                interval:false,
                place:false,
                tag:false
            }

            $scope.indexview = {
                interval: 'hidden',
                places: 'hidden',
                tags: 'hidden'
            }


            //$scope.$on('index-search-next', function(event,attr){
            //    //console.log(attr);
            //    $scope.indexdata.from=attr.from;
            //    $scope.search();
            //});

            $scope.$on('show-date-view', function(event,attr){
                $scope.initData();
                $scope.indexview.interval = '';
                $scope.indexview.places = 'hidden';
                $scope.indexview.tags = 'hidden';
            });

            $scope.$on('show-places-view', function(event,attr){
                $scope.indexview.interval = 'hidden';
                $scope.indexview.places = '';
                $scope.indexview.tags = 'hidden';
                $scope.$broadcast('init-place-data',{});
            });


            $scope.$on('show-tags-view', function(event,attr){
                $scope.indexview.interval = 'hidden';
                $scope.indexview.places = 'hidden';
                $scope.indexview.tags = '';
                $scope.$broadcast('init-tags-data',{});
            });


            $scope.initData = function() {

                $scope.begin =
                {
                    is_leaf:null,
                    days_array:null,
                    current_days_array:null

                }

                $scope.end =
                {
                    is_leaf:null,
                    days_array:null,
                    current_days_array:null
                }


                $scope.config = {
                    date: {
                        begin: {
                            year: true,
                            month: false,
                            day: false
                        }
                    }
                }


                $scope.initByParams();


                $scope.switch_select = {
                    begin:{
                        months:'hidden',
                        days:'hidden'
                    },
                    end:{
                        months:'hidden',
                        days:'hidden'
                    }
                }

                $scope.is_year_exist = {
                    begin:false,
                    end:false
                }

                $scope.months = DateService.months_array;

            }


            $scope.initByParams = function(){


                if($routeParams.length==0) {

                    $scope.indexdata = {
                        date: {
                            begin: {
                                year: "",
                                month: "",
                                day: ""
                            },
                            end: {
                                year: "",
                                month: "",
                                day: ""
                            }
                        },
                        place: null,
                        tag: null,
                        size: 1000,
                        from: 0
                    }

                }else{

                    $scope.indexdata = {
                        date: {
                            begin: {
                                year: "",
                                month: "",
                                day: ""
                            },
                            end: {
                                year: "",
                                month: "",
                                day: ""
                            }
                        },
                        place: null,
                        tag: null,
                        size: 1000,
                        from: 0
                    }

                    if($routeParams.begin){
                        var begin = $routeParams.begin.split(':');
                        $scope.indexdata.date.begin.year = begin[0];
                        $scope.indexdata.date.begin.month = begin[1];
                        $scope.indexdata.date.begin.day = begin[2];
                        $scope.not_empty_index.interval = true;
                    }

                    if($routeParams.end){
                        var end = $routeParams.end.split(':');
                        $scope.indexdata.date.end.year = end[0];
                        $scope.indexdata.date.end.month = end[1];
                        $scope.indexdata.date.end.day = end[2];
                        $scope.not_empty_index.interval = true;
                    }

                    if($routeParams.place){
                        IndexElementsService.getPlaceObject($routeParams.place)
                            .then(function(res){
                                $scope.indexdata.place= res;
                                $scope.not_empty_index.place = true;
                            });

                    }

                    if($routeParams.tag){
                        IndexElementsService.getTagObject($routeParams.tag)
                            .then(function(res){
                                $scope.indexdata.tag = res;
                                $scope.not_empty_index.tag = true;
                            });

                    }

                    if($routeParams.interval){
                        $scope.indexdata.interval = $routeParams.interval;
                        $scope.not_empty_index.interval = true;
                    }

                }

                if(!$routeParams.place && !$routeParams.tag && !$routeParams.interval){
                    $scope.$broadcast('search-init-from-route',{});
                }else{
                    $timeout(function(){
                        $scope.$broadcast('search-init-from-route',{});
                    },1000);
                }


            }


            $scope.resetSwitch = function(){

                $scope.switch_select = {
                    begin:{
                        months:'hidden',
                        days:'hidden'
                    },
                    end:{
                        months:'hidden',
                        days:'hidden'
                    }
                }
            }

            $scope.$watch('indexdata.date.begin.year', function(nVal,oVal){

                if(nVal!=undefined) {

                    $scope.resetSwitch();

                    if (nVal.length == 4) {

                        if ($scope.indexdata.date.begin.month != "") {
                            $scope.begin.is_leaf = DateService.checkIsLeapYear(parseInt($scope.indexdata.date.begin.year));
                            $scope.begin.days_array = DateService.makeDaysCollectionByLeap($scope.begin.is_leaf);
                            //$scope.createCurrentMonthDays('begin');
                        }
                        $scope.is_year_exist.begin = true;

                        //$scope.search('new');
                    } else {
                        $scope.is_year_exist.begin = false;
                    }

                    //$scope.checkIsSomeIntervalExits();

                }

            });

            $scope.$watch('indexdata.date.begin.month', function(nVal,oVal){

                if(nVal!=undefined) {

                    if ($scope.indexdata.date.begin.month != "") {

                        $scope.begin.is_leaf = DateService.checkIsLeapYear(parseInt($scope.indexdata.date.begin.year));
                        $scope.begin.days_array = DateService.makeDaysCollectionByLeap($scope.begin.is_leaf);
                        $scope.createCurrentMonthDays('begin');
                    }

                    //$scope.checkIsSomeIntervalExits();

                }

            });


            $scope.$watch('indexdata.date.begin.day', function(nVal,oVal){

                if(nVal!=undefined) {

                    if ($scope.indexdata.date.begin.day != "") {

                    }

                    //$scope.checkIsSomeIntervalExits();

                }

            });

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////

            $scope.$watch('indexdata.date.end.year', function(nVal,oVal){

                if(nVal!=undefined) {

                    $scope.resetSwitch();

                    if (nVal.length == 4) {
                        if ($scope.indexdata.date.end.month != "") {
                            $scope.end.is_leaf = DateService.checkIsLeapYear(parseInt($scope.indexdata.date.end.year));
                            $scope.end.days_array = DateService.makeDaysCollectionByLeap($scope.end.is_leaf);
                            //$scope.createCurrentMonthDays('end');
                        }
                        $scope.is_year_exist.end = true;
                    } else {
                        $scope.is_year_exist.end = false;
                    }

                    //$scope.checkIsSomeIntervalExits();

                }

            });


            $scope.$watch('indexdata.date.end.month', function(nVal,oVal){


                if(nVal!=undefined) {

                    if ($scope.indexdata.date.end.month != "") {
                        $scope.end.is_leaf = DateService.checkIsLeapYear(parseInt($scope.indexdata.date.end.year));
                        $scope.end.days_array = DateService.makeDaysCollectionByLeap($scope.end.is_leaf);
                        $scope.createCurrentMonthDays('end');
                    }

                    //$scope.checkIsSomeIntervalExits();

                }

            });


            $scope.$watch('indexdata.date.end.day', function(nVal,oVal){

                //$scope.checkIsSomeIntervalExits();

            });


            $scope.createCurrentMonthDays = function(what){

                switch (what){

                    case 'begin':

                        $scope.begin.current_days = $scope.begin.days_array[(parseInt($scope.indexdata.date.begin.month) - 1)];
                        $scope.begin.current_days_array = $filter('create_days_array')($scope.begin.current_days.days);
                        break;

                    case 'end':
                        $scope.end.current_days = $scope.end.days_array[(parseInt($scope.indexdata.date.end.month) - 1)];
                        $scope.end.current_days_array = $filter('create_days_array')($scope.end.current_days.days);
                        break;

                }


            }



            $scope.showSelectCloud = function(what){

                if($scope.is_year_exist.begin) {


                    switch (what) {

                        case 'begin.months':


                            if($scope.switch_select.begin.months=='hidden') {
                                $scope.resetSwitch();
                                $scope.switch_select.begin.months = '';
                            }else{
                                $scope.resetSwitch();
                                $scope.switch_select.begin.months = 'hidden';
                            }

                            break;

                        case 'begin.days':

                            if($scope.switch_select.begin.days=='hidden') {
                                $scope.resetSwitch();
                                $scope.switch_select.begin.days = '';
                            }else{
                                $scope.resetSwitch();
                                $scope.switch_select.begin.days = 'hidden';
                            }

                            break;

                    }

                }

                if($scope.is_year_exist.end) {

                    switch (what) {


                        case 'end.months':


                            if($scope.switch_select.end.months == 'hidden') {
                                $scope.resetSwitch();
                                $scope.switch_select.end.months = '';
                            }else{
                                $scope.resetSwitch();
                                $scope.switch_select.end.months = 'hidden';
                            }

                            break;

                        case 'end.days':

                            if($scope.switch_select.end.days == 'hidden') {
                                $scope.resetSwitch();
                                $scope.switch_select.end.days = '';
                            }else{
                                $scope.resetSwitch();
                                $scope.switch_select.end.days = 'hidden';
                            }

                            break;

                    }

                }



            }


            $scope.changeMonth = function(what,object){


                switch (what){

                    case 'begin':

                        $scope.indexdata.date.begin.month = object.month_string;

                        break;

                    case 'end':

                        $scope.indexdata.date.end.month = object.month_string;

                        break;

                }

            }


            $scope.changeDay = function(what,object){

                switch (what){

                    case 'begin':

                        $scope.indexdata.date.begin.day = object.day_string;

                        break;

                    case 'end':

                        $scope.indexdata.date.end.day = object.day_string;

                        break;

                }


            }


            $scope.getIntervalsByLetter = function(l){

                $http.post(AppService.url+'/ajax/get/intervals/by/letter', {letter:l})
                    .then(
                        function(res){
                            $scope.intervals = $filter('cut-collection-on-half')(res.data);
                        }
                    )

            }


            $scope.checkIsSomeIntervalExits = function(){

                if(DateService.isDateOkToSearch($scope.indexdata)){
                    $scope.not_empty_index.interval = true;
                }else{
                    $scope.not_empty_index.interval = false;
                }

            }

            $scope.clearCloseDate = function(){
                $scope.indexview.interval = 'hidden';
                $scope.indexview.places = 'hidden';
                $scope.indexview.tags = 'hidden';
                $scope.indexdata.date.begin.year = "";
                $scope.indexdata.date.begin.month = "";
                $scope.indexdata.date.begin.day = "";
                $scope.indexdata.date.end.year = "";
                $scope.indexdata.date.end.month = "";
                $scope.indexdata.date.end.day = "";
                $scope.searchDelegate();
            }

            //////////////////////////////////////////////////////////////////////////////////////////////////////////


        }]
    }
})


app.directive('menuSearch', function(){
    return {
        templateUrl: '/templates/front/elasticsearch/search/parts/menu.html',
        controller: ['$scope', '$rootScope', '$http', 'AppService', '$filter', '$location', '$routeParams', function ($scope, $rootScope, $http, AppService, $filter, $location, $routeParams) {

            $scope.changeTextSearchType = function(type){


                if($rootScope.search_type.length==1){

                    $rootScope.search_type = 'ti';

                }else{

                    $rootScope.search_type = type;

                }


            }

        }]
    }
})


app.filter('cut-collection-on-half',function(){

    return function (collection){

        var object = {
            first:null,
            second:null
        }


        var lng = collection.length;

        if(lng==0 || lng==1){

            object.first = collection;

        }else{

            var div = lng/2;
            var half_more = Math.ceil(div);
            var half_less = lng-half_more;

            object.first = collection.slice(0,half_more);
            object.second = collection.slice(half_more,lng);

        }


        return object;

    }

})

app.filter('check_is_fragment',function(){
    return function (collection, fr){

        var is_in = false;

        angular.forEach(collection, function(item,i){
            if(item.fid==fr.fid){
                is_in = true;
            }
        });

        return is_in;

    }
});


app.controller('SearchIndexController',['$scope', '$rootScope', '$http', '$log', '$q', '$location', '$window', '$filter', '$timeout', 'AppService','$routeParams','DateService', function($scope, $rootScope, $http, $log, $q, $location, $window, $filter, $timeout, AppService,$routeParams,DateService) {

    $scope.initData = function(){

        $scope.search_start = false;
        $scope.params_string = '';

        $scope.show_next_button = false;
        $rootScope.search_process_svg = false;

        $scope.viewtype_hide = "phrase_search";

        $rootScope.response_data = null;

        $scope.pag = {
            //size:50,
            size:3,
            from:0
        }

        $scope.total = null;
        $scope.pagination = null;
        $scope.pag_index = 0;
        $scope.last_pags_index = 0;

        if(!$scope.data) {
            $scope.data = {
                result: null
            }
        }

        $rootScope.search_process = false;

    }


    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $scope.showDateView = function(){
        if($scope.indexview.interval==''){
            $scope.indexview.interval='hidden';
            $scope.indexview.places='hidden';
            $scope.indexview.tags='hidden';
        }else{
            $scope.$broadcast('show-date-view',{});
        }

    }

    $scope.showPlaceView = function(){
        if($scope.indexview.places==''){
            $scope.indexview.interval='hidden';
            $scope.indexview.places='hidden';
            $scope.indexview.tags='hidden';
        }else{
            $scope.$broadcast('show-places-view',{});
        }
    }

    $scope.showTagsView = function(){
        if($scope.indexview.tags==''){
            $scope.indexview.interval='hidden';
            $scope.indexview.places='hidden';
            $scope.indexview.tags='hidden';
        }else{
            $scope.$broadcast('show-tags-view',{});
        }
    }


    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $scope.searchDelegate = function(){


        var params='';

        if(
            $scope.indexdata.date.begin.year==''
            &&
            $scope.indexdata.date.begin.month==''
            &&
            $scope.indexdata.date.begin.day==''
            &&
            $scope.indexdata.date.end.year==''
            &&
            $scope.indexdata.date.begin.month==''
            &&
            $scope.indexdata.date.begin.day==''
        ) {


        }else{

            params = 'begin=' + $scope.indexdata.date.begin.year
                + ':' + $scope.indexdata.date.begin.month
                + ':' + $scope.indexdata.date.begin.day
                + '&' +
                'end=' + $scope.indexdata.date.end.year
                + ':' + $scope.indexdata.date.end.month
                + ':' + $scope.indexdata.date.end.day;

        }

        if($scope.indexdata.place!=null) {
            if($scope.indexdata.place.id!=undefined) {
                params += '&place=' + $scope.indexdata.place.id
            }else{
                params += '&place=' + $routeParams.place;
            }
        }


        if($scope.indexdata.tag!=null) {

            if($scope.indexdata.tag.id!=undefined) {
                params += '&tag='+$scope.indexdata.tag.id
            }else{
                params += '&tag=' + $routeParams.tag;
            }
        }

        $scope.params_string = params;
        $location.path('/i').search(params);


    }

    $scope.$on('search-init-from-route',function(event,attr){
        $scope.search();
    });


    $scope.search = function(){


        $scope.params_to_href = $scope.createIntentHashRoute();

        $rootScope.search_process = true;

        $scope.indexdata.size=$scope.pag.size;
        $scope.indexdata.from=$scope.pag.from;



        if(!$scope.checkIsEmptyIndexSearchData($scope.indexdata)) {

            $rootScope.search_process_svg = false;

            //$http.post(AppService.url + '/ajax/elasticsearch/index/keywords', $scope.indexdata)
            $http.post(AppService.url + '/ajax/elasticsearch/index/by/criteria', $scope.indexdata)
                .then(
                    function (response) {
                        console.log(response.data);
                        $scope.$broadcast('response-index-data', {data: response.data});

                    }
                )

        }


    }



    $scope.createIntentHashRoute = function(){

        //console.log('route',$routeParams);
        var params = $routeParams;
        params.route = 'i';
        $rootScope.$broadcast('intent-hash-route',params);

        return params;

    }



    ///////////////////////////////////////////////////////////////////////////////////////////////


    $scope.$on('response-index-data', function(event,attr){

        //console.log('res data',$scope.data.result);
        //console.log('records',attr.data.records);

        $scope.search_start = false;

        $rootScope.search_process = false;
        $scope.total = attr.data.total;
        //

        if($scope.data.result == null) {
            $scope.data.result = [];
            $scope.data.result = attr.data.records;
            //console.log('concat',$scope.data.result);
        }else{

            //angular.forEach($scope.data.result, function(r,i){

                //angular.forEach(attr.data.records, function(item,j){
                    //$scope.data.result.push(item);
                    //if(r.record_id==item.record_id){

                        //console.log('double')
                        //console.log(r.fragments)
                        //console.log(item.fragments)


                        //angular.forEach(r.fragments, function(fr,z){
                        //
                        //    angular.forEach(item.fragments, function(f,x){
                        //
                        //        if(fr.fid == f.fid){
                        //            console.log('jest');
                        //        }else{
                        //            console.log('brak');
                        //            //item.fragments[]
                        //            $scope.data.result[i].fragments.push(f);
                        //        }
                        //
                        //    });
                        //
                        //});

                        //angular.forEach(item.fragments, function(f,x){
                        //
                        //    if(!$filter('check_is_fragment')(r.fragments,f)){
                        //        $scope.data.result[i].fragments.push(f);
                        //    }
                        //
                        //});

                        //attr.data.records.splice(j,1);
                    //}
                //})

            //})

            angular.forEach(attr.data.records, function(item,j){
                $scope.data.result.push(item);
            })

            //console.log('concat next',$scope.data.result);
        }

        if(attr.data.total==0){
            $scope.data.result = null;
            $scope.search_start = true;
        }



        $scope.createPagiantionSteps();

        //console.log('pagination',$scope.pagination);

        if($scope.last_pags_index==$scope.pag_index){
            $scope.show_next_button = false;
        }else{
            $scope.show_next_button = true;
        }
        $rootScope.search_process_svg = false;
    });


    //$scope


    $scope.createPagiantionSteps = function(){

        if($scope.pag.size>=$scope.total){
            $scope.pagination = null;
        }else{

            var dev = Math.ceil($scope.total/$scope.pag.size);
            $scope.pagination = [];
            for (var i=0;i<dev;i++){
                if(i==0){
                    $scope.pagination.push(0);
                }else{
                    $scope.pagination.push((i*$scope.pag.size));
                }

            }

            $scope.last_pags_index = $scope.pagination.length;

        }

    }


    $scope.nextPagIndex = function(){

        if($scope.last_pags_index!=$scope.pag_index){

            $scope.pag_index++;
            $scope.pag.from = $scope.pagination[$scope.pag_index];
            $scope.search();

        }
    }

    $scope.checkIsEmptyIndexSearchData = function(index){
        //console.log(index);

        var begin, end, place, tag;

        if(index.date.begin.year=='' && index.date.begin.month=='' && index.date.begin.day==''){
            begin = false;
        }else{
            begin = true;
        }

        if(index.date.end.year=='' && index.date.end.month=='' && index.date.end.day==''){
            end = false;
        }else{
            end = true;
        }

        if(index.place==null){
            place = false;
        }else{
            place = true;
        }

        if(index.tag==null){
            tag = false;
        }else{
            tag = true;
        }

        if(!begin && !end && !place && !tag){
            return true;
        }else{
            return false
        }

    }

}]);


app.controller('GlobalController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout', '$rootScope','$cookies', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout, $rootScope,$cookies) {

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

    $rootScope.$on('intent-hash-route', function(event, attr){

        $scope.hash_emit ='&hash='+JSON.stringify(attr);

    })

    $timeout(function(){

        angular.element(document.getElementById('body')).removeClass('alphaHide');
        angular.element(document.getElementById('body')).addClass('alphaShow');

    },500)

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

    $http.get(AppService.url+'/get/ajax/auth')
        .then(

            function successCallback(response) {

                //$log.info(response);
                $rootScope.auth = response.data.boolean;

            },
            function errorCallback(response) {

            }
        );

    $scope.$watch('auth',function(){
        $rootScope.auth = $scope.auth;
    })


}]);

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

            var t = parseInt(hh)+":"+mm+":"+ss;

            return t;

        }



    }

});

app.filter('contentTrim', function() {

    return function(content,words) {

        var trim_content = '';

        var spl = content.split(' ');
        for(var i=0;i<words;i++){

            if(i!=0){
                trim_content += ' '+spl[i];
            }else{
                trim_content += spl[i];
            }

        }

        return trim_content+' ...';
    }

});


//app.controller('SearchController',['$scope', '$rootScope', '$http', '$log', '$q', '$location', '$window', '$filter', '$timeout', 'AppService','$routeParams','DateService', function($scope, $rootScope, $http, $log, $q, $location, $window, $filter, $timeout, AppService, $routeParams,DateService) {
//
//    $scope.initData = function(){
//
//        $scope.viewtype_hide = "index_search";
//
//        $scope.data = {
//            phrase:''
//        }
//    }
//
//
//
//    $scope.onSubmitFulltext = function(){
//
//        $location.path('/t').search('q='+$scope.data.phrase);
//
//    }
//
//}]);


app.controller('SearchNormalController',['$scope', '$rootScope', '$http', '$log', '$q', '$location', '$window', '$filter', '$timeout', 'AppService','$routeParams','DateService', '$anchorScroll', function($scope, $rootScope, $http, $log, $q, $location, $window, $filter, $timeout, AppService,$routeParams,DateService,$anchorScroll) {

    $scope.initData = function(){

        $scope.limit = 20;

        $scope.response_data = null;

        $scope.search_start = false;

        $scope.show_next_button = false;
        $scope.search_process_svg = false;

        $scope.auth = $rootScope.auth;

        $scope.viewtype_hide = "index_search";

        $scope.pag = {
            size:$scope.limit,
            from:0
        }

        $scope.total = null;
        $scope.pagination = null;
        $scope.pag_index = 0;
        $scope.last_pags_index = 0;


        $scope.data = {
            phrase:'',
            result:[]
        }


        if($routeParams.q){
            $scope.data.phrase = $routeParams.q;

            if($routeParams.type){
                $rootScope.search_type = $routeParams.type;
                $scope.delegateToCurrentSearch();
            }

        }else{
            $scope.data.phrase = '';
            $rootScope.search_type='t';
        }



    }



    $scope.changeTextSearchType = function(type,select){


        if($rootScope.search_type!=undefined) {

            if ($rootScope.search_type.length == 1) {

                if(select=='selected'){
                    $rootScope.search_type = undefined;
                }else{
                    $rootScope.search_type = 'ti';
                }


            } else {

                if(select=='selected'){

                    if(type=='i'){
                        $rootScope.search_type = 't';
                    }
                    if(type=='t'){
                        $rootScope.search_type = 'i';
                    }

                }else{
                    $rootScope.search_type = type;
                }

            }
        }else{
            $rootScope.search_type = type;
        }


    }

    $rootScope.$watch('search_type', function(nVal,oVal){


        if(nVal!=undefined){
            if(nVal=='ti'){
                $scope.type_checkbox_text = 'selected';
                $scope.type_checkbox_images = 'selected';
            }else if(nVal=='i'){
                $scope.type_checkbox_text = '';
                $scope.type_checkbox_images = 'selected';
            }else if(nVal=='t'){
                $scope.type_checkbox_text = 'selected';
                $scope.type_checkbox_images = '';
            }
        }else{
            $scope.type_checkbox_text = '';
            $scope.type_checkbox_images = '';
        }

    });

    /////////////////////////////////////////////////////////////////////////////////////////////




    $scope.delegateToCurrentSearch = function(){

        switch ($routeParams.type){

            case 't':

                console.log('t');
                $rootScope.search_type = 't';
                $scope.searchByPhrase('t');

                break;

            case 'i':

                $scope.search_type = 'i';
                console.log('i');
                $scope.searchByPhraseImages();

                break;

            case 'ti':

                $rootScope.search_type = 'ti';
                console.log('ti');
                $scope.searchByPhrase('ti');

                break;

        }


    }


    $scope.onSubmitFulltext = function(){

        $location.path('/').search('type='+$rootScope.search_type+'&q='+$scope.data.phrase);

    }


    ////////////////////////////////////////////////////////////////////////////////////////////


    $scope.searchByPhrase = function(type){

        var to_server = {phrase:$scope.data.phrase,pag:$scope.pag,type:type};
        $scope.search_process_svg = true;
        $scope.createIntentHashRoute();
        $http.post(AppService.url+'/ajax/elasticsearch/by/phrase', to_server)
            .then(
                function(response){
                    console.log(response.data);
                    $scope.total = response.data.total;
                    $scope.data.result = $scope.data.result.concat(response.data.records);
                    //$scope.data.result = response.data.records;

                    if($scope.total==0){
                        $scope.search_start = true;
                    }else{
                        $scope.search_start = false;
                    }

                    $scope.createPagiantionSteps();
                    if($scope.last_pags_index==$scope.pag_index){
                        $scope.show_next_button = false;
                    }else{
                        $scope.show_next_button = true;
                    }
                    //console.log($scope.data.result);
                    $scope.search_process_svg = false;
                }
            )
    }



    $scope.searchByPhraseImages = function(){

        $scope.createIntentHashRoute();

        $http.post(AppService.url + '/get/search/elastic/images/by/criteria', {frase: $routeParams.q})
            .then(
                function successCallback(response) {
                    console.log(response);
                    $scope.data.images = response.data;
                },
                function errorCallback(reason) {

                }
            );
    }


    $scope.createIntentHashRoute = function(){

        console.log('route',$routeParams);
        var params = $routeParams;
        params.route = null;
        $rootScope.$broadcast('intent-hash-route',params);

    }


    //////////////////////////////////////////////////////////////////////////////////////////////////////////////


    $scope.createPagiantionSteps = function(){

        if($scope.pag.size>=$scope.total){
            $scope.pagination = null;
        }else{

            var dev = Math.ceil($scope.total/$scope.pag.size);
            $scope.pagination = [];
            for (var i=0;i<dev;i++){
                if(i==0){
                    $scope.pagination.push(0);
                }else{
                    $scope.pagination.push(i*$scope.limit);
                }

            }

            $scope.last_pags_index = $scope.pagination.length-1;

        }

    }


    $scope.nextPag = function(){

        if($scope.last_pags_index!=$scope.pag_index){

            $scope.pag_index++;
            $scope.pag.from = $scope.pagination[$scope.pag_index];
            $scope.searchByPhrase($rootScope.search_type);

        }
    }

}]);



app.controller('ImagesSearchController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout', '$interval', '$document','$rootScope','$routeParams', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout, $interval, $document,$rootScope,$routeParams) {

    $scope.initData = function(){

        $scope.data = {
            phrase:'',
            images:null
        }

        $scope.viewtype_hide = "index_search";
        $scope.fragment_type = '';
        $scope.image_type = 'selected';

        if($routeParams.q){
            $scope.query = $routeParams.q;
            $scope.data.phrase = $routeParams.q;
        }else{
            $scope.query = '';
            $scope.data.phrase = '';

            $scope.indexdata = {
                date: {
                    begin: {
                        year: "",
                        month: "",
                        day: ""
                    },
                    end: {
                        year: "",
                        month: "",
                        day: ""
                    }
                },
                place:null,
                tag:null,
                size:1000,
                from:0
            }
        }


    }


    $scope.$watch('query', function(nVal, oVal){

        if(nVal!='') {

            if($routeParams.q){
                $scope.query = $routeParams.q;
                $scope.data.phrase = $routeParams.q;
            }else{
                $scope.query = '';
                $scope.data.phrase = '';
            }

            $http.post(AppService.url + '/get/search/elastic/images/by/criteria', {frase: $routeParams.q})
                .then(
                    function successCallback(response) {
                        console.log(response);
                        $scope.data.images = response.data;
                    },
                    function errorCallback(reason) {

                    }
                );
        }

    });


    $scope.onSubmitFulltext = function(){

        $location.path('/img/').search('q='+$scope.data.phrase);
        $scope.query = $scope.data.phrase;

    }




}]);










app.config(function($routeProvider, $locationProvider) {

    $routeProvider.
    when('/', {
        templateUrl: '/templates/front/elasticsearch/search/text-search-new.html',
        controller: 'SearchNormalController'
    }).when('/i/', {
        templateUrl: '/templates/front/elasticsearch/search/index-search.html',
        controller: 'SearchIndexController'
    })
    //    .when('/img/', {
    //    templateUrl: '/templates/front/elasticsearch/search/search-images.html',
    //    controller: 'ImagesSearchController'
    //});

    $locationProvider.html5Mode({
        enabled: false,
        requireBase: false
    });

});