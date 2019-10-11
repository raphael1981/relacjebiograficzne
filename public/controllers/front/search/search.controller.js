var app = angular.module('app',['ngSanitize','ngRoute','ngAnimate','perfect_scrollbar','ngCookies'], function($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});

app.factory('AppService', function($location) {
    return {
        url : $location.protocol()+'://'+$location.host(),
        makeRegex: function (frase) {

            var reg = /\sdo\s|\sna\s|\si\s|\sz\s|\sod\s|\sw\s|\.\s|\,\s|\s/g;
            var words = frase.split(reg);
            var newWords = [];
			/*
            var limits = [[3,0],[4,1],[7,2],[9,3],[12,4],[14,5]];



            angular.forEach(words,function(item){
                for(var i=0; i<limits.length; i++){
                    if(item.length <= limits[i][0]){
                        newWords.push(item.substring(0,(item.length-limits[i][1])))
                        break;
                    }else if(item.length > 12){
                        newWords.push(item.substring(0,(item.length-6)))
                        break;
                    }
                }
            })
			*/
			 
			 angular.forEach(words,function(item,key){
				 if(item.length < 3){
					 newWords.push(item);
				 }else if(item.length < 5){
					 newWords.push(item.substring(0, item.length - 2));
				 }else if(item.length < 8){
					 newWords.push(item.substring(0, item.length - 3));
				 }else if(item.length < 10){
					 newWords.push(item.substring(0, item.length - 5));
				 }else {
					 newWords.push(item.substring(0, item.length - 6));
				 }
			 });
			
			
            var newregex = new RegExp(newWords.join('(.{0,10})')+'([^,\.\? ]+)','gi');

            //console.log(newregex);

            return newregex;

        }
    };
});


