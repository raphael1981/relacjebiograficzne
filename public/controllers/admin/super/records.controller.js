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
       url : $location.protocol()+'://'+$location.host(),
       sourcesurls:{
           audio:'http://fono.dsh.waw.pl/',
           video:'http://video.dsh.waw.pl/'
       },
       sources:{
           //audio:'/home/relacjebiograficzne.pl/public/media/audio',
           //video:'/home/relacjebiograficzne.pl/public/media/video',
           audio:'/home/media_on_qnap/audio',
           video:'/home/media_on_qnap/video'
       }


   };
});


// app.factory('AppService', function($location) {
//     return {
//         url : $location.protocol()+'://'+$location.host(),
//         sourcesurls:{
//             audio:'http://audiovideo.spaceforweb.pl/audio/',
//             video:'http://audiovideo.spaceforweb.pl/video/'
//         },
//         sources:{
//             audio:'/usr/home/raphael/domains/audiovideo.spaceforweb.pl/public_html/audio',
//             video:'/usr/home/raphael/domains/audiovideo.spaceforweb.pl/public_html/video'
//         }


//     };
// });




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


app.directive('deleteElement', function(AppService, $http, $q, $filter, $route) {
    return {
        templateUrl: '/templates/admin/super/interval/delete.html',
        link: function(scope, element, attributes, searchFactory){



            //console.log(attributes);
            scope.id = attributes.id;

            scope.relations = [
                {
                    type:'oneToMany',
                    method:'fragments',
                    name:'Fragmenty'
                },
                {
                    type:'manyToMany',
                    method:'interviewees',
                    name:'Świadkowie'
                },
                {
                    type:'manyToMany',
                    method:'tags',
                    name:'Tags'
                },
                {
                    type:'manyToMany',
                    method:'intervals',
                    name:'Interwały czasowe'
                },
                {
                    type:'manyToMany',
                    method:'recordsMorphToMany',
                    name:'Recordy'
                },
                {
                    type:'manyToMany',
                    method:'recordsMorphedByMany',
                    name:'Recordy'
                }

            ];

            scope.model = 'App\\Entities\\Record';

            scope.getBeforeDeleteRaport = function(){

                var deferred = $q.defer();

                $http.post(AppService.url+'/administrator/record/get/raport/before/delete', {relations:scope.relations, id:attributes.id})
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

                $http.put(AppService.url+'/administrator/delete/record', {relations:scope.relations, id:attributes.id, model:scope.model})
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


app.directive('leftListTranscriptions', function() {
    return {
        templateUrl: '/templates/admin/super/records/left.html'
    };
});


app.directive('fragmentFilter', function() {
    return {
        templateUrl: '/templates/admin/super/records/fragment-filter.html',
        link: function (scope, element, attributes) {

            //console.log(attributes);

        }
    }

});



app.directive('statusBtnInList', function() {
    return {
        templateUrl: '/templates/admin/super/records/status.html',
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



app.filter('tagsfilter', function() { return function(tags, added) {

    var array = [];
    var iter = 0;
    var bool = true;

    //console.log(added);

    for(var i=0;i<tags.length;i++){

        bool = true;

        for(var j=0;j<added.length;j++) {

            if (tags[i].id == added[j].id) {
                bool = false;
            }

        }

        if(bool) {
            array[iter] = tags[i];
            iter++;
        }

    }

    //console.log(array);

    return array;


}});



app.filter('placesfilter', function() { return function(tags, added) {

    var array = [];
    var iter = 0;
    var bool = true;

    //console.log(added);

    for(var i=0;i<tags.length;i++){

        bool = true;

        for(var j=0;j<added.length;j++) {

            if (tags[i].id == added[j].id) {
                bool = false;
            }

        }

        if(bool) {
            array[iter] = tags[i];
            iter++;
        }

    }

    //console.log(array);

    return array;


}});


app.filter('idarraycollect', function() { return function(objects) {

    var array = [];

    for(var i=0;i<objects.length;i++){
        array.push(objects[i].id);
    }

    return array;

}});







app.filter('secondsToTime', function() {

    function padTime(t) {
        return t < 10 ? "0"+t : t;
    }

    return function(_seconds) {
        if (typeof _seconds !== "number" || _seconds < 0)
            return "00:00:00";

        var hours = Math.floor(_seconds / 3600),
            minutes = Math.floor((_seconds % 3600) / 60),
            seconds = Math.floor(_seconds % 60);

        return padTime(hours) + ":" + padTime(minutes) + ":" + padTime(seconds);
    };
});



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



app.controller('GlobalController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout', 'ngDialog', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout, ngDialog) {

    $timeout(function(){

        angular.element(document.getElementById('body')).removeClass('alphaHide');
        angular.element(document.getElementById('body')).addClass('alphaShow');

    },500)

}]);


app.controller('RecordsController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams','$rootScope', 'ngDialog', '$sce', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams,$rootScope,ngDialog,$sce) {


    $scope.initData = function(){

        $scope.limit = 10;
        $scope.start = 0;
        $scope.frase = null;
        $scope.searchcolumns = {
            title:true,
            signature:true,
            source:true,
            xmltrans:true,
            description:true,
            summary:true
        };

        $scope.datainstock = [];
        $scope.calclass = [];

        $scope.filter = {
            filetype:'all'
        };

        if($routeParams.start){

            if($routeParams.frase){
                $scope.frase = $routeParams.frase;
            }else{
                $scope.frase = null;
            }

            if($routeParams.type){
                $scope.filter.filetype = $routeParams.type;
            }else{
                $scope.filter.filetype = 'all';
            }

            $scope.start = parseInt($routeParams.start);
            $scope.halfclick = JSON.parse($routeParams.halfclick);
            $scope.iterstart = parseInt($routeParams.iterstart);
            $scope.getElements($routeParams.iterstart);

        }else {

            $scope.start = 0;
            if($routeParams.frase && $routeParams.frase!=''){
                $scope.frase = $routeParams.frase;
            }else{
                $scope.frase = null;
            }

            if($routeParams.type){
                $scope.filter.filetype = $routeParams.type;
            }else{
                $scope.filter.filetype = 'all';
            }

            $scope.getElements(0);
        }

    }


    $scope.getElements = function(iterstart){

        if(iterstart) {
            iterstart = parseInt(iterstart);
        }

        $http.post(AppService.url + '/administrator/get/records',
            {
                start:$scope.start,
                limit:$scope.limit,
                frase:$scope.frase,
                searchcolumns:$scope.searchcolumns,
                filter:$scope.filter
            })
            .then(
                function successCallback(response) {

                    //$log.info(response.data);


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

        if($routeParams.frase || $routeParams.type){

            var search = {start:$scope.start,iterstart:$scope.iterstart,halfclick:$scope.halfclick};

            if($routeParams.frase){
                search.frase = $routeParams.frase;
            }
            //console.log($routeParams.type);
            if($routeParams.type!=null || $routeParams.type!=''){
                search.type = $routeParams.type;
            }

            //if($routeParams.status){
            //    search.status = JSON.stringify($scope.filter.status);
            //}

            $location.path('/').search(search);

        }else{
            //$location.path('/').search({start:$scope.start,halfclick:$scope.halfclick,iterstart:$scope.iterstart,status:JSON.stringify($scope.filter.status)});
            $location.path('/').search({start:$scope.start,halfclick:$scope.halfclick,iterstart:$scope.iterstart,type:$scope.filter.type});
        }

    });


    $scope.changePage = function(start){

        $scope.halfclick = false;
        $scope.start = $scope.limit*start;
        //$scope.getElements(start);

        if($scope.frase!=null || $scope.frase!=''){
            $location.path('/').search({start:$scope.start,iterstart:start,halfclick:$scope.halfclick,frase:$scope.frase,type:$scope.filter.filetype});
        }else{
            $location.path('/').search({start:$scope.start,halfclick:$scope.halfclick,iterstart:start,type:$scope.filter.filetype});
        }



    }


    $scope.goToHalf = function(){

        $scope.halfclick = true;
        $scope.iterstart = $scope.start;
        $scope.start = $scope.half*$scope.limit;
        //$scope.getElements($scope.start);
        if($scope.frase!=null || $scope.frase!=''){
            $location.path('/').search({start:$scope.start,iterstart:$scope.half,halfclick:$scope.halfclick,frase:$scope.frase});
        }else{
            $location.path('/').search({start:$scope.start,halfclick:$scope.half,iterstart:$scope.iterstart});
        }

    }


    $scope.changePagePrev = function(){

        $scope.halfclick = false;
        var start = $scope.iterstart-1;
        $scope.start = $scope.limit*start;
        //$scope.getElements(start);
        if($scope.frase!=null || $scope.frase!=''){
            $location.path('/').search({start:$scope.start,iterstart:start,halfclick:$scope.halfclick,frase:$scope.frase,type:$scope.filter.filetype});
        }else{
            $location.path('/').search({start:$scope.start,halfclick:$scope.halfclick,iterstart:start,type:$scope.filter.filetype});
        }

    }


    $scope.changePageNext = function(){

        $scope.halfclick = false;
        var start = $scope.iterstart+1;
        $scope.start = $scope.limit*start;
        //$scope.getElements(start);
        if($scope.frase!=null || $scope.frase!=''){
            $location.path('/').search({start:$scope.start,iterstart:start,halfclick:$scope.halfclick,frase:$scope.frase,type:$scope.filter.filetype});
        }else{
            $location.path('/').search({start:$scope.start,halfclick:$scope.halfclick,iterstart:start,type:$scope.filter.filetype});
        }

    }


    $scope.changeShowFirst = function(){

        $scope.start = 0;
        //$scope.getElements(0);
        if($scope.frase!=null || $scope.frase!=''){
            $location.path('/').search({start:$scope.start,iterstart:$scope.start,halfclick:$scope.halfclick,frase:$scope.frase,type:$scope.filter.filetype});
        }else{
            $location.path('/').search({start:$scope.start,halfclick:$scope.halfclick,iterstart:$scope.start,type:$scope.filter.filetype});
        }

    }

    $scope.changeShowLast = function(){

        $scope.start = $scope.pages*$scope.limit;
        //$scope.getElements($scope.pages);
        if($scope.frase!=null || $scope.frase!=''){
            $location.path('/').search({start:$scope.start,iterstart:$scope.start,halfclick:$scope.halfclick,frase:$scope.frase,type:$scope.filter.filetype});
        }else{
            $location.path('/').search({start:$scope.start,halfclick:$scope.halfclick,iterstart:$scope.pages,type:$scope.filter.filetype});
        }

    }


    //Get Data Logic



    $scope.searchSubmit = function(){

        $scope.start = 0;
        $location.path('/').search({frase:$scope.frase,halfclick:$scope.halfclick,iterstart:$scope.start,type:$scope.filter.filetype});
        //$scope.getElements(0);


    }



    ////////////////////////////////////////////////////////


    $scope.changeRecordData = function(field, value, id, es_bool){

        $http.put(AppService.url + '/administrator/update/record/data',
            {
                field:field,
                value:value,
                id:id,
                es:(es_bool)?{field:"record_status"}:null
            }
            )
            .then(
                function successCallback(response) {

                    console.log(response);
                    //$scope.getElements($scope.iterstart);
                    $route.reload();

                },
                function errorCallback(response) {

                }
            );

    }





}]);

app.controller('NewRecordController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams', '$rootScope', 'ngDialog', '$sce', 'Upload', '$interval', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams,$rootScope, ngDialog, $sce, Upload,$interval) {


    //$table->text('title');
    //$table->text('alias');
    //$table->string('signature', 255);
    //$table->string('source', 1000);
    //$table->string('xmltrans', 255);
    //$table->mediumText('description');
    //$table->mediumText('summary');
    //$table->bigInteger('duration');
    //$table->enum('type',['video', 'audio']);
    //$table->integer('status')->unsigned()->default(0);

    $scope.initData = function(){

        $scope.record = {
            type: 'video',
            title:'',
            signature:'',
            source:'',
            xmltrans:null,
            description:'',
            summary:'',
            duration:null,
            interviewees:[],
            redactors:[]
        }

        $scope.valid = {
            title:false,
            signature:false,
            source:false,
            duration:false,
        }

        $scope.classes = {
            title:'',
            signature:'',
            source:'',
        }

        $scope.sourcealert = false;

        $scope.sources = null;

        $scope.interviewees = null;

        $scope.redactors = null;

        $scope.loadingfile = false;

        $scope.listSources();
        $scope.getAllInterviewees();
        $scope.getAllRedactors();

    }


    $scope.listSources = function(){

        $http.post(AppService.url+'/get/media/sources', AppService.sources)
            .then(
                function successCallback(response){
                    //console.log(response.data);
                    $scope.sources = response.data;
                },
                function errorCallback(reason){

                }
            )

    }


    $scope.getAllInterviewees = function(){

        $http.get(AppService.url+'/get/all/interviewees')
            .then(
                function successCallback(response){
                    //console.log(response.data);
                    $scope.interviewees = response.data;
                },
                function errorCallback(reason){

                }
            )

    }


    $scope.getAllRedactors = function(){

        $http.get(AppService.url+'/get/all/redactors')
            .then(
                function successCallback(response){
                    //console.log(response.data);
                    $scope.redactors = response.data;
                },
                function errorCallback(reason){

                }
            )

    }


    ///////////////////////////////Upload////////////////////////////////////


    $scope.$watch('file', function () {
        $scope.uploadFiles($scope.file);
    });


    $scope.uploadFiles = function (file) {

        if(file && file.type=='text/xml') {

            //console.log(file);

            Upload.upload({
                url: AppService.url + '/upload/file/record/xml',
                fields: {},
                file: file
            }).progress(function (evt) {

                var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                $scope.log = 'progress: ' + progressPercentage + '% ' + evt.config.file.name + '\n' + $scope.log;
                //$log.info(progressPercentage);
                $scope.progress = progressPercentage;

            }).success(function (data, status, headers, config) {

                //$log.info(data);
                $scope.record.xmltrans = data;
                $scope.progress = 0;

                $timeout(function () {
                    $scope.log = 'file: ' + config.file.name + ', Response: ' + JSON.stringify(data) + '\n' + $scope.log;
                });

            }).error(function (data, status, headers, config) {
                //$log.info(data);
            });

        }

    };


    //////////////////////////////////////////////////////////////////////////////////////


    $scope.removeXmlFile = function(source){

        $http.put(AppService.url+'/remove/xml/file', source)
            .then(
                function successCallback(response){
                    //console.log(response.data);
                    $scope.record.xmltrans = null;
                },
                function errorCallback(reason){

                }
            )

    }


    ////////////////////////////////////Wyswig/////////////////////////////////////////////


    $scope.model = {};


    //$scope.model.customMenu = {
    //    'openMediaModal' : {
    //        tag : 'button',
    //        classes: 'btn btn-primary btn-md btn-add-image',
    //        attributes: [{name : 'ng-model',
    //            value:'openMediaCloud'},
    //            {name : 'type',
    //                value : 'button'},
    //            {name : 'title',
    //                value : 'Dodaj obrazek'},
    //            {name: 'ng-click',
    //                value: 'openMediaModal()'},
    //        ],
    //        data: [{
    //            tag: 'i',
    //            text: ' Dodaj obrazek',
    //            classes: 'fa fa-picture-o'
    //        }]
    //    }
    //};


    //$scope.model.customFunctions = {
    //    chInsert: function(scope) {
    //        console.log('chi')
    //        if (scope.myInsertElement != '0') {
    //            document.execCommand("insertHTML", false, scope.myInsertElement);
    //            scope.myInsertElement = '0';
    //        }
    //    }
    //};

    $scope.model.menu = [
        ['bold', 'italic', 'underline', 'strikethrough', 'subscript', 'superscript'],
        ['format-block'],
        ['font'],
        ['font-size'],
        ['font-color', 'hilite-color'],
        ['remove-format'],
        ['ordered-list', 'unordered-list', 'outdent', 'indent'],
        ['left-justify', 'center-justify', 'right-justify'],
        ['code', 'quote', 'paragraph'],
        ['link', 'image'],
        ['openMediaModal']
    ];


    ///////////////////////////////////////////////////////////////////////////////////////////


    $scope.$watch('record.type', function(){
        $scope.loadingfile = false;
        $scope.valid.source = false;
        document.getElementById('appendSource').innerHTML = '';
        $scope.record.duration = null;
        $scope.record.source = null;
    });


    $scope.checkTitle = function(){

        if($scope.record.title.length>1){

            $scope.valid.title = true;
            $scope.classes.title = 'has-success';

        }else{

            $scope.valid.title = false;
            $scope.classes.title = 'has-error';
        }

    }


    $scope.checkSignature = function(){

        if($scope.record.signature.length>0){

            $scope.valid.signature = true;
            $scope.classes.signature = 'has-success';

        }else{

            $scope.valid.signature = true;
            $scope.classes.signature = 'has-error';

        }

    }



    $scope.getTransDuration = function(source){

        //alert($scope.record.type);
        $scope.loadingfile = true;
        $scope.valid.source = false;
        $scope.valid.duration = false;
        document.getElementById('appendSource').innerHTML = '';
        $scope.record.duration = null;


        if($scope.record.type=='video') {

            var video = document.createElement('video');
            video.setAttribute('controls', true);
            video.setAttribute('class', 'img-responsive');
            video.setAttribute('id', 'transId');
            var sourceEl = document.createElement('source');
            sourceEl.src = AppService.sourcesurls.video + source.filename;
            video.appendChild(sourceEl);

            //document.getElementById('appendSource').appendChild(video);

            $timeout(function () {
                //console.log(video);
                $scope.loadingfile = false;
                document.getElementById('appendSource').appendChild(video);
                $scope.record.duration = Math.floor(video.duration);
                $scope.valid.source = true;
                $scope.valid.duration = true;
                $scope.classes.source = '';
                $scope.sourcealert = false;
            }, 3000);

        }

        if($scope.record.type=='audio') {

            var audio = document.createElement('audio');
            audio.setAttribute('controls', true);
            audio.setAttribute('class', 'img-responsive');
            audio.setAttribute('id', 'transId');
            var sourceEl = document.createElement('source');
            sourceEl.src = AppService.sourcesurls.audio + source.filename;
            audio.appendChild(sourceEl);

            //document.getElementById('appendSource').appendChild(video);

            $timeout(function () {
                //console.log(video);
                $scope.loadingfile = false;
                document.getElementById('appendSource').appendChild(audio);
                $scope.record.duration = Math.floor(audio.duration);
                $scope.valid.source = true;
                $scope.valid.duration = true;
                $scope.classes.source = '';
                $scope.sourcealert = false;
            }, 3000);

        }

    }


    $scope.createNewRecord = function(){

        $scope.checkTitle();
        $scope.checkSignature();

        if(!$scope.valid.source || !$scope.valid.duration){
            $scope.classes.source = 'has-error';
            $scope.sourcealert = true;
        }else{
            $scope.classes.source = '';
            $scope.sourcealert = false;
        }

        //console.log($scope.record);
        //console.log($scope.valid);
        //console.log($filter('checkfalse')($scope.valid));

        if($filter('checkfalse')($scope.valid)){

            $scope.rsaving = '';


            $http.put(AppService.url+'/administrator/add/new/record', $scope.record)
                .then(
                    function successCallback(response){
                        //console.log(response.data);

                        if(response.data.success){
                            $scope.rsaving = 'hidden';
                            $scope.rsaved = '';
                            document.getElementById('appendSource').innerHTML = '';
                            $scope.initData();
                        }

                    },
                    function errorCallback(reason){

                    }
                )


        }

    }


}]);




app.controller('EditRecordController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams', '$rootScope', 'ngDialog', '$sce', 'Upload', '$interval', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams,$rootScope, ngDialog, $sce, Upload,$interval) {


    $scope.initData = function(){

        $scope.record = {
            id:$routeParams.id,
            type: null,
            title:'',
            signature:'',
            source:'',
            xmltrans:null,
            description:'',
            summary:'',
            duration:null
        }

        $scope.valid = {
            title:false,
            signature:false,
            source:false,
            duration:false,
        }

        $scope.classes = {
            title:'',
            signature:'',
            source:'',
        }

        $scope.rdata = null;

        $scope.sourcealert = false;

        $scope.sources = null;

        $scope.interviewees = null;

        $scope.loadingfile = false;

        $scope.xmluploadstatus = 'original';


        $scope.getRecordData().then(
            function(data){

                if(data==1) {

                    //console.log($scope.rdata);

                    $scope.record.type = $scope.rdata.type;
                    $scope.record.title=$scope.rdata.title;
                    $scope.record.signature=$scope.rdata.signature;

                    switch ($scope.record.type){

                        case 'video':
                            $scope.record.source={filename:$scope.rdata.source, filepath:AppService.sources.video+'/'+$scope.rdata.source};
                            break;

                        case 'audio':

                            $scope.record.source={filename:$scope.rdata.source, filepath:AppService.sources.audio+'/'+$scope.rdata.source};

                            break;

                    }


                    $scope.record.xmltrans=$scope.rdata.xmltrans;
                    $scope.record.oldxmltrans=$scope.rdata.xmltrans;
                    $scope.record.description=$scope.rdata.description;
                    $scope.record.summary=$scope.rdata.summary;
                    $scope.record.duration=$scope.rdata.duration;



                    $scope.listSources().then(
                        function (data) {


                            if (data == 1) {
                                $scope.getTransDuration($scope.record.source);
                            }

                        },
                        function (reason) {

                        }
                    );

                    $scope.getAllInterviewees().then(
                        function (data) {

                            if (data == 1) {
                                $scope.getRecordInterviewees();

                            }

                        },
                        function (reason) {

                        }
                    );

                    $scope.getAllRedactors().then(
                        function (data) {

                            if (data == 1) {

                                $scope.getRecordRedactors();

                            }

                        },
                        function (reason) {

                        }
                    );

                }

            },
            function(reason){

            }
        )


    }


    $scope.listSources = function(){

        var deferred = $q.defer();

        $http.post(AppService.url+'/get/media/sources', AppService.sources)
            .then(
                function successCallback(response){
                    //console.log(response.data);
                    $scope.sources = response.data;
                    deferred.resolve(1);

                },
                function errorCallback(reason){

                    deferred.reject();

                }
            )

        return deferred.promise;

    }


    $scope.getAllInterviewees = function(){

        var deferred = $q.defer();

        $http.get(AppService.url+'/get/all/interviewees')
            .then(
                function successCallback(response){
                    //console.log(response.data);
                    $scope.interviewees = response.data;
                    deferred.resolve(1);
                },
                function errorCallback(reason){

                    deferred.reject();

                }
            )

        return deferred.promise;

    }


    $scope.getAllRedactors = function(){

        var deferred = $q.defer();

        $http.get(AppService.url+'/get/all/redactors')
            .then(
                function successCallback(response){
                    //console.log(response.data);
                    $scope.redactors = response.data;
                    deferred.resolve(1)
                },
                function errorCallback(reason){
                    deferred.reject();
                }
            )

        return deferred.promise;

    }


    $scope.getRecordData = function(){

        var deferred = $q.defer();

        $http.get(AppService.url+'/administrator/get/record/data/'+$scope.record.id)
            .then(
                function successCallback(response){
                    //console.log(response.data);
                    $scope.rdata = response.data;
                    deferred.resolve(1);
                },
                function errorCallback(reason){
                    deferred.reject();
                }
            )

        return deferred.promise;

    }

    $scope.getRecordInterviewees = function(){

        var deferred = $q.defer();

        $http.get(AppService.url+'/administrator/get/record/interviewees/'+$scope.record.id)
            .then(
                function successCallback(response){
                    //console.log(response.data);
                    $scope.record.interviewees = $filter('idarraycollect')(response.data);
                    deferred.resolve(1);
                },
                function errorCallback(reason){
                    deferred.reject();
                }
            )

        return deferred.promise;

    }


    $scope.getRecordRedactors = function(){

        var deferred = $q.defer();

        $http.get(AppService.url+'/administrator/get/record/redactors/'+$scope.record.id)
            .then(
                function successCallback(response){
                    console.log(response.data);
                    $scope.record.redactors = $filter('idarraycollect')(response.data);
                    deferred.resolve(1);
                },
                function errorCallback(reason){
                    deferred.reject();
                }
            )

        return deferred.promise;

    }




    ////////////////////////////////////////////////////////////////////////////////////////////////////


    $scope.getTransDuration = function(source){

        //alert($scope.record.type);
        $scope.loadingfile = true;
        $scope.valid.source = false;
        $scope.valid.duration = false;
        document.getElementById('appendSource').innerHTML = '';
        $scope.record.duration = null;


        if($scope.record.type=='video') {

            var video = document.createElement('video');
            video.setAttribute('controls', true);
            video.setAttribute('class', 'img-responsive');
            video.setAttribute('id', 'transId');
            var sourceEl = document.createElement('source');
            sourceEl.src = AppService.sourcesurls.video + source.filename;
            video.appendChild(sourceEl);

            //document.getElementById('appendSource').appendChild(video);

            $timeout(function () {
                //console.log(video);
                $scope.loadingfile = false;
                document.getElementById('appendSource').appendChild(video);
                $scope.record.duration = Math.floor(video.duration);
                $scope.valid.source = true;
                $scope.classes.source = '';
                $scope.sourcealert = false;

                if($scope.record.duration==NaN){
                    $scope.valid.duration = false;
                }else{
                    $scope.valid.duration = true;
                }

            }, 3000);

        }

        if($scope.record.type=='audio') {

            var audio = document.createElement('audio');
            audio.setAttribute('controls', true);
            audio.setAttribute('class', 'img-responsive');
            audio.setAttribute('id', 'transId');
            var sourceEl = document.createElement('source');
            sourceEl.src = AppService.sourcesurls.audio + source.filename;
            audio.appendChild(sourceEl);

            //document.getElementById('appendSource').appendChild(video);

            $timeout(function () {
                //console.log(video);
                $scope.loadingfile = false;
                document.getElementById('appendSource').appendChild(audio);
                $scope.record.duration = Math.floor(audio.duration);
                $scope.valid.source = true;
                $scope.classes.source = '';
                $scope.sourcealert = false;

                if($scope.record.duration==NaN){
                    $scope.valid.duration = false;
                }else{
                    $scope.valid.duration = true;
                }


            }, 3000);

        }

    }


    ////////////////////////////////////////////////////////////////////////////////////////////////////



    ///////////////////////////////Upload///////////////////////////////////////////////////////////////


    $scope.$watch('file', function () {
        $scope.uploadFiles($scope.file);
    });


    $scope.uploadFiles = function (file) {

        if(file && file.type=='text/xml') {

            //console.log(file);

            Upload.upload({
                url: AppService.url + '/upload/file/record/xml',
                fields: {},
                file: file
            }).progress(function (evt) {

                var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                $scope.log = 'progress: ' + progressPercentage + '% ' + evt.config.file.name + '\n' + $scope.log;
                //$log.info(progressPercentage);
                $scope.progress = progressPercentage;

            }).success(function (data, status, headers, config) {

                //$log.info(data);
                $scope.record.xmltrans = data;
                $scope.progress = 0;

                $timeout(function () {
                    $scope.xmluploadstatus = 'new'
                    $scope.log = 'file: ' + config.file.name + ', Response: ' + JSON.stringify(data) + '\n' + $scope.log;
                });

            }).error(function (data, status, headers, config) {
                //$log.info(data);
            });

        }

    };


    //////////////////////////////////////////////////////////////////////////////////////



    $scope.removeXmlFile = function(source){

        $http.put(AppService.url+'/remove/xml/file', source)
            .then(
                function successCallback(response){
                    //console.log(response.data);
                    $scope.xmluploadstatus = 'original';
                    $scope.record.xmltrans = $scope.record.oldxmltrans;
                },
                function errorCallback(reason){

                }
            )

    }

    //////////////////////////////////////////////////////////////////////////////////////





    $scope.checkTitle = function(){

        if($scope.record.title.length>1){

            $scope.valid.title = true;
            $scope.classes.title = 'has-success';

        }else{

            $scope.valid.title = false;
            $scope.classes.title = 'has-error';
        }

    }


    $scope.checkSignature = function(){

        if($scope.record.signature.length>0){

            $scope.valid.signature = true;
            $scope.classes.signature = 'has-success';

        }else{

            $scope.valid.signature = true;
            $scope.classes.signature = 'has-error';

        }

    }


    $scope.updateRecord = function(){

        $scope.checkTitle();
        $scope.checkSignature();

        if(!$scope.valid.source){
            $scope.classes.source = 'has-error';
            $scope.sourcealert = true;
        }else{
            $scope.classes.source = '';
            $scope.sourcealert = false;
        }

        //
        //console.log($scope.record);
        //console.log($scope.valid);


        if($filter('checkfalse')($scope.valid)){

            $scope.rsaving = '';
            $scope.record.uploadstatus = $scope.xmluploadstatus;

            $http.put(AppService.url+'/administrator/update/edit/record', $scope.record)
                .then(
                    function successCallback(response){
                        console.log(response.data);

                        if(response.data.success){
                            $scope.rsaving = 'hidden';
                            $scope.rsaved = '';
                            document.getElementById('appendSource').innerHTML = '';
                            $scope.initData();
                        }

                    },
                    function errorCallback(reason){

                    }
                )

        }

    }



}]);

app.controller('TagsRecordController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams', '$rootScope', 'ngDialog', '$sce', 'Upload', '$interval', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams,$rootScope, ngDialog, $sce, Upload,$interval) {



    $scope.initData = function(){

        //console.log($routeParams.id);
        $scope.record = {
            id:$routeParams.id,
            rdata:null,
            fragments:null,
            tags:[],
            intervals:[],
            places:[]
        }


        $scope.fullfragment = {
            record:null,
            current:[],
            currentplace:[],
            allplaces:[],
            all:[],
            intervals: [],
            baseinterval:[]
        };


        $scope.fragmentsloaded = false;



        $scope.intervals = null;
        $scope.intervalscloud = null;


        $scope.tags = null;
        $scope.tagscloud = null;

        $scope.getFullRecordData();



    }

    $scope.$watch('fullfragment.current', function(newVal, oldVal){
        //console.log('newVal', newVal);
        //console.log('oldVal',oldVal);
    });


    $scope.getFullRecordData = function(){

        $scope.wasloaded = '';

        $http.get(AppService.url + '/administrator/get/full/record/data/'+$scope.record.id)
            .then(
              function successCallback(response){

                  console.log(response.data);
                  $scope.record.rdata = response.data.record;
                  $scope.record.fragments = response.data.fragments;
                  $scope.record.tags = response.data.tags;
                  $scope.record.tagbase = response.data.tagsbase;
                  $scope.record.places = response.data.places;
                  $scope.record.placebase = response.data.tagbase;
                  $scope.record.intervals = response.data.intervals;
                  $scope.record.intervalsbase = response.data.intervalsbase;
                  $scope.getAllTags(false)
                      .then(
                          function(){
                              $scope.getAllPlaces(false)
                                  .then(
                                      function(){

                                          $scope.makeTagBaseForFragments($scope.record.fragments, $scope.record.tagbase)
                                              .then(
                                                  function(){

                                                      $scope.makePlaceBaseForFragments($scope.record.fragments, $scope.record.placebase)
                                                          .then(
                                                            function(){
                                                                $scope.wasloaded = 'hidden';
                                                            },
                                                            function(){

                                                            }
                                                        )

                                                  },
                                                  function(){

                                                  }
                                              )

                                      },
                                      function(){

                                      }
                                  )
                          },
                          function(){

                          }
                      )



              },
              function errorCallback(reason){

              }
            );

    }





    $scope.makeTagBaseForFragments = function(fragments, base){

        var d = $q.defer();

        //console.log(fragments);
        //console.log(base);

        angular.forEach(fragments, function(item, key){


            //$scope.fullfragment.all.push(
            //    base
            //);

            //console.log( item.tags);
            var tags = []
            angular.forEach(item.tags,function(t,k){
                tags.push({name: t.name,id: t.id})
            });
            $scope.fullfragment.current[key] = tags;


        });


        $scope.fragmentsloaded = false;

        d.resolve(1);
        return d.promise;

    }


    $scope.makePlaceBaseForFragments = function(fragments, base){

        var d = $q.defer();

        angular.forEach(fragments, function(item, key){


            //$scope.fullfragment.allplaces.push(
            //    base
            //);

            //$scope.fullfragment.currentplace[key] = item.places;
            //console.log(item.places);
            var places = []
            angular.forEach(item.places,function(t,k){
                places.push({name: t.name,id: t.id})
            });
            $scope.fullfragment.currentplace[key] = places;



        });


        $scope.fragmentsloaded = false;

        d.resolve(1);
        return d.promise;

    }



    $scope.$watch('record.tags', function(newVal, oldVal){

        $scope.getAllTags(true);

    },true);






    $scope.getAllTags = function(bool){

        var d = $q.defer();

        $http.get(AppService.url + '/get/all/tags')
            .then(
                function successCallback(response){

                    //
                    //if(bool){
                    //    $scope.tagscloud = $filter('tagsfilter')(response.data, $scope.record.tags);
                    //}else{
                    //    $scope.tagscloud = response.data;
                    //}

                    $scope.tagscloud = response.data;
                    d.resolve(1);

                },
                function errorCallback(reason){
                    d.reject(0);
                }
            )

        return d.promise;

    }


    $scope.getAllPlaces = function(bool){

        var d = $q.defer();

        $http.get(AppService.url + '/get/all/places')
            .then(
                function successCallback(response){


                    //if(bool){
                    //    $scope.placescloud = $filter('placesfilter')(response.data, $scope.record.places);
                    //}else{
                    //
                    //}

                    $scope.placescloud = response.data;

                    d.resolve(1);

                },
                function errorCallback(reason){
                    d.reject(0);
                }
            )

        return d.promise;

    }



    $scope.changeTagsRecordCollection = function(){

        $http.put(AppService.url + '/administrator/update/record/tags', {tags: $scope.record.tags, id:$scope.record.id})
            .then(
                function successCallback(response){

                    console.log(response.data);

                },
                function errorCallback(reason){

                }
            )

    }



    $scope.getAllTagsFragment = function(bool, $index, id, action, $tag){


        $http.get(AppService.url + '/get/all/tags')
            .then(
                function successCallback(response){



                    if(bool){
                        $scope.fullfragment.all[$index] = $filter('tagsfilter')(response.data, $scope.fullfragment.current[$index]);
                    }else{
                        $scope.fullfragment.all[$index] = response.data;
                    }

                    $scope.changeTagsFragmentCollection($index, id, action, $tag);


                },
                function errorCallback(reason){

                }
            )


    }


    $scope.changeTagsFragmentCollection = function($index,id, action, tag){


        $http.put(AppService.url + '/administrator/update/fragment/tags', {tags: $scope.fullfragment.current[$index], id:id, action:action, tag:tag})
            .then(
                function successCallback(response){

                    console.log(response.data);
                    $scope.getAllTags(true);


                    //if(response.data!=null) {
                    //
                    //    angular.forEach($scope.fullfragment.all, function (item, key) {
                    //
                    //        if (key != $index) {
                    //            $scope.fullfragment.all[key].push(response.data);
                    //        }
                    //
                    //    });
                    //
                    //}


                },
                function errorCallback(reason){

                }
            )

    }






    $scope.loadTags = function(query) {

        return $http.get('/get/tags/by/query?guery='+query);

    };


    ///////////////////////////////////////////////////////////////////////////////////////////



    $scope.changePlacesFragmentCollection = function($index,id, action, place){



        $http.put(AppService.url + '/administrator/update/fragment/places', {places: $scope.fullfragment.currentplace[$index], id:id, action:action, tag:place})
            .then(
                function successCallback(response){

                    //console.log(response.data);
                    $scope.getAllPlaces(true);


                    //if(response.data!=null) {
                    //
                    //    angular.forEach($scope.fullfragment.all, function (item, key) {
                    //
                    //        if (key != $index) {
                    //            $scope.fullfragment.all[key].push(response.data);
                    //        }
                    //
                    //    });
                    //
                    //}


                },
                function errorCallback(reason){

                }
            )

    }


    $scope.getAllPlacesFragment = function(bool, $index, id, action, $place){


        $http.get(AppService.url + '/get/all/places')
            .then(
                function successCallback(response){


                    if(bool){
                        $scope.fullfragment.allplaces[$index] = $filter('placesfilter')(response.data, $scope.fullfragment.currentplace[$index]);
                    }else{
                        $scope.fullfragment.allplaces[$index] = response.data;
                    }

                    $scope.changePlacesFragmentCollection($index, id, action, $place);


                },
                function errorCallback(reason){

                }
            )


    }


    $scope.loadPlaces = function(query) {

        return $http.get('/get/places/by/query?guery='+query);

    };




    //$scope.addTag = function(el){
    //
    //    var nel = {text:el.name, id:el.id};
    //    $scope.record.tags.push(nel);
    //    $scope.getAllTags(true);
    //
    //}
    //
    //$scope.removedOneTag = function(){
    //
    //    //console.log($scope.tags);
    //    $scope.getAllTags(true);
    //
    //}


    ///////////////////////Fragments//////////////////////////////////////


    $scope.updateSignifyFragment = function(data){

        $scope.datatoupdate = data;
        //console.log($scope.datatoupdate);
        //console.log($scope.datatoupdate.index);
        //console.log($scope.record.fragments[$scope.datatoupdate.index]);


        $http.put(AppService.url + '/administrator/update/fragment/intervals/get', {fid:$scope.datatoupdate.id})
            .then(
                function successCallback(response){

                    //console.log(response.data);
                    $scope.record.fragments[$scope.datatoupdate.index].intervals = response.data;

                },
                function errorCallback(reason){

                }
            )

    }


    $scope.removeLinkInterval = function($index, fid, iid){

        //var deferred = $q.defer();

        $http.put(AppService.url+'/administrator/link/fragment/interval/remove', {iid:iid, fid:fid})
            .then(
                function successCallback(response){

                    //deferred.resolve(1);
                    $scope.updateListInterval($index, fid);

                },
                function errorCallback(reason){

                    //deferred.resolve(0);

                }
            )

        //return deferred.promise;

    }


    $scope.updateListInterval = function($index, fid){

        $http.put(AppService.url + '/administrator/update/fragment/intervals/get', {fid:fid})
            .then(
                function successCallback(response){

                    //console.log(response.data);
                    $scope.record.fragments[$index].intervals = response.data;

                },
                function errorCallback(reason){

                }
            )

    }


    $scope.removeOneFragment = function($index,rid){

        $scope.index = $index;
        $scope.rid = rid;
        $scope.fragdialog = '';

    }

    $scope.removeFragmentAfterConfirm = function(){

        $http.delete(AppService.url+'/administrator/del/fragment/'+$scope.rid)
            .then(function(res){
                console.log(res);
                $scope.record.fragments.splice($scope.index,1);
                $scope.fragdialog = 'hidden';
                $scope.initData();
            })

    }



    $scope.menu = [
        ['bold', 'italic', 'underline'],
        ['remove-format']
    ];

    $scope.editFragment = function($index,rid){
        $scope.editfrag = '';
        $scope.reditid=rid;
        $scope.editindex = $index;
        $scope.fragedittext = $scope.record.fragments[$index].content;
    }


    $scope.updateFragmentContent = function(){

        $http.post(AppService.url+'/administrator/update/record/fragment',{rid:$scope.reditid,content:$scope.fragedittext})
            .then(function(res){

                $scope.record.fragments[$scope.editindex].content = res.data;

                $scope.editfrag = 'hidden';
                $scope.reditid = null;
                $scope.editindex = null;
                $scope.fragedittext = '';

            });

    }


    $scope.validateFragment = function($data){

        var reg = /^[0-9]*$/

        if(reg.test($data)){
            return true;
        }
        return 'nie prawidłowy format';

    }

    $scope.saveFragmentTime = function($data,fid){

        $http.put(AppService.url+'/administrator/update/time/fragment',{fid:fid,time:$data})
            .then(function(res){
                console.log(res);
            });
    }





}]);

