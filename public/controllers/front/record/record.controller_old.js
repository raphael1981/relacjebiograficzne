'use strict';

var app = angular.module('app',['ngSanitize','ngRoute','ngDialog','ngPhotoSwipe','perfect_scrollbar'], function($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});


app.directive('linkedRecords', ['$http', function($http) {
	return {
		templateUrl: '/templates/linkedRecords.html',
		link: function(scope, element, attributes, $http, AppService){

			//console.log(JSON.parse(attributes.linkedRecords));
			//console.log(attributes.linked);
			//scope.linked = attributes.linked;

		},
		controller:function($scope,$http,$attrs,AppService){
			//console.log($attrs);

			$http.get(AppService.url+'/get/linked/records/'+$attrs.linked)
					.then(
							function successCallback(response){

								console.log(response.data);
								//console.log(response.data.length);
								if(response.data.length>0){
									$scope.showlinked = true;
								}else{
									$scope.showlinked = false;
								}
								$scope.linked = response.data;

							},
							function errorCallback(response){

							}
					);

		}
	};
}]);


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




app.filter('chunkArray', function() { return function(arr,len) {

	var chunks = [],
			i = 0,
			n = arr.length;

	while (i < n) {
		chunks.push(arr.slice(i, i += len));
	}

	return chunks;
}});



app.factory('mySharedService', function($rootScope) {
    var sharedService = {};
    sharedService.data = {};

    sharedService.prepForBroadcast = function(data) {		 
        this.data = data;
        this.broadcastItem();		
    };

    sharedService.broadcastItem = function() {         
        $rootScope.$broadcast('handleBroadcast');
    };

    return sharedService;
});


app.factory('AppService', function($location) {
	return {
		url : $location.protocol()+'://'+$location.host()
	};
});



app.directive('videoPlayer',function($http,mySharedService){
		return{
				restrict:'EA',	 	 
				scope: true,				
				controller: function($scope){					
					$scope.$on('handleBroadcast', function() {                       
							$scope.data = mySharedService.data;															  
							$scope.$watch('data.currentMediaPath', function() {						   
											$("video").attr("src",$scope.data.currentMediaPath)
											});					
							});
                   mySharedService.prepForBroadcast($scope);																
					
				},
			/*	
             link:	 function(scope,element,attr){
				    var video = element[0].firstElementChild;
					video.addEventListener('timeupdate',function(ev){
						mySharedService.data.currTime = ev.target.currentTime;
						console.log(ev.target.currentTime)})					
				    
			 },
			*/ 
             templateUrl: '/templates/videoplayer.html'	    
		    }	
});

app.directive('audioPlayer',function($http,mySharedService){
		return{
				restrict:'EA',	 	 
				scope: true,				
				controller: function($scope){					
					$scope.$on('handleBroadcast', function() {
                    $scope.data = mySharedService.data;	
					   $scope.$watch('data.currentMediaPath', function() {						   
								$("audio").attr("src",$scope.data.currentMediaPath)
						});				    
             });
			     mySharedService.prepForBroadcast($scope);																
				},				
       templateUrl: '/templates/audioplayer.html'	    
		}	
})

app.factory('AppService', function($location) {
    return {
        url : $location.protocol()+'://'+$location.host()
    };
});

