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

app.directive('leftListArticles', function() {
    return {
        templateUrl: '/templates/admin/super/articles/left.html'
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
                    type:'manyToMany',
                    method:'galleries',
                    name:'Galerie'
                },
                {
                    type:'oneToMany',
                    method:'category',
                    name:'Kategorie'
                }

            ];

            scope.model = 'App\\Entities\\Article';

            scope.getBeforeDeleteRaport = function(){

                var deferred = $q.defer();

                $http.post(AppService.url+'/administrator/article/get/raport/before/delete', {relations:scope.relations, id:attributes.id})
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

                $http.put(AppService.url+'/administrator/delete/article', {relations:scope.relations, id:attributes.id, model:scope.model})
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



app.directive('statusBtnInList', function() {
    return {
        templateUrl: '/templates/admin/super/articles/status.html',
        link: function(scope, element, attributes, searchFactory){

            //console.log(attributes);
            scope.status = attributes.statusBtnInList;
            scope.id = attributes.artid;

        }
    };
});


app.directive('mainBtn', function() {
    return {
        templateUrl: '/templates/admin/super/articles/main-status.html',
        link: function(scope, element, attributes, searchFactory){

            //console.log(attributes);
            scope.mainstatus = attributes.mainBtn;
            scope.id = attributes.artid;

        }
    };
});



app.directive('mediaLibrary', function() {
    return {
        templateUrl: '/templates/admin/super/articles/media-library.html',
        link: function(scope, element, attributes){

            console.log(attributes);

        }
    };
});


app.directive('mediaLibraryForIntro', function() {
    return {
        templateUrl: '/templates/admin/super/articles/media-library-for-intro.html',
        link: function(scope, element, attributes){

            //console.log(attributes);

        }
    };
});


app.directive('cssConfig', function() {
    return {
        templateUrl: '/templates/admin/super/articles/css-config.html',
        link: function(scope, element, attributes){

            //console.log(attributes);

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


app.controller('GlobalController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout', 'ngDialog', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout, ngDialog) {

    $timeout(function(){

        angular.element(document.getElementById('body')).removeClass('alphaHide');
        angular.element(document.getElementById('body')).addClass('alphaShow');

    },500)

}]);


app.controller('ArticlesController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams','$rootScope', 'ngDialog', '$sce', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams,$rootScope,ngDialog,$sce) {


    $scope.initData = function(){

        $scope.limit = 10;
        $scope.start = 0;
        $scope.frase = null;
        $scope.searchcolumns = {
            title:true,
            intro:true,
            content:true
        };

        $scope.datainstock = [];
        $scope.calclass = [];

        $scope.filter = {
            category:{id:0,name:'Wszystkie'}
        };

        $scope.getCategories();


        if($routeParams.start){

            if($routeParams.frase){
                $scope.frase = $routeParams.frase;
            }else{
                $scope.frase = null;
            }

            if($routeParams.category){
                $scope.filter.category = JSON.parse($routeParams.category);
            }else{
                $scope.filter.category = {id:0,name:'Wszystkie'};
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

            if($routeParams.category){
                $scope.filter.category = JSON.parse($routeParams.category);
            }else{
                $scope.filter.category = {id:0,name:'Wszystkie'};
            }

            $scope.getElements(0);
        }

    }


    //Get Data Logic


    $scope.openSubWindow = function(temp,klasa){
        ngDialog.open({
            scope: $scope,
            template: temp,
            className: klasa,
            cache: true,
            overlay: false
        });
        $scope.$on('ngDialog.closed', function (e, $dialog) {

        });
    }


    $scope.getCategories = function(){

        $http.get(AppService.url + '/get/article/categories')
        .then(
            function successCallback(response) {

                //console.log(response);
                var categories = response.data;
                categories.splice(0, 0,{id:0,name:'Wszystkie'});
                $scope.categories = categories;



            },
            function errorCallback(response) {

            }
        );

}

    $scope.getElements = function(iterstart){

        if(iterstart) {
            iterstart = parseInt(iterstart);
        }

        $http.post(AppService.url + '/administrator/get/articles',
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

            if($routeParams.category){
                search.category = JSON.stringify($scope.filter.category);
            }

            $location.path('/').search(search);

        }else{
            $location.path('/').search({start:$scope.start,halfclick:$scope.halfclick,iterstart:$scope.iterstart,category:JSON.stringify($scope.filter.category)});
        }

    });



    $scope.changePage = function(start){

        $scope.halfclick = false;
        $scope.start = $scope.limit*start;
        //$scope.getElements(start);

        if($scope.frase!=null || $scope.frase!=''){
            $location.path('/').search({start:$scope.start,iterstart:start,halfclick:$scope.halfclick,frase:$scope.frase,category:JSON.stringify($scope.filter.category)});
        }else{
            $location.path('/').search({start:$scope.start,halfclick:$scope.halfclick,iterstart:start,category:JSON.stringify($scope.filter.category)});
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
            $location.path('/').search({start:$scope.start,iterstart:start,halfclick:$scope.halfclick,frase:$scope.frase,category:JSON.stringify($scope.filter.category)});
        }else{
            $location.path('/').search({start:$scope.start,halfclick:$scope.halfclick,iterstart:start,category:JSON.stringify($scope.filter.category)});
        }

    }


    $scope.changePageNext = function(){

        $scope.halfclick = false;
        var start = $scope.iterstart+1;
        $scope.start = $scope.limit*start;
        //$scope.getElements(start);
        if($scope.frase!=null || $scope.frase!=''){
            $location.path('/').search({start:$scope.start,iterstart:start,halfclick:$scope.halfclick,frase:$scope.frase,category:JSON.stringify($scope.filter.category)});
        }else{
            $location.path('/').search({start:$scope.start,halfclick:$scope.halfclick,iterstart:start,category:JSON.stringify($scope.filter.category)});
        }

    }


    $scope.changeShowFirst = function(){

        $scope.start = 0;
        //$scope.getElements(0);
        if($scope.frase!=null || $scope.frase!=''){
            $location.path('/').search({start:$scope.start,iterstart:$scope.start,halfclick:$scope.halfclick,frase:$scope.frase,category:JSON.stringify($scope.filter.category)});
        }else{
            $location.path('/').search({start:$scope.start,halfclick:$scope.halfclick,iterstart:$scope.start,category:JSON.stringify($scope.filter.category)});
        }

    }

    $scope.changeShowLast = function(){

        $scope.start = $scope.pages*$scope.limit;
        //$scope.getElements($scope.pages);
        if($scope.frase!=null || $scope.frase!=''){
            $location.path('/').search({start:$scope.start,iterstart:$scope.start,halfclick:$scope.halfclick,frase:$scope.frase,category:JSON.stringify($scope.filter.category)});
        }else{
            $location.path('/').search({start:$scope.start,halfclick:$scope.halfclick,iterstart:$scope.pages,category:JSON.stringify($scope.filter.category)});
        }

    }


    //Get Data Logic



    $scope.searchSubmit = function(){

        $scope.start = 0;
        $location.path('/').search({frase:$scope.frase,halfclick:$scope.halfclick,iterstart:$scope.start,category:JSON.stringify($scope.filter.category)});
        //$scope.getElements(0);


    }


    $scope.changeArticleData = function(field, value, id){

        $http.put(AppService.url + '/administrator/update/article/data',
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


    $scope.changeArticleDataMain = function(value, id, inverse){

        $http.put(AppService.url + '/administrator/update/main/article',
            {
                inverse:inverse,
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


    /////////////////////Roberto Dialog///////////////////////////////////////

    $scope.delegate = function(fn,data){
        fn(data);
    }


    $scope.toTrustedHTML = function( html ){
        return $sce.trustAsHtml( html );
    }


    $scope.onTimeSet = function (newDate, id, $index) {
        var date = new Date(newDate);
        var tosave = {id:id, date:date, index:$index};

        $scope.forConfirmData = {
            fn: $scope.updateArticleData,
            item: tosave,
            query: "Czy checesz zmienić datę publikacji?"
        };
        $scope.openSubWindow('/templates/confirm_renderer.html','ngdialog-theme-dialog');

    }



    $scope.updateArticleData = function(tosave){


        $http.put(AppService.url+'/administrator/update/publish/date/'+tosave.id, tosave)
            .then(
                function successCallback(response){
                    //$log.info(response);
                    $scope.getElements($scope.iterstart);
                },
                function errorCallback(reason){

                }
        );


    }


    /////////////////////Roberto Dialog///////////////////////////////////////



}]);


app.controller('NewArticleController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams', '$rootScope', 'ngDialog', '$sce', 'Upload', '$interval', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams,$rootScope, ngDialog, $sce, Upload,$interval) {





    $scope.initData = function(){

        $scope.loading = 'hidden';

        $scope.loading_disk = true;

        $scope.arttype = 'site';
        //'site','external'

        $scope.uploaded = [];

        $scope.newartdata = {
            title:'',
            introtext:'',
            fulltext:'',
            intro_image:null,
            external_url:'',
            category_id:1
        };

        $scope.imagetoadd = null;
        $scope.media = null;
        $scope.mediaconfig = [];
        $scope.uploadmediaconfig = [];

        $scope.disk = 'photos';
        $scope.type = 'disk';
        $scope.getCategories();
        // $scope.getMediaCloud();


        ////////Valid//////////

        $scope.if_title_valid = true;
        $scope.if_exurl_valid = true;


    }


    $scope.getCategories = function(){

        $http.get(AppService.url + '/get/article/categories')
            .then(
                function successCallback(response) {

                    //console.log(response);
                    $scope.categories = response.data;

                },
                function errorCallback(response) {

                }
            );

    }



    $scope.getMediaCloud = function(){

        $scope.loading_disk = true;

        $http.get(AppService.url + '/get/media/images/'+$scope.type+'/'+$scope.disk)
            .then(
                function successCallback(response) {

                    //console.log(response.data);
                    $scope.loading_disk = false;
                    $scope.media = response.data;

                },
                function errorCallback(response) {

                }
            );


    }



    $scope.changeDisk = function(disk){
        $scope.disk = disk;
        $scope.getMediaCloud();
    }



    $scope.$watch('imagetoadd.image',function(newValue,oldValue){
        //console.log(newValue);
        if(newValue!=null){
           $scope.imagetoadd.maxwidth=$scope.imagetoadd.rootsize[0];
           $scope.imagetoadd.float='none';
        }
    });



    $scope.addSiteCollect = function(img){

    }




    ///Editor module

    $scope.model = {};


    $scope.model.customMenu = {
        'openMediaModal' : {
            tag : 'button',
            classes: 'btn btn-primary btn-md btn-add-image',
            attributes: [{name : 'ng-model',
                value:'openMediaCloud'},
                {name : 'type',
                    value : 'button'},
                {name : 'title',
                    value : 'Dodaj obrazek'},
                {name: 'ng-click',
                    value: 'openMediaModal()'},
            ],
            data: [{
                tag: 'i',
                text: ' Dodaj obrazek',
                classes: 'fa fa-picture-o'
            }]
        }
    };


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


    //function setSelectionRange(input, selectionStart, selectionEnd) {
    //    if (input.setSelectionRange) {
    //        input.focus();
    //        input.setSelectionRange(selectionStart, selectionEnd);
    //    }
    //    else if (input.createTextRange) {
    //        var range = input.createTextRange();
    //        range.collapse(true);
    //        range.moveEnd('character', selectionEnd);
    //        range.moveStart('character', selectionStart);
    //        range.select();
    //    }
    //}
    //
    //function setCaretToPos (input, pos) {
    //    setSelectionRange(input, pos, pos);
    //}


    $scope.clickImageInLibrary = function($index,img){


        //var input = $('#fulltextID');
        //console.log(input);
        //if(input.setSelectionRange) {
        //    input.focus();
        //    input.setSelectionRange(4, 4);
        //}else if (input.createTextRange) {
        //    var range = input.createTextRange();
        //    range.collapse(true);
        //    range.moveEnd('character', 4);
        //    range.moveStart('character', 4);
        //    range.select();
        //}

        //setCaretToPos(document.getElementById("fulltext"), 4);
        document.execCommand('insertHTML', false, '<img src="'+img.image+'/'+$scope.mediaconfig[$index].maxWidth+'"  class="img-responsive '+$scope.mediaconfig[$index].float+'">');
        angular.element(document.getElementById('modal-media-lib')).addClass('hidden');
        angular.element(document.getElementsByClassName('overflow-shadow-media')).addClass('hidden');



    }



    $scope.clickImageInLibraryUpload = function($index,img){


        //var input = $('#fulltextID');
        //console.log(input);
        //if(input.setSelectionRange) {
        //    input.focus();
        //    input.setSelectionRange(4, 4);
        //}else if (input.createTextRange) {
        //    var range = input.createTextRange();
        //    range.collapse(true);
        //    range.moveEnd('character', 4);
        //    range.moveStart('character', 4);
        //    range.select();
        //}

        //setCaretToPos(document.getElementById("fulltext"), 4);
        document.execCommand('insertHTML', false, '<img src="'+img.image+'/'+$scope.uploadmediaconfig[$index].maxWidth+'"  class="img-responsive '+$scope.uploadmediaconfig[$index].float+'">');
        angular.element(document.getElementById('modal-media-lib')).addClass('hidden');
        angular.element(document.getElementsByClassName('overflow-shadow-media')).addClass('hidden');



    }


    $scope.clickImageInLibraryIntroArticle = function($index,img){

        $scope.newartdata.intro_image = null;
        $timeout(function(){
            $scope.newartdata.intro_image = img;
        },1000);
        angular.element(document.getElementById('modal-media-lib-for-intro')).addClass('hidden');
        angular.element(document.getElementsByClassName('overflow-shadow-media')).addClass('hidden');

    }

    $scope.hideMediaModal = function(){
        angular.element(document.getElementById('modal-media-lib')).addClass('hidden');
        angular.element(document.getElementById('modal-media-lib-for-intro')).addClass('hidden');
        angular.element(document.getElementsByClassName('overflow-shadow-media')).addClass('hidden');
    }



    $scope.openMediaForIntro = function(){

        angular.element(document.getElementById('modal-media-lib-for-intro')).removeClass('hidden');
        angular.element(document.getElementsByClassName('overflow-shadow-media')).removeClass('hidden');

    }



    //$scope.changeMaxWidth = function(action,$index){
    //
    //    switch (action){
    //
    //        case 'plus':
    //
    //            if($scope.mediaconfig[$index].maxWidth!=$scope.mediaconfig[$index].validmaxwidth){
    //                $scope.mediaconfig[$index].maxWidth++;
    //            }
    //
    //            break;
    //
    //        case 'minus':
    //
    //            if($scope.mediaconfig[$index].maxWidth!=0){
    //                $scope.mediaconfig[$index].maxWidth--;
    //            }
    //
    //            break;
    //
    //    }
    //
    //}


    $scope.changeMaxWidthMouseDown = function(action,$index){

        switch (action){

            case 'plus':

                $scope.mediaconfig[$index].interval = $interval(function() {
                    if ($scope.mediaconfig[$index].maxWidth != $scope.mediaconfig[$index].validmaxwidth) {
                        $scope.mediaconfig[$index].maxWidth++;
                    }
                },10);

                break;

            case 'minus':

                $scope.mediaconfig[$index].interval = $interval(function() {
                    if($scope.mediaconfig[$index].maxWidth!=0){
                        $scope.mediaconfig[$index].maxWidth--;
                    }
                },10);

                break;

        }

    }


    $scope.changeMaxWidthMouseUp = function($index){

        $interval.cancel($scope.mediaconfig[$index].interval);

    }



    //$scope.changeMaxWidthUpload = function(action,$index){
    //
    //    switch (action){
    //
    //        case 'plus':
    //
    //            if($scope.uploadmediaconfig[$index].maxWidth!=$scope.uploadmediaconfig[$index].validmaxwidth){
    //                $scope.uploadmediaconfig[$index].maxWidth++;
    //            }
    //
    //            break;
    //
    //        case 'minus':
    //
    //            if($scope.uploadmediaconfig[$index].maxWidth!=0){
    //                $scope.uploadmediaconfig[$index].maxWidth--;
    //            }
    //
    //            break;
    //
    //    }
    //
    //}


    $scope.changeMaxWidthUploadMouseDown = function(action,$index){


        switch (action){

            case 'plus':

                $scope.uploadmediaconfig[$index].interval = $interval(function(){
                    if($scope.uploadmediaconfig[$index].maxWidth!=$scope.uploadmediaconfig[$index].validmaxwidth){
                        $scope.uploadmediaconfig[$index].maxWidth++;
                    }
                },10);

                break;

            case 'minus':

                $scope.uploadmediaconfig[$index].interval = $interval(function() {
                    if ($scope.uploadmediaconfig[$index].maxWidth != 0) {
                        $scope.uploadmediaconfig[$index].maxWidth--;
                    }
                },10);

                break;

        }

    }


    $scope.changeMaxWidthUploadMouseUp = function($index){

        $interval.cancel($scope.uploadmediaconfig[$index].interval);

    }




    ////////////////////////////////////Save//////////////////////////////////////////////////////////////


    ////////////////////////////////Valid Article Data////////////////////////////////////////////////////


    $scope.checkIsTitle = function(){


        if($scope.newartdata.title.length>0 && $scope.newartdata.title.length<=1499){

            $scope.if_title_valid = true;
            return true;

        }else{

            $scope.if_title_valid = false;
            return false;
        }



    }


    $scope.checkExternalLink = function(){

        // var url_regex = new RegExp('^((http\:\/\/)|(https\:\/\/))([a-z0-9-]*)([.]?)([a-z0-9-]+)([.]{1})([a-z0-9-]{2,4})$','g');

        if(true){

            $scope.url_class_valid = 'has-success';
            $scope.if_exurl_valid = true;

            return true;

        }else{

            $scope.url_class_valid = 'has-error';
            $scope.if_exurl_valid = false;

            return false;

        }


    }


    ////////////////////////////////Valid Article Data////////////////////////////////////////////////////

    $scope.saveNewArticle = function(){

        //console.log($scope.arttype);
        //console.log($scope.newartdata);
        //console.log($scope.checkIsTitle());
        var boolean = [];
        boolean.push($scope.checkIsTitle());

        if($scope.arttype=='external') {

            boolean.push($scope.checkExternalLink());
        }

        console.log(boolean);

        if($filter('checkfalsearray')(boolean)) {

            $scope.loading = '';

            switch ($scope.arttype) {

                case 'site':

                    $http.put(AppService.url + '/administrator/create/new/article', {type:$scope.arttype, art:$scope.newartdata})
                        .then(
                            function successCallback(response){

                                console.log(response.data);
                                $scope.initData();


                            },
                            function errorCallback(reason){

                                console.log(reason);

                            }
                        )

                    break;

                case 'external':

                    $http.put(AppService.url + '/administrator/create/new/article', {type:$scope.arttype, art:$scope.newartdata})
                        .then(
                            function successCallback(response){

                                console.log(response.data);
                                $scope.initData();

                            },
                            function errorCallback(reason){

                                console.log(reason);

                            }
                        )

                    break;

            }

        }else{

        }

    }



    ////////////////////////////////////Save//////////////////////////////////////////////////////////////




    ////////////////////////////////////Upload File///////////////////////////////////////////////////////


    $scope.$watch('files', function () {
        $scope.uploadFiles($scope.files);
    });


    $scope.uploadFiles = function (files) {


        console.log(files);


        if (files && files.length) {
            for (var i = 0; i < files.length; i++) {

                var file = files[i];

                Upload.upload({
                    url: AppService.url + '/upload/file/pictures',
                    fields: {},
                    file: file
                }).progress(function (evt) {

                    var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                    $scope.log = 'progress: ' + progressPercentage + '% ' +
                        evt.config.file.name + '\n' + $scope.log;

                    //$log.info(progressPercentage);
                    $scope.progress = progressPercentage;

                }).success(function (data, status, headers, config) {

                    //$log.info(data);
                    $scope.uploaded.push(data);
                    $scope.progress = 0;


                    $timeout(function() {
                        $scope.log = 'file: ' + config.file.name + ', Response: ' + JSON.stringify(data) + '\n' + $scope.log;
                    });
                }).error(function(data, status, headers, config) {
                    //$log.info(data);
                });
            }
        }
    };



}]);


app.controller('EditArticleController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout','$route','$routeParams', '$rootScope', 'ngDialog', '$sce', 'Upload', '$interval', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout,$route, $routeParams,$rootScope, ngDialog, $sce, Upload,$interval) {


    $scope.initData = function(){

        $scope.article_id = $routeParams.id;

        $scope.loading = '';

        $scope.loading_disk = true;

        $scope.getArticleDataPrepare()
                .then(
                    function(response){

                        //console.log(response);
                        //console.log($scope.data_to_prepare);


                        $scope.arttype = $scope.data_to_prepare.target_type;
                        //'site','external'

                        $scope.uploaded = [];

                        $scope.newartdata = {
                            id:$scope.data_to_prepare.id,
                            title:$scope.data_to_prepare.title,
                            introtext:$scope.data_to_prepare.intro,
                            fulltext:$scope.data_to_prepare.content,
                            intro_image:null,
                            external_url:$scope.data_to_prepare.external_url,
                            category_id:$scope.data_to_prepare.category_id
                        };


                        if($scope.data_to_prepare.intro_image==''){
                            $scope.newartdata.intro_image = null;
                        }else{

                            $scope.getIntroImage($scope.data_to_prepare)
                                .then(
                                    function(response){

                                        $scope.newartdata.intro_image = response;
                                        //console.log(response);

                                    },
                                    function(reason){

                                    }
                                )

                        }


                        $scope.imagetoadd = null;
                        $scope.media = null;
                        $scope.mediaconfig = [];
                        $scope.uploadmediaconfig = [];

                        $scope.disk = 'photos';
                        $scope.type = 'disk';
                        $scope.getCategories();
                        $scope.getMediaCloud();


                        ////////Valid//////////

                        $scope.if_title_valid = true;
                        $scope.if_exurl_valid = true;


                        ////////////////////////////////


                        $scope.loading = 'hidden';


                    },
                    function(reason){

                    }
            )


    }



    $scope.getArticleDataPrepare = function(){

        var deferred = $q.defer();
        //deferred.resolve(1);
        //deferred.promise;

        $http.get(AppService.url+'/administrator/get/article/'+$scope.article_id)
            .then(

                function successCallback(response){

                    //console.log(response);
                    $scope.data_to_prepare = response.data;
                    deferred.resolve(1);

                },
                function errorCallback(reason){

                    deferred.resolve(0);

                }

            );


        return deferred.promise;


    }



    $scope.getIntroImage = function(data){

        var deferred = $q.defer();

        $http.post(AppService.url+'/get/image/by/data', data)
            .then(
                function successCallback(response){

                    deferred.resolve(response.data);

                },
                function errorCallback(){
                    deferred.resolve(null);
                }
            )

        return deferred.promise;

    }



    $scope.getCategories = function(){

        $http.get(AppService.url + '/get/article/categories')
            .then(
                function successCallback(response) {

                    //console.log(response);
                    $scope.categories = response.data;

                },
                function errorCallback(response) {

                }
            );

    }



    $scope.getMediaCloud = function(){

        $scope.loading_disk = true;

        $http.get(AppService.url + '/get/media/images/'+$scope.type+'/'+$scope.disk)
            .then(
                function successCallback(response) {

                    //console.log(response.data);
                    $scope.loading_disk = false;
                    $scope.media = response.data;

                },
                function errorCallback(response) {

                }
            );


    }


    $scope.changeDisk = function(disk){
        $scope.disk = disk;
        $scope.getMediaCloud();
    }




    $scope.$watch('imagetoadd.image',function(newValue,oldValue){
        //console.log(newValue);
        if(newValue!=null){
            $scope.imagetoadd.maxwidth=$scope.imagetoadd.rootsize[0];
            $scope.imagetoadd.float='none';
        }
    });



    ///Editor module

    $scope.model = {};


    $scope.model.customMenu = {
        'openMediaModal' : {
            tag : 'button',
            classes: 'btn btn-primary btn-md btn-add-image',
            attributes: [{name : 'ng-model',
                value:'openMediaCloud'},
                {name : 'type',
                    value : 'button'},
                {name : 'title',
                    value : 'Dodaj obrazek'},
                {name: 'ng-click',
                    value: 'openMediaModal()'},
            ],
            data: [{
                tag: 'i',
                text: ' Dodaj obrazek',
                classes: 'fa fa-picture-o'
            }]
        }
    };



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




    $scope.clickImageInLibrary = function($index,img){

        document.execCommand('insertHTML', false, '<img src="'+img.image+'/'+$scope.mediaconfig[$index].maxWidth+'"  class="img-responsive '+$scope.mediaconfig[$index].float+'">');
        angular.element(document.getElementById('modal-media-lib')).addClass('hidden');
        angular.element(document.getElementsByClassName('overflow-shadow-media')).addClass('hidden');


    }



    $scope.clickImageInLibraryUpload = function($index,img){

        //console.log($scope.uploadmediaconfig[$index]);

        document.execCommand('insertHTML', false, '<img src="'+img.image+'/'+$scope.uploadmediaconfig[$index].maxWidth+'"  class="img-responsive '+$scope.uploadmediaconfig[$index].float+'">');
        angular.element(document.getElementById('modal-media-lib')).addClass('hidden');
        angular.element(document.getElementsByClassName('overflow-shadow-media')).addClass('hidden');

    }


    $scope.clickImageInLibraryIntroArticle = function($index,img){

        //console.log(img);
        $scope.newartdata.intro_image = null;
        $timeout(function(){
            $scope.newartdata.intro_image = img;
        },1000);

        angular.element(document.getElementById('modal-media-lib-for-intro')).addClass('hidden');
        angular.element(document.getElementsByClassName('overflow-shadow-media')).addClass('hidden');

    }

    $scope.hideMediaModal = function(){
        angular.element(document.getElementById('modal-media-lib')).addClass('hidden');
        angular.element(document.getElementById('modal-media-lib-for-intro')).addClass('hidden');
        angular.element(document.getElementsByClassName('overflow-shadow-media')).addClass('hidden');
    }



    $scope.openMediaForIntro = function(){

        angular.element(document.getElementById('modal-media-lib-for-intro')).removeClass('hidden');
        angular.element(document.getElementsByClassName('overflow-shadow-media')).removeClass('hidden');

    }




    $scope.changeMaxWidthMouseDown = function(action,$index){

        switch (action){

            case 'plus':

                $scope.mediaconfig[$index].interval = $interval(function() {
                    if ($scope.mediaconfig[$index].maxWidth != $scope.mediaconfig[$index].validmaxwidth) {
                        $scope.mediaconfig[$index].maxWidth++;
                    }
                },10);

                break;

            case 'minus':

                $scope.mediaconfig[$index].interval = $interval(function() {
                    if($scope.mediaconfig[$index].maxWidth!=0){
                        $scope.mediaconfig[$index].maxWidth--;
                    }
                },10);

                break;

        }

    }


    $scope.changeMaxWidthMouseUp = function($index){

        $interval.cancel($scope.mediaconfig[$index].interval);

    }





    $scope.changeMaxWidthUploadMouseDown = function(action,$index){


        switch (action){

            case 'plus':

                $scope.uploadmediaconfig[$index].interval = $interval(function(){
                    if($scope.uploadmediaconfig[$index].maxWidth!=$scope.uploadmediaconfig[$index].validmaxwidth){
                        $scope.uploadmediaconfig[$index].maxWidth++;
                    }
                },10);

                break;

            case 'minus':

                $scope.uploadmediaconfig[$index].interval = $interval(function() {
                    if ($scope.uploadmediaconfig[$index].maxWidth != 0) {
                        $scope.uploadmediaconfig[$index].maxWidth--;
                    }
                },10);

                break;

        }

    }


    $scope.changeMaxWidthUploadMouseUp = function($index){

        $interval.cancel($scope.uploadmediaconfig[$index].interval);

    }



    ////////////////////////////////Valid Article Data////////////////////////////////////////////////////


    $scope.checkIsTitle = function(){


        if($scope.newartdata.title.length>0 && $scope.newartdata.title.length<=1499){

            $scope.if_title_valid = true;
            return true;

        }else{

            $scope.if_title_valid = false;
            return false;
        }



    }


    $scope.checkExternalLink = function(){

        var url_regex = new RegExp('^((http\:\/\/)|(https\:\/\/))([a-z0-9-]*)([.]?)([a-z0-9-]+)([.]{1})([a-z0-9-]{2,4})([a-z0-9-/]*)$','g');

        if(true){

            $scope.url_class_valid = 'has-success';
            $scope.if_exurl_valid = true;

            return true;

        }else{

            $scope.url_class_valid = 'has-error';
            $scope.if_exurl_valid = false;

            return false;

        }


    }


    ////////////////////////////////Valid Article Data////////////////////////////////////////////////////

    $scope.updateArticleById = function(){

        //console.log($scope.arttype);
        //console.log($scope.newartdata);
        //console.log($scope.checkIsTitle());
        var boolean = [];
        boolean.push($scope.checkIsTitle());

        if($scope.arttype=='external') {

            boolean.push($scope.checkExternalLink());
        }

        //console.log(boolean);

        if($filter('checkfalsearray')(boolean)) {

            $scope.loading = '';

            switch ($scope.arttype) {

                case 'site':

                    $http.put(AppService.url + '/administrator/update/article/full/data', {type:$scope.arttype, art:$scope.newartdata})
                        .then(
                            function successCallback(response){

                                console.log(response.data);
                                $scope.initData();


                            },
                            function errorCallback(reason){

                                console.log(reason);

                            }
                        )

                    break;

                case 'external':

                    $http.put(AppService.url + '/administrator/update/article/full/data', {type:$scope.arttype, art:$scope.newartdata})
                        .then(
                            function successCallback(response){

                                console.log(response.data);
                                $scope.initData();

                            },
                            function errorCallback(reason){

                                console.log(reason);

                            }
                        )

                    break;

            }

        }else{

        }

    }



    ////////////////////////////////////Save//////////////////////////////////////////////////////////////


    ////////////////////////////////////Upload File///////////////////////////////////////////////////////


    $scope.$watch('files', function () {
        $scope.uploadFiles($scope.files);
    });


    $scope.uploadFiles = function (files) {


        //console.log(files);


        if (files && files.length) {
            for (var i = 0; i < files.length; i++) {

                var file = files[i];

                Upload.upload({
                    url: AppService.url + '/upload/file/pictures',
                    fields: {},
                    file: file
                }).progress(function (evt) {

                    var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                    $scope.log = 'progress: ' + progressPercentage + '% ' +
                        evt.config.file.name + '\n' + $scope.log;

                    //$log.info(progressPercentage);
                    $scope.progress = progressPercentage;

                }).success(function (data, status, headers, config) {

                    //$log.info(data);
                    $scope.uploaded.push(data);
                    $scope.progress = 0;


                    $timeout(function() {
                        $scope.log = 'file: ' + config.file.name + ', Response: ' + JSON.stringify(data) + '\n' + $scope.log;
                    });
                }).error(function(data, status, headers, config) {
                    //$log.info(data);
                });
            }
        }
    };



}]);


app.config(function($routeProvider, $locationProvider) {

    $routeProvider.
    when('/', {
        templateUrl: '/templates/admin/super/articles/master.html',
        controller: 'ArticlesController'
    }).
    when('/add', {
        templateUrl: '/templates/admin/super/articles/new_article.html',
        controller: 'NewArticleController'
    }).
    when('/edit/:id', {
        templateUrl: '/templates/admin/super/articles/edit_article.html',
        controller: 'EditArticleController'
    });
    //otherwise({redirectTo: '/'});
    //
    $locationProvider.html5Mode({
        enabled: false,
        requireBase: false
    });

});