app.controller('LinkRecordController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams', '$rootScope', 'ngDialog', '$sce', 'Upload', '$interval', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams,$rootScope, ngDialog, $sce, Upload,$interval) {

}]);



app.controller('LinkRecordsController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams', '$rootScope', 'ngDialog', '$sce', 'Upload', '$interval', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams,$rootScope, ngDialog, $sce, Upload,$interval) {

}]);



app.directive('fragmentLinkTime', function($http, DateService, AppService, $q){
    return {
        templateUrl: '/templates/admin/super/records/fragment-link-time.html',
        link: function (scope, element, attributes) {

            //console.log(attributes);
            //console.log($http);
            //console.log(DateService);
            scope.filter = {
                name:true,
                begin:true,
                end:true
            }

            scope.search = {
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


            scope.suggestintevals = [];


            scope.days_more = DateService.days_more;
            scope.days_less = DateService.days_less;
            scope.days_leap_feb = DateService.days_leap_feb;
            scope.days_feb = DateService.days_feb;

            scope.begin_vars = {};
            scope.end_vars = {};

            scope.begin_vars.days = scope.days_more;

            scope.begin_vars.months = DateService.months;

            scope.begin_vars.years = DateService.createYearsFromTo(1900,new Date().getFullYear());

            scope.end_vars.days = scope.days_more;

            scope.end_vars.months = DateService.months;

            scope.end_vars.years = DateService.createYearsFromTo(1900,new Date().getFullYear());


            scope.checkIsLeapYear = DateService.checkIsLeapYear;


            scope.alertaddinterval = 'hidden';
            scope.intervalwaslink = 'hidden';


            scope.clearIntervalFields = function(){

                scope.search = {
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
            }



            scope.removeTagAdded = function($index, iid, fid){

                $http.put(AppService.url+'/administrator/link/fragment/interval/remove', {iid:iid, fid:fid})
                    .then(
                        function successCallback(response){
                            console.log(response);
                            scope.fragment.intervals.splice($index, 1);

                        },
                        function errorCallback(reason){

                        }
                    )

            }


            scope.changeDaysListBegin = function(year){

                var er = /^-?[0-9]+$/;


                if(scope.search.begin.month!=null) {
                    if (scope.search.begin.month == 2) {

                        if (scope.checkIsLeapYear(year)) {
                            scope.begin_vars.days = scope.days_leap_feb;
                        } else {
                            scope.begin_vars.days = scope.days_feb;
                        }

                    }else{

                        var number = scope.search.begin.month/2;

                        if(scope.search.begin.month==1){
                            scope.begin_vars.days = scope.days_more;
                        }else if(DateService.months[scope.search.begin.month-1].type=='less') {
                            scope.begin_vars.days = scope.days_less;
                        }else if(DateService.months[scope.search.begin.month-1].type=='more') {
                            scope.begin_vars.days = scope.days_more;
                        }

                    }
                }

            }


            scope.changeDaysListEnd = function(year){

                var er = /^-?[0-9]+$/;


                if(scope.search.end.month!=null) {
                    if (scope.search.end.month == 2) {

                        if (scope.checkIsLeapYear(year)) {
                            scope.end_vars.days = scope.days_leap_feb;
                        } else {
                            scope.end_vars.days = scope.days_feb;
                        }

                    }else{

                        var number = scope.search.end.month/2;

                        if(scope.search.end.month==1){
                            scope.end_vars.days = scope.days_more;
                        }else if(DateService.months[scope.search.end.month-1].type=='less') {
                            scope.end_vars.days = scope.days_less;
                        }else if(DateService.months[scope.search.end.month-1].type=='more') {
                            scope.end_vars.days = scope.days_more;
                        }
                    }
                }

            }


            scope.addIntervalToFragment = function($index, id){

                $http.put(AppService.url+'/administrator/link/fragment/by/interval', {id:id, fid:scope.fragment.id})
                    .then(
                        function successCallback(response){

                            scope.intervalwaslink = 'hidden';

                            if(response.data.success){
                                scope.suggestintevals.splice( $index, 1 );
                                scope.fragment.intervals.unshift(response.data.interval);

                            }else{
                                scope.intervalwaslink = '';
                            }


                        },
                        function errorCallback(reason){

                        }
                    )

            }


            ////////////////////////////////////////////////////////////////////////////////////////////////////

            scope.checkInterval = function(){

                //scope.search
                //scope.fragment.id
                scope.intervalwaslink = 'hidden';

                $http.post(AppService.url+'/administrator/check/is/interval', {data:scope.search, id:scope.fragment.id, filter:scope.filter})
                    .then(
                        function successCallback(response){
                            console.log(response.data);
                            scope.suggestintevals = response.data.intervals;
                        },
                        function errorCallback(reason){

                        }
                    )

            }

            ////////////////////////////////////////////////////////////////////////////////////////////////////


            scope.addInterval = function(){

                //var defeder = $q.defer();

                if(scope.search.begin.year!=null && scope.search.end.year!=null) {

                    scope.alertaddinterval = 'hidden';

                    $http.post(AppService.url + '/administrator/add/new/interval', {
                            data: scope.search,
                            id: scope.fragment.id,
                            filter: scope.filter
                        })
                        .then(
                            function successCallback(response) {

                                console.log(response.data);

                                if(response.data.success){
                                    scope.fragment.intervals.unshift(response.data.interval);
                                }else{
                                    scope.showSuggestIntervals(response.data);
                                }

                            },
                            function errorCallback(reason) {

                            }
                        )

                }else{
                    scope.alertaddinterval = '';
                }

            }



            scope.showSuggestIntervals = function(data){

                scope.suggestintevals = data.intervals;

            }




            ////////////////////////////////////////////////////////////////////////////////////////////////////


            attributes.$observe('signifyFragment', function() {

                if(attributes.signifyFragment!='') {

                    scope.fragment = JSON.parse(attributes.signifyFragment);
                    scope.show_signify = true;

                }else{
                    scope.show_signify = false;
                }

                //console.log(scope.fragment);


            });
        }


    }



    //////////////////////////////////////////////////////////////////////////////////////////////////

});




app.controller('IntervalRecordsController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams', '$rootScope', 'ngDialog', '$sce', 'Upload', '$interval', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams,$rootScope, ngDialog, $sce, Upload,$interval) {

    $scope.initData = function() {

        console.log($routeParams);

        $scope.signify_fragment = null;

        $scope.record = {
            id: $routeParams.id,
            rdata: null,
            fragments: null,
            intervals: []
        }


        $scope.fullfragment = {
            record: null,
            intervals: [],
            baseinterval:[]
        };


        $scope.fragmentsloaded = false;


        $scope.intervals = null;
        $scope.intervalscloud = null;

        $scope.getFullRecordDataIntervals();
        //$scope.getAllTags(false);


    }

    $scope.getFullRecordDataIntervals = function(){

        $http.get(AppService.url + '/administrator/get/full/record/data/intervals/'+$scope.record.id)
            .then(
                function successCallback(response){

                    //console.log(response.data);
                    $scope.record.rdata = response.data.record;
                    $scope.record.fragments = response.data.fragments;
                    $scope.record.intervals = response.data.intervals;
                    $scope.record.intervalsbase = response.data.intervalsbase;
                    //console.log(response.data);
                    //$scope.makeIntervalBaseForFragments($scope.record.fragments, $scope.record.intervalsbase);

                },
                function errorCallback(reason){

                }
            );

    }


    $scope.makeIntervalBaseForFragments = function(fragments, base){


        //angular.forEach(fragments, function(item, key){
        //
        //
        //    $scope.fullfragment.all.push(
        //        base
        //    );
        //
        //    $scope.fullfragment.current.push(
        //        item.intervals
        //    );
        //
        //
        //});
        //
        //
        //$scope.fragmentsloaded = false;


    }


}]);