app.controller('GlobalController',['$scope', 
                                   '$http', 
								      '$log', 
									  '$q', 
									  '$location',
									  'AppService', 
									  '$window', 
									  '$filter', 
									  '$timeout',
                                   'mySharedService',
                                    '$rootScope',								
									  function($scope, $http, 
									           $log, $q, 
											    $location,AppService, 
												$window, $filter, 
												$timeout,mySharedService,
												$rootScope) {

   $scope.currTime = 0;
	
    $timeout(function(){

        angular.element(document.getElementById('body')).removeClass('alphaHide');
        angular.element(document.getElementById('body')).addClass('alphaShow');

    },500);

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


app.controller('RecordController',['$scope',
                                   '$sce',
								      'mySharedService',
									  '$rootScope',
									  '$timeout',
									  'ngDialog',
		                              '$q',
		   								'$http',
									'$filter',
									'AppService',
									  function($scope,$sce, 
									            mySharedService,$rootScope,$timeout,ngDialog,$q,$http, $filter, AppService){

    $scope.currTime = 0;
    $scope.currentRecord = null 
	$scope.currentMediaPath = '';	
	$scope.player = null;
	$scope.klasa = {normal : 'normal', highlight : 'light'};
	$scope.currSection = {};	
	$scope.search = {phrase:''}

	$scope.showgallerysection = false;
	

	$scope.toTrustedHTML = function( html ){
	 	return $sce.trustAsHtml( html );
	 }
	
    $scope.init = function(data,fragments){
			$scope.currentRecord = data;			
            $scope.fragments = fragments;
		    $scope.listXML = document.getElementById('listXML');			
			$scope.sections = $scope.listXML.getElementsByTagName('section');
            console.log('time',arguments[2]);
			/*
			$timeout(function(){
				$scope.sections[0].addClass('light');
				console.log('sdsad',$scope.sections[0])
				},10);
			*/	
			$scope.currentMediaPath = data.type=='audio' ?
		                             $sce.trustAsResourceUrl('http://audio.zbiglem.pl/'+data.source) :
										  $sce.trustAsResourceUrl('http://video.zbiglem.pl/'+data.source);           
            mySharedService.prepForBroadcast($scope);            
			
  }
  
     angular.element(document).ready(function(){	          
	  $scope.player = $scope.currentRecord.type == 'video' ? 
					   document.getElementsByTagName('video') : 
	                  document.getElementsByTagName('audio');
	/*				  
	 $scope.player[0].currentTime = $scope.currTime;
	 $scope.searchByPhrase(null);
     go2Time($scope.player[0],1)
	*/ 
     $timeout(function(){ 
     $scope.currSection = $scope.sections[0];		         
	  //$scope.player[0].addEventListener('progress',$scope.seekFragment);
	  $scope.player[0].addEventListener('timeupdate',$scope.seekFragment,true);
	  $scope.player[0].addEventListener('seeked',seekByProgressBar,true);	  	  	  	  	    	
	  $scope.currSection.className = 'light'
	},1000)
	 
  })
  

    $scope.getSearchResult = function(time, frase){
		
	 	$scope.currTime = time;		
	    $scope.search.phrase = frase;	
		$timeout(function(){
				 $scope.player[0].currentTime = $scope.currTime;
	            searchSectionByTime(time); 
				//$scope.searchByPhrase(null);
                go2Time($scope.player[0],1)
		},1000)		
		console.log('time',time);
  }

   $scope.getIndexSearchResult = function(time){
	   $scope.currTime = time;
	   $timeout(function(){
		   $scope.player[0].currentTime = $scope.currTime;
		   searchSectionByTime(time);
		   go2Time($scope.player[0],1)
	   },1000)
	   console.log('time',time);
   }

  
  function pausePlayer(ev){
	  var target = ev.target || ev.srcElement;
	  $scope.player[0].pause();
	  target.addEventListener('mouseup',function(event){})
	  console.log('scroll target',target)
  }
    
   

	
   function go2Time(elem,mod){
        console.log('jestem')	   
		$scope.currTime = elem.currentTime
		if(typeof elem.currentTime !== 'undefined'){	
        var newPos = 0;
		angular.forEach($scope.sections,function(item,key){
			item.className = 'normal'
		});			
       if(mod == 0){		
		angular.forEach($scope.fragments,function(item,key){        
		var section = {}
		for(var i=0; i < $scope.sections.length; i++) {			
		   if(elem.currentTime <= parseInt($scope.sections[i].getElementsByTagName('span')[0].innerText))		  
	       {				              
				if(i<0){					
					newPos = $scope.sections[i].offsetTop - $scope.listXML.offsetTop;
                  section = $scope.sections[i];					
				}else{
					if(typeof $scope.sections[i-1] !== 'undefined'){
						newPos = $scope.sections[i-1].offsetTop - $scope.listXML.offsetTop;
						section = $scope.sections[i-1];
					}									
				}
				$scope.currSection = section;					
				break;
				return false;				
			}
		 }		   					 
	   });}else if(mod == 1){		 
		angular.forEach($scope.fragments,function(item,key){        
		for(var i=0; i < $scope.sections.length; i++) {					
		   if(elem.currentTime == parseInt($scope.sections[i].getElementsByTagName('span')[0].innerText))		  
	       {				              				                
				 newPos = $scope.sections[i].offsetTop - $scope.listXML.offsetTop;
                 $scope.currSection = $scope.sections[i];                
				break;
				return false;				
			}
		 }		   					 
	   }); 
           
	   }
	   
	      //console.log('terefere',$scope.currSection)				 
		  //console.log('mod', mod)				 
		  $scope.currSection.className = 'light';
		  $scope.listXML.scrollTop = newPos;
		}        
	}
	
	
	function seekByProgressBar(ev){  
       console.log('jadymy!', $scope.player[0].paused)			
      	if(!$scope.player[0].paused){			
			ev.stopPropagation();
			var target = ev.target || ev.srcElement;			
			$scope.currTime = target.currentTime	       		
			go2Time(target,0)
		}
	}
	
	
	$scope.seekFragment = function(ev){
     if(!$scope.player[0].paused){		
	    ev.stopPropagation();
		var target = ev.target || ev.srcElement;		
		$scope.currTime = target.currentTime;        
	   go2Time(target,0)
	 }
	}
  
    $scope.changeTime = function(ev,sec){         	   
       if(ev){
	   ev.stopPropagation();       	   
       var target = ev.target || ev.srcElement;	   	   	   
	   }
       try{		 
		 $scope.player[0].currentTime = sec
		 if(!$scope.player[0].paused){			 
			 console.log('jedzie')
			go2Time($scope.player[0],0)		 
		 }else{			 
			 console.log('stoi')
			go2Time($scope.player[0],1)            		
		 }
	   }catch(err){}	    
		$scope.currTime = sec;
    }
		
	
		$scope.openSubWindow = function(temp,klasa){
		  ngDialog.open({
				   scope: $scope,
                    template: temp,
                    className: klasa, 
                    cache: true,
                    overlay: true
                   });			
			$scope.$on('ngDialog.closed', function (e, $dialog) {
			 
           });
	}
	
	$scope.alertMess = function(mess){
			$scope.forMessageData = {	   
                                    message: mess,                               						  
								    };		
		    $scope.openSubWindow('/templates/alert_renderer.html','ngdialog-theme-small');		
	}
	
	function scrollToCurrent(currSection){		
		angular.forEach($scope.sections,function(item,key){
			item.className = 'normal'
		});
		$scope.currSection = currSection.section;
		$scope.currSection.className = 'light';
		$scope.listXML.scrollTop = currSection.section.offsetTop - $scope.listXML.offsetTop
		$scope.player[0].currentTime = parseInt(currSection.time)
		console.log('asd', currSection)
		$scope.currSection.getElementsByTagName('div')[0].innerHTML = 
		$scope.currSection.getElementsByTagName('div')[0].innerHTML.highlightWords(currSection.searchText)
	}
	var searchCounter = $scope.phraseOut ? 0 : -1;
			
	
	
	function searchSectionByTime(time){
		//alert(location);
		//console.log('location search',location.search)
		//console.log('sections',$scope.sections[0].getElementsByTagName('span')[0].innerText)
		angular.forEach($scope.sections,function(item,key){
			//console.log(item.getElementsByTagName('span')[0].innerText + '-----' + time)
            if(item.getElementsByTagName('span')[0].innerText == time){
					$scope.currSection = item;					
					searchCounter = key++;
			}
		})
		//console.log('phrassssse',$scope.search);
		if($scope.search.phrase) {
			var reg = /\sdo\s|\sna\s|\si\s|\sz\s|\sod\s|\sw\s|\.\s|\,\s|\s/g;
			var words = $scope.search.phrase.split(reg);
			var newWords = [];

			angular.forEach(words, function (item, key) {
				if (item.length < 3) {
					newWords.push(item);
				} else if (item.length < 5) {
					newWords.push(item.substring(0, item.length - 2));
				} else if (item.length < 8) {
					newWords.push(item.substring(0, item.length - 3));
				} else if (item.length < 10) {
					newWords.push(item.substring(0, item.length - 5));
				} else {
					newWords.push(item.substring(0, item.length - 6));
				}
			});

			var newregex = new RegExp(newWords.join('(.{0,10})') + '([^,\.\? ]+)', 'gi');
			var searchText = $scope.currSection.getElementsByTagName('div')[0].innerText.match(newregex)
			$scope.currSection.getElementsByTagName('div')[0].innerHTML =
					$scope.currSection.getElementsByTagName('div')[0].innerHTML.highlightWords(searchText)
			console.log('currsection', $scope.currSection.getElementsByTagName('div')[0].innerHTML)
			console.log('searchText', searchText)
		}
	}

    function getCurrentSection(item){
        for(var i=0; i<$scope.sections.length;i++)
		if($filter('secondsToTime')(item.start) == $scope.sections[i].getElementsByTagName('time')[0].innerHTML){
			return $scope.sections[i];
		}
		return false;
	}
    var essearchCounter = 0;
	$scope.searchByPhraseES = function(id) {
        if($scope.search.phrase !== '') {
            if ($scope.search.phrase.length < 3) {
                $scope.alertMess('Wyszukiwana fraza musi mieć więcej niż 2&nbsp;znaki')
                return false;
            }
			$http.post(AppService.url + '/get/essearch/', {id: id, phrase: $scope.search.phrase}).then(
                function successCallback(response) {
                    $scope.searchresult = [];
                    console.log('rowdata',response.data)
                    angular.forEach(response.data.hits.hits, function (item, key) {
						var highlights = [];
                    	angular.forEach(item.highlight.content,function(it){
                            var temps = it.match(/\|%(.*?)%\|/g)
							for(var i=0; i<temps.length; i++){
								temps[i] = temps[i].replace(/\|%(.*?)%\|/,"\$1")
							}
							//console.log('temps',temps)
							highlights = temps;
						})
                        item._source.highlights = highlights
                    	$scope.searchresult.push(item._source)
                    })



					angular.forEach($scope.fragments,function(item,key){
						//scrollToCurrent(getCurrentSection($scope.fragments[1]))
						for(var i=0; i<$scope.searchresult.length; i++){
							//console.log(item.id+' == '+$scope.searchresult[i].fid)
							if(item.id == $scope.searchresult[i].fid) {
								$scope.searchresult[i].count = key;
							  for(var j=0; j<$scope.searchresult[i].highlights.length; j++) {
								  var phrase = $scope.searchresult[i].highlights[j]
								  var re = new RegExp(phrase,"g");
								  var re0 = new RegExp('<span class="highlight">'+phrase+'</span>',"g");
								  item.content = item.content.replace(re0,phrase)
								  item.content = item.content.replace(re, '<span class="highlight">'+phrase+'</span>');
/*								      item.content = item.content.replace($scope.searchresult[i].highlights[j],
										  '<span class="highlight">'+$scope.searchresult[i].highlights[j]+'</span>')*/
							  }
								//console.log('sas',i+' - '+j)
								//console.log('asa',$scope.searchresult[i].highlights)
							}
						}
					})


					angular.forEach($scope.sections,function(item,key){
						   item.className = "ng-class normal"
					})
                    var block = false;
					var currTime = 0;
					angular.forEach($scope.sections,function(item,key){
						if(key <= essearchCounter || block){
						   return false;
						}
							angular.forEach($scope.searchresult, function(it,k){
								if(item.getElementsByTagName('time')[0].innerHTML == $filter('secondsToTime')(it.start)){
									//item.className = "light";
										$scope.currSection = item;
                                        currTime = it.start;
									    essearchCounter = key++;
									    block = true;
										return true;
								}
							})

					})
					console.log('akt sec',$scope.currSection)
					$scope.currSection.className = "light"
					$scope.listXML.scrollTop = $scope.currSection.offsetTop - $scope.listXML.offsetTop
					$scope.player[0].currentTime = currTime;
					console.log('jadymy', $scope.searchresult);
					angular.forEach($scope.sections,function(item,key){

					 });


				}, function errorCallback(err) {
                    console.log(err.data)
                });

        }
    }
	
	$scope.searchByPhrase = function(ev){
		if($scope.search.phrase !== ''){
			if($scope.search.phrase.length < 3){
				$scope.alertMess('Wyszukiwana fraza musi mieć więcej niż 2&nbsp;znaki')			    
				return false;
			}
			  var reg = /\sdo\s|\sna\s|\si\s|\sz\s|\sod\s|\sw\s|\.\s|\,\s|\s/g;			  
			  var words = 	$scope.search.phrase.split(reg);
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

			  var newPhrase = new RegExp(words.join(' '),'gi');
			  var newregex = new RegExp(newWords.join('(.{0,10})')+'([^,\.\? ]+)','gi');

			  var newSearchRegex = words.length > 1 ?  newregex : newPhrase;
				console.log('regex',newSearchRegex);
   			  var result = [];
			  angular.forEach($scope.sections,function(item,key){	
                   console.log('key ',searchCounter)				    
                  	 if(key <= searchCounter){return false}
					if(item.getElementsByTagName('div')[0].innerText.search(newSearchRegex) > -1)				  
					 result.push({'sectiontext' : item.getElementsByTagName('div')[0].innerText,
				                  'time' : item.getElementsByTagName('span')[0].innerText,
								   'position' : item.offsetTop,
								   'searchText' : item.getElementsByTagName('div')[0].innerText.match(newSearchRegex),
								   'searchIndex': item.getElementsByTagName('div')[0].innerText.search(newSearchRegex),
								   'section': item,
								   'it' : key
								   })					
			  })
			 console.log('result ',result)
             if(result.length > 0 ){                
                scrollToCurrent(result[0]);
			  }
			  if(typeof result[0] !== 'undefined'){
					searchCounter = result[0].it++;
			  }else{
				  searchCounter = -1
			  }
			  
			  console.log('fr ',searchCounter)
			}					
	}
	
    $scope.clearSearch = function(ev){
		var target = ev.target || ev.srcElement;
		$scope.search.phrase = '';
		var co = /<span class="highlight">([\s\S]*?)<\/span>/gi
		var naco = "\$1"
		 angular.forEach($scope.sections,function(item,key){
			//console.log('item ',item.getElementsByTagName('div')[0].innerHTML)
			 //item.getElementsByTagName('div')[0].innerHTML =
			//		 item.getElementsByTagName('div')[0].innerText.replace(co,naco)
			 //item.getElementsByTagName('div')[0].clearHighlight();
			 item.className = "ng-class normal";
		 })

		angular.forEach($scope.fragments,function(item){
			item.content = item.content.replace(co,naco);
		})

		//$scope.init($scope.currentRecord,$scope.fragments);
		 $scope.currSection = $scope.sections[0];
		 $scope.currSection.className = 'light';
		 $scope.listXML.scrollTop = 0;
		 $scope.searchresult = [];
		 essearchCounter = 0;
	}
	
	/*	
	$scope.$watch('currTime',function(newVal,oldVal){
		if(newVal != 0){
				console.log('trrrrrrrrrrr ',newVal)	
               $scope.changeTime(null,newVal);
				$scope.searchByPhrase(null);
				
		}
	})
	*/
/*
	$scope.$watch('currSection', function(newVal,oldVal){
				if(newVal !== oldVal){
					console.log('currSection ',newVal);					
					newVal.className = 'highclass'
				}				
		  })
	*/



	///////////////////Rafal Part Of Controller////////////////////////
	$scope.initData = function(id){

		//$scope.data = null;
		$scope.linked = null;

		//$scope.getLinkedRecords(id);

		$scope.getIntervieweeImages(id)
			.then(
				function(data){
					//console.log(data);
						$timeout(function () {
							$scope.$broadcast('rebuild:me');
						}, 0);
					},
					function(reason){
						//console.log(reason);
					}

				);


	}


	$scope.getIntervieweeImages = function(id){


		var deferred = $q.defer();

		$http.get(AppService.url+'/interviewee/images/get/photos/'+id)
			.then(
					function successCallback(response){

						//console.log(response.data);
						//console.log($filter('chunkArray')(response.data[0],21));

						if(angular.isArray(response.data[0])) {
							if (response.data[0].length > 0) {
								$scope.showgallerysection = true;
							}

							$scope.fullchunkimages = $filter('chunkArray')(response.data[0], 21);
							$scope.gcount = $scope.fullchunkimages.length;
							$scope.current = 1;

							if ($scope.gcount > 1) {
								$scope.is_more = true;
							} else {
								$scope.is_more = false;
							}

							$scope.interimages = $scope.fullchunkimages[0];
							//$scope.data = response.data;
						}
					},
					function errorCallback(response){
								deferred.reject();
					}
			)

			return deferred.promise;

		}


	$scope.showMoreImages = function(){

		if($scope.gcount>1){

			$scope.current++;

			if($scope.current<=$scope.gcount){

				if($scope.current==$scope.gcount){
					$scope.is_more = false;
				}

				$scope.fullchunkimages[$scope.current].forEach(function(item){
					$scope.interimages.push(item);
				});



				$scope.$broadcast('rebuild:me');
				console.log($scope.interimages);
			}
		}

	}

	//$scope.getLinkedRecords = function(id){
    //
	//	$http.get(AppService.url+'/get/linked/records/'+id)
	//			.then(
	//					function successCallback(response){
    //
	//						//console.log(response.data);
	//						$scope.linked = response.data;
    //
	//					},
	//					function errorCallback(response){
    //
	//					}
	//			);
    //
	//}

	$scope.scrollTop = 0
	$scope.scrollHeight = 0
    $scope.onScroll = function (scrollTop, scrollHeight) {
                        $scope.scrollTop = scrollTop
                        $scope.scrollHeight = scrollHeight
                    }	
	
}]);


app.config(function($routeProvider, $locationProvider) {

	$locationProvider.html5Mode({
		enabled: false,
		requireBase: false
	});

});