app.factory('searchFactory', function(){

    service.getArtist = function(){
        return 11;
    }

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

app.directive('searchFragments', function() {
    return {
        templateUrl: 'templates/searchFragments.html',
        link: function(scope, element, attributes, searchFactory){

            //console.log(attributes);

            scope.fragments = JSON.parse(attributes.searchFragments);
            scope.record = JSON.parse(attributes.record);
            scope.frase = attributes.frase;
            scope.auth = attributes.auth;
            scope.stype = attributes.type;
            console.log(scope.stype);
            //AppService.makeRegex($scope.data.search.frase);

            //attributes.$observe('searchFragments', function(value){
            //    console.log(value);
            //});
            //
            //attributes.$observe('fragments', function(value){
            //    console.log(value);
            //});


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


app.filter('makeFragmentDesc', ['AppService', function(AppService) {
    return function(content, frase) {
        var regex = AppService.makeRegex(frase);
        //console.log(regex.exec(content));
        //console.log(content.match(regex));
        var descplace = '';
        //console.log(regex);
        //console.log(content);
        //console.log(content.match(regex));
        angular.forEach(content.match(regex),function(item,key){

            var str = content.substring(content.indexOf(item) - item.length - 60, content.indexOf(item)+ item.length + 60);
            //console.log(str);
            descplace += ' ...';
            descplace += str.replace(item, '<b>'+item+'</b>');
            descplace += '... ';

        });


        return descplace;
    }
}]);


app.filter('makeFragmentDescPerfect', ['AppService', function(AppService) {
    return function(content, frase, weight) {

        var descplace = '';

        //console.log(weight);

        var regex = new RegExp(frase,'gi');

        //console.log(content.match(regex));

        //for(var i=0;i<=weight;i++){
        //
        //    var str = content.toLowerCase().substring(content.indexOf(frase.toLowerCase()) - frase.length - 60, content.toLowerCase().indexOf(frase.toLowerCase())+ frase.length + 60);
        //    //console.log(str);
        //    descplace += ' ...';
        //    descplace += str.replace(frase.toLowerCase(), '<b>'+frase+'</b>');
        //    descplace += ' ...';
        //
        //}

        angular.forEach(content.match(regex),function(item,key){

            var str = content.substring(content.indexOf(item) - item.length - 60, content.indexOf(item)+ item.length + 60);
            //console.log(str);
            descplace += ' ...';
            descplace += str.replace(item, '<b>'+item+'</b>');
            descplace += '... ';

        });


        return descplace;
    }
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

            var t = hh+":"+mm+":"+ss;

            return t;

        }



    }

});



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


}]);





app.controller('SearchNoAuthController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout', 'orderByFilter', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout, orderBy) {


    $scope.showsearching = false;
    $scope.morebutton = false;
    $scope.howmuchpush = null;
    $scope.currentpush = null;
    $scope.searchplaceholder = 'wyszukaj frazę dokładnie';

    $scope.is_perfect = true;

    $scope.data = {};

    $scope.data.search = {
        frase:'',
        tsearch:'trans',
        searchmode: 'perfect'
    };


    $scope.data.results = null;

    $scope.now = 0;
    $scope.next = 1;

    $scope.canlazyload = false;

    $scope.auth = false;


    $scope.initOnLoad = function(){


        if($location.path()) {

            var getSplit = $location.path().split('&');

            var typearray = getSplit[1].split('=');

            console.log(typearray);

            var frasearray = getSplit[0].split('=');

            if (frasearray[1] && frasearray[1] != '') {

                if (typearray[1] == 'regex') {
                    $scope.data.search.frase = frasearray[1];
                    $scope.data.search.searchmode = 'regex';
                    $scope.cacheFraseResult($scope.data.search);
                    $scope.getSearchData($scope.data.search);
                    $scope.regexforcontent = AppService.makeRegex($scope.data.search.frase);
                } else if (typearray[1] == 'perfect') {
                    $scope.data.search.frase = frasearray[1];
                    $scope.cacheFraseResult($scope.data.search);
                    $scope.getSearchData($scope.data.search);
                    $scope.data.search.searchmode = 'perfect';
                    //$scope.regexforcontent = AppService.makeRegex($scope.data.search.frase);
                }


            }

        }

        $http.get(AppService.url+'/get/ajax/auth',$scope.data.search)
            .then(

                function successCallback(response) {

                    //$log.info(response.data);
                    $scope.auth = response.data.boolean;

                },
                function errorCallback(response) {

                }
            );


    }


    $scope.changeSearchMode = function(mode){

        $scope.data.search.searchmode = mode;

        if($scope.is_perfect){
            $scope.is_perfect = false;
            $scope.searchplaceholder = 'wyszukaj frazę uwzględniając odmianę wyrazów';
        }else{
            $scope.is_perfect = true;
            $scope.searchplaceholder = 'wyszukaj frazę dokładnie';
        }


    }


    $scope.getSuggest = function(){



    }

    $scope.checkIsRegexFindSomePhrase = function(content, frase){
        var reg = AppService.makeRegex(frase);
        if(content.match(reg)==null){
            return false;
        }else{
            return true;
        }
    }


    $scope.cacheFraseResult = function(){

        $http.post(AppService.url+'/ahm/search/cache/result',$scope.data.search)
            .then(

                function successCallback(response) {

                    //$log.info('cache',response.data);
                    $scope.howmuchpush = response.data.length;
                    $scope.currentpush = 1;
                    if($scope.howmuchpush>1){
                        $scope.morebutton = true;
                    }
                    $scope.canlazyload = true;

                },
                function errorCallback(response) {

                }
            );

    };






    $scope.getSearchData = function(){

        $scope.showsearching = true;
        $scope.howmuchpush = null;
        $scope.currentpush = null;
        $scope.data.results = null;

        $http.post(AppService.url + '/ahm/search/data', $scope.data.search)
            .then(
                function successCallback(response) {

                    //$log.info('now', response.data);
                    $scope.showsearching = false;
                    if(response.data!=0) {
                        $scope.data.results = $filter('orderBy')(response.data, 'fcount', true);
                    }else{

                    }

                },
                function errorCallback(response) {

                }
            );

    }


    $scope.onSubmit = function(){

        if($scope.data.search.frase.length>0) {

            $scope.cacheFraseResult($scope.data.search);
            $scope.getSearchData($scope.data.search);
            //$log.info($scope.is_perfect);
            $scope.regexforcontent = AppService.makeRegex($scope.data.search.frase);

            if($scope.is_perfect){
                $location.path('frase='+$scope.data.search.frase+'&stype=perfect');
            }else{
                $location.path('frase='+$scope.data.search.frase+'&stype=regex');
            }



        }else{

        }

    }




    $scope.addNext = function(){

        //$scope.now = 0;
        //$scope.next = 1;
        $scope.showsearching = true;

        if($scope.canlazyload){

            $http.get(AppService.url + '/ahm/search/cache/get?next='+$scope.next+"&frase="+$scope.data.search.frase+'&type='+$scope.data.search.searchmode)
                .then(
                    function successCallback(response) {

                        //$log.info(response.data);

                        if(response.data!=1) {

                            $scope.showsearching = false;
                            $scope.data.results = $scope.data.results.concat(response.data);
                            $scope.now++;
                            $scope.next++;
                            $scope.currentpush += 1;
                            if($scope.howmuchpush>=$scope.currentpush){
                                $scope.morebutton = false;
                            }

                        }else{
                            $scope.showsearching = false;
                        }

                    },
                    function errorCallback(response) {

                    }
                )
        }else{
            $scope.$watch('canlazyload', function(newValue, oldValue) {
                if(newValue){
                    $scope.addNext();
                }
            })
        }



    }


    $scope.sendPostIntentToLogin = function(params, frase, is_fragment){


        var fdata = [
            {name:'id', value:params.record.rid},
            {name:'alias', value:params.record.alias},
            {name:'type', value:params.record.type}
        ];

        var form = document.createElement('form');
        form.action = '/autoryzacja';
        form.method = 'POST';

        var tinput = document.createElement('input');
        tinput.type = 'hidden';
        tinput.name = '_token';
        tinput.value = $('meta[name="csrf-token"]').attr('content');
        form.appendChild(tinput);

        var fraseinput = document.createElement('input');
        fraseinput.type = 'hidden';
        fraseinput.name = 'frase';
        fraseinput.value = frase;
        form.appendChild(fraseinput);


        if(!is_fragment) {

            var inputtype = document.createElement('input');
            inputtype.type = 'hidden';
            inputtype.name = 'linktype';
            inputtype.value = 'record';
            form.appendChild(inputtype);


            for (var i in fdata) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = fdata[i].name;
                input.value = fdata[i].value;
                form.appendChild(input);
            }

        }


        form.submit();

    }


    $scope.sendPostAuthIntentFragment = function(id, alias, start, type, frase){


        var fdata = [
            {name:'frase', value: frase},
            {name:'id', value:id},
            {name:'alias', value:alias},
            {name:'start', value:start},
            {name:'type', value:type}
        ];

        var form = document.createElement('form');
        form.action = '/autoryzacja';
        form.method = 'POST';

        var tinput = document.createElement('input');
        tinput.type = 'hidden';
        tinput.name = '_token';
        tinput.value = $('meta[name="csrf-token"]').attr('content');
        form.appendChild(tinput);

        var inputtype = document.createElement('input');
        inputtype.type = 'hidden';
        inputtype.name = 'linktype';
        inputtype.value = 'fragment';
        form.appendChild(inputtype);

        for (var i in fdata) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = fdata[i].name;
            input.value = fdata[i].value;
            form.appendChild(input);
        }


        form.submit();

    }


    $scope.sendPostToRecordWithFrase = function(link, frase){

        var fdata = [
            {name:'frase', value: frase}
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


        form.submit();

    }


    $scope.sendPostFragment = function(link, frase){


        var fdata = [
            {name:'frase', value: frase}
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


        form.submit();

    }



}]);


app.config(function($routeProvider, $locationProvider) {

    $locationProvider.html5Mode({
        enabled: false,
        requireBase: false
    });

});