app.config(function($routeProvider, $locationProvider) {

    $routeProvider.
    when('/', {
        templateUrl: '/templates/admin/super/records/master.html',
        controller: 'RecordsController'
    }).
    when('/add', {
        templateUrl: '/templates/admin/super/records/new-record.html',
        controller: 'NewRecordController'
    }).
    when('/edit/:id', {
        templateUrl: '/templates/admin/super/records/edit-record.html',
        controller: 'EditRecordController'
    }).
    when('/tags/:id', {
        templateUrl: '/templates/admin/super/records/tag-record.html',
        controller: 'TagsRecordController'
    }).
    when('/tags/:id/fragment/:fid', {
        templateUrl: '/templates/admin/super/records/tag-fragment-record.html',
        controller: 'TagsRecordController'
    }).
    when('/link/:id', {
        templateUrl: '/templates/admin/super/records/link-record.html',
        controller: 'LinkRecordController'
    }).
    when('/linked', {
        templateUrl: '/templates/admin/super/records/link-records.html',
        controller: 'LinkRecordsController'
    }).
    when('/intervals/:id', {
        templateUrl: '/templates/admin/super/records/intervals-records.html',
        controller: 'IntervalRecordsController'
    });
    //otherwise({redirectTo: '/'});
    //
    $locationProvider.html5Mode({
        enabled: false,
        requireBase: false
    });

});