var app = angular.module('app',['ngSanitize', 'ngRoute', 'ui.tinymce', 'xeditable', 'ui.bootstrap.datetimepicker','ngDialog','wysiwyg.module','ngFileUpload', 'ngTagsInput', 'ui.select', 'angular-img-cropper'], function($interpolateProvider) {
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
        customerurl: 'http://adminahm.zbiglem.pl'
    };
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
        templateUrl: '/templates/admin/super/interviewees/search.html'
    };
});


app.directive('statusBtnInList', function() {
    return {
        templateUrl: '/templates/admin/super/interviewees/status.html',
        link: function(scope, element, attributes, searchFactory){

            //console.log(attributes);
            scope.status = attributes.statusBtnInList;
            scope.id = attributes.artid;

        }
    };
});


app.directive('deleteElement', function(AppService, $http, $q, $filter, $route) {
    return {
        templateUrl: '/templates/admin/super/interviewees/delete.html',
        link: function(scope, element, attributes, searchFactory){



            //console.log(attributes);
            scope.id = attributes.id;

            scope.relations = [
                {
                    method:'records',
                    name:'Nagrania'
                }

            ];

            scope.model = 'App\\Entities\\Interviewee';

            scope.getBeforeDeleteRaport = function(){

                var deferred = $q.defer();

                $http.post(AppService.url+'/administrator/interviewee/get/raport/before/delete', {relations:scope.relations, id:attributes.id})
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

                $http.put(AppService.url+'/administrator/delete/interviewee', {relations:scope.relations, id:attributes.id, model:scope.model})
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


app.directive('resize', function ($window,$timeout) {
    return function (scope, element, attr) {

        var w = angular.element($window);
        scope.$watch(function () {
            return {
                'h': w.height(),
                'w': w.width()
            };
        }, function (newValue, oldValue) {

            scope.windowHeight = newValue.h;
            scope.windowWidth = newValue.w;


        }, true);

        w.bind('resize', function () {
            scope.$apply();
        });
    }
});


app.controller('GlobalController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout) {

    $timeout(function(){

        angular.element(document.getElementById('body')).removeClass('alphaHide');
        angular.element(document.getElementById('body')).addClass('alphaShow');

    },1000)

}]);


app.controller('IntervieweesController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams','$rootScope', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams,$rootScope) {


    $scope.initData = function(){

        $scope.limit = 10;
        $scope.start = 0;
        $scope.frase = null;
        $scope.searchcolumns = {
            name:true,
            surname:true,
            biography:true
        };

        $scope.filter = {
            status: {name:'Wszystkie', number:null},
        }

        $scope.statuses = [
            {name:'Wszystkie', number:null},
            {name:'Opublikowany', number:1},
            {name:'Nieopublikowany', number:0}
        ]



        if($routeParams.start){

            if($routeParams.frase){
                $scope.frase = $routeParams.frase;
            }else{
                $scope.frase = null;
            }

            if($routeParams.status){
                $scope.filter.status = JSON.parse($routeParams.status);
            }else{
                $scope.filter.status = {name:'Wszystkie', number:null};
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

            if($routeParams.status){
                $scope.filter.status = JSON.parse($routeParams.status);
            }else{
                $scope.filter.status = {name:'Wszystkie', number:null};
            }

            $scope.getElements(0);
        }


    }





    //Get Data Logic

    $scope.getElements = function(iterstart){

        if(iterstart) {
            iterstart = parseInt(iterstart);
        }

        $http.post(AppService.url + '/administrator/get/interviewees',
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


    $rootScope.$on('go-to-page',function(event,attr){

        //console.log(attr);
        //console.log($routeParams);
        $scope.halfclick = false;
        $scope.iterstart = attr.nr-1;
        $scope.start = $scope.limit*$scope.iterstart;

        if($routeParams.frase || $routeParams.status){

            var search = {start:$scope.start,iterstart:$scope.iterstart,halfclick:$scope.halfclick};

            if($routeParams.frase){
                search.frase = $routeParams.frase;
            }

            if($routeParams.status){
                search.status = JSON.stringify($scope.filter.status);
            }

            $location.path('/').search(search);

        }else{
            $location.path('/').search({start:$scope.start,halfclick:$scope.halfclick,iterstart:$scope.iterstart,status:JSON.stringify($scope.filter.status)});
        }

    });


    $scope.changePage = function(start){

        $scope.halfclick = false;
        $scope.start = $scope.limit*start;
        //$scope.getElements(start);

        if($scope.frase!=null || $scope.frase!=''){
            $location.path('/').search({start:$scope.start,iterstart:start,halfclick:$scope.halfclick,frase:$scope.frase,status:JSON.stringify($scope.filter.status)});
        }else{
            $location.path('/').search({start:$scope.start,halfclick:$scope.halfclick,iterstart:start,status:$scope.filter.status});
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
            $location.path('/').search({start:$scope.start,iterstart:start,halfclick:$scope.halfclick,frase:$scope.frase,status:JSON.stringify($scope.filter.status)});
        }else{
            $location.path('/').search({start:$scope.start,halfclick:$scope.halfclick,iterstart:start,status:JSON.stringify($scope.filter.status)});
        }

    }


    $scope.changePageNext = function(){

        $scope.halfclick = false;
        var start = $scope.iterstart+1;
        $scope.start = $scope.limit*start;
        //$scope.getElements(start);
        if($scope.frase!=null || $scope.frase!=''){
            $location.path('/').search({start:$scope.start,iterstart:start,halfclick:$scope.halfclick,frase:$scope.frase,status:JSON.stringify($scope.filter.status)});
        }else{
            $location.path('/').search({start:$scope.start,halfclick:$scope.halfclick,iterstart:start,status:JSON.stringify($scope.filter.status)});
        }

    }


    $scope.changeShowFirst = function(){

        $scope.start = 0;
        //$scope.getElements(0);
        if($scope.frase!=null || $scope.frase!=''){
            $location.path('/').search({start:$scope.start,iterstart:$scope.start,halfclick:$scope.halfclick,frase:$scope.frase,status:JSON.stringify($scope.filter.status)});
        }else{
            $location.path('/').search({start:$scope.start,halfclick:$scope.halfclick,iterstart:$scope.start,status:JSON.stringify($scope.filter.status)});
        }

    }

    $scope.changeShowLast = function(){

        $scope.start = $scope.pages*$scope.limit;
        //$scope.getElements($scope.pages);
        if($scope.frase!=null || $scope.frase!=''){
            $location.path('/').search({start:$scope.start,iterstart:$scope.start,halfclick:$scope.halfclick,frase:$scope.frase,status:JSON.stringify($scope.filter.status)});
        }else{
            $location.path('/').search({start:$scope.start,halfclick:$scope.halfclick,iterstart:$scope.pages,status:JSON.stringify($scope.filter.status)});
        }

    }





    //Get Data Logic



    $scope.searchSubmit = function(){

        $scope.start = 0;
        $location.path('/').search({frase:$scope.frase,halfclick:$scope.halfclick,iterstart:$scope.start,status:JSON.stringify($scope.filter.status)});
        //$scope.getElements(0);


    }

    $scope.changeIntervieweeData = function(field, value, id){


        $http.put(AppService.url + '/administrator/update/interviewee/data',
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


app.controller('NewIntervieweeController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams', '$rootScope', 'ngDialog', '$sce', 'Upload', '$interval', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams,$rootScope, ngDialog, $sce, Upload,$interval) {


    $scope.initData = function(){

        $scope.inter = {
            name:'',
            surname:'',
            portrait:null,
            records:null,
            disk:'portraits',
            records:[],
            biography:''
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

        $scope.getListRecords();

        $scope.cropper = {};
        $scope.cropper.sourceImage = null;
        $scope.cropper.croppedImage   = null;
        $scope.bounds = {};
        $scope.bounds.left = 0;
        $scope.bounds.right = 0;
        $scope.bounds.top = 0;
        $scope.bounds.bottom = 0;

        //$scope.canvas_width=900;

        $scope.file = null;

        $scope.upload_status = false;

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


    ///////////////////////////////Upload////////////////////////////////////


    $scope.$watch('file', function () {
        //$scope.uploadFiles($scope.file);
        //console.log($scope.file);
    });


    $scope.uploadFiles = function (file) {

        if(file) {

            //console.log(file);
            //////////////////////////////////////////////////

            Upload.upload({
                url: AppService.url + '/upload/image/to/'+$scope.inter.disk,
                fields: {ftype:$scope.file.type, fname:$scope.file.name , inter:$scope.inter},
                file: file
            }).progress(function (evt) {

                var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                $scope.log = 'progress: ' + progressPercentage + '% ' + evt.config.file.name + '\n' + $scope.log;
                //$log.info(progressPercentage);
                $scope.progress = progressPercentage;

            }).success(function (data, status, headers, config) {

                $log.info(data);

                if(data.success){
                    $scope.upload_status = true;
                    $scope.inter.portrait = data.data;

                }

                $scope.progress = 0;

                $timeout(function () {
                    $scope.log = 'file: ' + config.file.name + ', Response: ' + JSON.stringify(data) + '\n' + $scope.log;
                });

            }).error(function (data, status, headers, config) {
                //$log.info(data);
            });

        }

    };



    $scope.removeUplodedFile = function(imagedata){

        $http.put(AppService.url+'/remove/image/from/'+$scope.inter.disk, {fname:imagedata.fname})
            .then(
                function successCallback(response){
                    $log.info(response.data);

                    if(response.data.success){
                        $scope.upload_status = false;
                        //$scope.cropper = {};
                        //$scope.cropper.sourceImage = null;
                        $scope.cropper.croppedImage   = null;
                        $scope.file = null;
                        //$scope.bounds = {};
                        //$scope.bounds.left = 0;
                        //$scope.bounds.right = 0;
                        //$scope.bounds.top = 0;
                        //$scope.bounds.bottom = 0;
                        $scope.inter.portrait = null;
                    }

                },
                function errorCallback(reason){

                }
            )

    }



    ////////////////////////////////////Crop/////////////////////////////////////////////


    //$scope.myImage='';
    //$scope.myCroppedImage='';
    //
    //var handleFileSelect=function(evt) {
    //    var file=evt.currentTarget.files[0];
    //    var reader = new FileReader();
    //    reader.onload = function (evt) {
    //        $scope.$apply(function($scope){
    //            $scope.myImage=evt.target.result;
    //        });
    //    };
    //    reader.readAsDataURL(file);
    //};
    //angular.element(document.querySelector('#fileInput')).on('change',handleFileSelect);


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


    $scope.checkName = function(){

        if($scope.inter.name.length>1){

            $scope.valid.name = true;
            $scope.classes.name = 'has-success';

        }else{

            $scope.valid.name = false;
            $scope.classes.name = 'has-error';
        }

    }


    $scope.checkSurname = function(){

        if($scope.inter.surname.length>1){

            $scope.valid.surname = true;
            $scope.classes.surname = 'has-success';

        }else{

            $scope.valid.surname = false;
            $scope.classes.surname = 'has-error';
        }

    }


    $scope.createNewInterviewee = function(){

        $scope.checkName();
        $scope.checkSurname();

        //console.log($scope.inter);

        if($filter('checkfalse')($scope.valid)){

            $scope.intersaving = '';

            $http.put(AppService.url+'/administrator/create/new/interviewee', $scope.inter)
                .then(
                    function successCallback(response){


                        //$log.info(response.data);
                        if(response.data.success){
                            $scope.intersaving = 'hidden';
                            $scope.intersaved = '';
                            $scope.initData();
                        }


                    },
                    function errorCallback(reason){

                    }
                )

        }

    }


}]);


app.controller('EditIntervieweeController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams', '$rootScope', 'ngDialog', '$sce', 'Upload', '$interval', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams,$rootScope, ngDialog, $sce, Upload,$interval) {

    $scope.initData = function(){

        $scope.inter = {
            id:$routeParams.id,
            name:'',
            surname:'',
            portrait:null,
            disk:'portraits',
            records:[],
            biography:''
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


        $scope.has_image=false;


        $scope.cropper = {};
        $scope.cropper.sourceImage = null;
        $scope.cropper.croppedImage   = null;
        $scope.bounds = {};
        $scope.bounds.left = 0;
        $scope.bounds.right = 0;
        $scope.bounds.top = 0;
        $scope.bounds.bottom = 0;

        //$scope.canvas_width=900;

        $scope.file = null;

        $scope.upload_status = false;


        $scope.getListRecords().then(
            function(data){
                $scope.getIntervieweeData();
            },
            function(reason){

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
                    deferred.resolve(1);
                },
                function errorCallback(reason){
                    deferred.reject();
                }
            )

        return deferred.promise;

    }

    $scope.getIntervieweeData = function(){

        var deferred = $q.defer();

        $http.get(AppService.url+'/administrator/get/interviewee/'+$scope.inter.id)
            .then(
                function successCallback(response){
                    //console.log(response.data);

                    if(response.data.portrait!='default.jpg' && response.data.portrait!=''){
                        $scope.upload_status = true;
                        $scope.has_image = true;
                    }

                    $scope.inter.name=response.data.name;
                    $scope.inter.surname=response.data.surname;
                    $scope.inter.portrait=response.data.portrait;
                    $scope.inter.disk=response.data.disk;
                    $scope.inter.records=response.data.records;
                    $scope.inter.biography=response.data.biography;

                    deferred.resolve(1);
                },
                function errorCallback(reason){
                    deferred.reject();
                }
            )

        return deferred.promise;

    }


    ///////////////////////////////Upload////////////////////////////////////


    $scope.$watch('file', function () {
        //$scope.uploadFiles($scope.file);
        //console.log($scope.file);
    });


    $scope.uploadFiles = function (file) {

        if(file) {

            //console.log(file);
            //////////////////////////////////////////////////

            Upload.upload({
                url: AppService.url + '/upload/image/to/'+$scope.inter.disk,
                fields: {ftype:$scope.file.type, fname:$scope.file.name , inter:$scope.inter},
                file: file
            }).progress(function (evt) {

                var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                $scope.log = 'progress: ' + progressPercentage + '% ' + evt.config.file.name + '\n' + $scope.log;
                //$log.info(progressPercentage);
                $scope.progress = progressPercentage;

            }).success(function (data, status, headers, config) {

                $log.info(data);

                if(data.success){
                    $scope.upload_status = true;
                    $scope.inter.portrait = data.data;

                }

                $scope.progress = 0;

                $timeout(function () {
                    $scope.log = 'file: ' + config.file.name + ', Response: ' + JSON.stringify(data) + '\n' + $scope.log;
                });

            }).error(function (data, status, headers, config) {
                //$log.info(data);
            });

        }

    };



    $scope.removeUplodedFile = function(imagedata){

        $scope.upload_status = false;
        $scope.inter.portrait = null;

        //$http.put(AppService.url+'/remove/image/from/'+$scope.inter.disk, {fname:imagedata})
        //    .then(
        //        function successCallback(response){
        //            $log.info(response.data);
        //
        //            if(response.data.success){
        //                $scope.upload_status = false;
        //                //$scope.cropper = {};
        //                //$scope.cropper.sourceImage = null;
        //                $scope.cropper.croppedImage   = null;
        //                $scope.file = null;
        //                //$scope.bounds = {};
        //                //$scope.bounds.left = 0;
        //                //$scope.bounds.right = 0;
        //                //$scope.bounds.top = 0;
        //                //$scope.bounds.bottom = 0;
        //                $scope.inter.portrait = null;
        //            }
        //
        //        },
        //        function errorCallback(reason){
        //
        //        }
        //    )

    }


    $scope.restoreBaseImage = function(){

        $http.get(AppService.url+'/administrator/get/interviewee/'+$scope.inter.id)
            .then(
                function successCallback(response){
                    //console.log(response.data);
                    $scope.upload_status = true;
                    $scope.inter.portrait = response.data.portrait;
                },
                function errorCallback(reason){

                }
            )

    }

    ////////////////////////////////////Crop/////////////////////////////////////////////

    ////////////////////////////////////Wyswig/////////////////////////////////////////////


    $scope.model = {};


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



    /////////////////////////////////////////////////////////////////////////////////////////


    $scope.checkName = function(){

        if($scope.inter.name.length>1){

            $scope.valid.name = true;
            $scope.classes.name = 'has-success';

        }else{

            $scope.valid.name = false;
            $scope.classes.name = 'has-error';
        }

    }


    $scope.checkSurname = function(){

        if($scope.inter.surname.length>1){

            $scope.valid.surname = true;
            $scope.classes.surname = 'has-success';

        }else{

            $scope.valid.surname = false;
            $scope.classes.surname = 'has-error';
        }

    }


    $scope.createNewInterviewee = function(){

        $scope.checkName();
        $scope.checkSurname();

        //console.log($scope.inter);

        if($filter('checkfalse')($scope.valid)){

            $scope.intersaving = '';

            $http.put(AppService.url+'/administrator/update/full/interviewee/data/'+$scope.inter.id, $scope.inter)
                .then(
                    function successCallback(response){

                        $log.info(response.data);
                        if(response.data.success) {
                            $scope.intersaving = 'hidden';
                            $scope.intersaved = '';
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
        templateUrl: '/templates/admin/super/interviewees/master.html',
        controller: 'IntervieweesController'
    }).
    when('/add', {
        templateUrl: '/templates/admin/super/interviewees/new-interviewee.html',
        controller: 'NewIntervieweeController'
    }).
    when('/edit/:id', {
        templateUrl: '/templates/admin/super/interviewees/edit-interviewee.html',
        controller: 'EditIntervieweeController'
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