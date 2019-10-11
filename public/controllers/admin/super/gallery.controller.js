var app = angular.module('app',['ngSanitize',
                                'ngRoute',
								'ngFileUpload',
								'angularFileUpload',
                                'xeditable',								
								'ngDialog',
								'angularSideBySideSelect',								
								'dndLists'], function($interpolateProvider) {
    $interpolateProvider.startSymbol('<%');
    $interpolateProvider.endSymbol('%>');
});

app.run(function(editableOptions) {
  editableOptions.theme = 'bs3'; // bootstrap3 theme. Can be also 'bs2', 'default'
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

app.filter('getIdFromName',function(){return function(name){
	var temp = name.split('.');
	return temp[0];
}})

app.filter('reverse', function() {
  return function(items) {
    return items.slice().reverse();
  };
});

app.controller('GlobalController',['$scope', '$http', '$log', '$q', '$location','AppService', '$window', '$filter', '$timeout', function($scope, $http, $log, $q, $location,AppService, $window, $filter, $timeout) {   $timeout(function(){        angular.element(document.getElementById('body')).removeClass('alphaHide');        angular.element(document.getElementById('body')).addClass('alphaShow');    },100)

}]);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
app.controller('GalleriesController',['$scope', 
                                      '$http', 
									    '$log', 
										'$q', 
										'$location',
										'AppService', 
										'$window', 
										'$filter', 
										'$timeout',
										'$route',
										'$sce',
										'ngDialog',
										'FileUploader',
										'Upload',
										'$routeParams', function($scope, 
										                         $http, 
																   $log, 
																   $q, 
																   $location,
																   AppService, 
																   $window, 
																   $filter, 
																   $timeout,
																   $route,
																   $sce,
																   ngDialog,
																   FileUploader,
																   Upload,
																   $routeParams) {
	
	   $scope.images = null;  	   
	   $scope.fileUploadedNameTab = [];	   
	   $scope.addForPicture = '';
       $scope.forConfirmData = {};
       $scope.iptc = {};
	   $scope.imagePath = '';
       $scope.persons = null;	
       $scope.personImages = null;	  
       $scope.orderMode = '';
	   $scope.limit = 20;
		$scope.imageList = [];
		$scope.currPerson = '';
       $scope.currSubject = null;
	   $scope.currSubDbase = [];
       $scope.uploaded = [];	   
		$scope.destinationgalleryData	 = [];
		$scope.gallerytype = {person: true, subject: false};
        $scope.photosFromDbase = null;
		$scope.uploader = null;
		
	var data = $scope.galleryData;


	 $scope.$watch('files', function () {
        $scope.uploadFiles($scope.files);
    });


    $scope.uploadFiles = function (files) {

        if (files && files.length) {
            for (var i = 0; i < 1; i++) {
                var file = files[i];
                Upload.upload({
                    url: AppService.url + '/upload/filegallery/primaryphoto',
                    fields: {},
					 data: {newname: $scope.currSubject.id+'-'+$scope.currSubject.alias},                                      
                    file: file
                }).progress(function (evt) {

                    var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                    $scope.log = 'progress: ' + progressPercentage + '% ' +
                        evt.config.file.name + '\n' + $scope.log;

                    $log.info(progressPercentage);
                    $scope.progress = progressPercentage;

                }).success(function (data, status, headers, config) {
                                       
                    $scope.uploaded.push(data);
                    $scope.progress = 0;
                    $timeout(function() {
                        $scope.log = 'file: ' + config.file.name + ', Response: ' + JSON.stringify(data) + '\n' + $scope.log;
                    });
                }).error(function(data, status, headers, config) {
                    $log.info(data);
                });
            }
        }
    };

	
		
                $scope.getList = function () {					
                  console.log('datttttta ',data)                  
				   return data;
                }
				
					$scope.$watch('destinationgalleryData',function(nVal,oVal){
		            if(nVal != oVal){
						if($scope.imagePath != ''){
							$http({url:'galleries/data/galltitles',
							       method:'POST',
								   data: {image:$scope.imagePath,
									      supplementalCategories:nVal},
								   headers : {'Content-Type':'application/json; charset=utf-8'}}
							).then(function successCallback(response){
								console.log('hahaha',response.data);
							},function errorCallback(response) {})
							//console.log('hastalavista!',nVal)
						}
					 }		            							  
	              },true);
							
				
				this.getFilteredList = function (text) {                    
                    if ((text === "") || (text === undefined) || (text === null)) {						
                        return data;
                    } else {
                        var filtered = [];
                        for (var i = 0; i < data.length; i++) {
                            if ((data[i].name + "").indexOf(text) !== -1) {								   
                                filtered.push(data[i]);								
                            }
                        }						  
                        return filtered;
                    }
                };
                this.itemsComparator = function (a, b) {					 
                    return a.name === b.name;
                };				 
                this.data = {};
                this.data.data = data;
				 //this.data.data = $scope.iptc.supplementalCategories;
               // this.data.example7 = [data[5], data[6], data[7]];
   
	
	$scope.loadPictures = function(ev,dirPath){
				 $http({url : 'galleries/data',				
			      method : "GET",				
				   headers : {'Content-Type':'application/json; charset=utf-8',
				  'path': dirPath}}).then(function successCallback(response){			
										angular.forEach(response.data.pathes, function(path){			/*								
													$http({url : 'galleries/iptc',				
															method : "GET",				
															headers : {'Content-Type':'application/json; charset=utf-8',
															           'path': path}}).
															then(function successCallback(response) {
																	$scope.imageList.push({'path': path,
								                                                      'iptc': response.data})
										                  },
										                   function errorCallback(response){
																	$scope.imageList.push({'path': path,
								                                                      'iptc': 'brak info'})												
														   }
										                )
														*/
														$scope.imageList.push({'path': path.name,'iptc': path.iptc})
					                   });									   
				   },
                function errorCallback(response) {})
	}
	

	
	$scope.displayPhoto = function(ev,image){		
		console.log('image ',image);
		$scope.imagePath = image.path;
		$scope.iptc = image.iptc;			
	}
	
	
	$scope.loadIPTC = function(ev, path){		           			       
	$scope.iptc = null;
	$scope.imagePath = path;				
	$http({url : 'galleries/iptc',				
			      method : "GET",				
				  headers : {'Content-Type':'application/json; charset=utf-8',
				  'path': path}})
				  .success(function(data){
					  $scope.iptc = data; 				 
					  console.log($scope.iptc);					  
					 })						
			}


	$scope.getIPTC = function(ev, path){		           			       
	$scope.iptc = null;
	$scope.imagePath = path;				
	$http({url : 'galleries/iptcinfo',				
			      method : "GET",				
				  headers : {'Content-Type':'application/json; charset=utf-8',
				  'path': path}})
				  .success(function(data){
					  $scope.iptc = data; 				 
					  console.log(data);			  
					 })					 
			}
			
	$scope.getExif = function(ev, path){	
	$scope.exif = null;
	$scope.imagePath = path;				
	$http({url : 'galleries/exif/'+path,				
			      method : "GET",				
	headers : {'Content-Type':'application/json; charset=utf-8'}})
				  .success(function(data){
					  $scope.exif = data; 				 
					  console.log(data);			  
					 })						

	}		
	
	$scope.updatePhotoName = function(data){		
		$http({url:'galleries/data/photoName',
							       method:'PUT',
								   data: {image:$scope.imagePath,photographerName:data},
								   headers : {'Content-Type':'application/json; charset=utf-8'}}
							).then(function successCallback(response){
								console.log('pobite gary',response.data);
							},function errorCallback(response) {})
	}
	
	
	$scope.updateCaption = function(data){		
		$http({url:'galleries/data/caption',
							       method:'PUT',
								   data: {image:$scope.imagePath,caption:data},
								   headers : {'Content-Type':'application/json; charset=utf-8'}}
							).then(function successCallback(response){
								console.log('pobite gary',response.data);
							},function errorCallback(response) {})
	}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 	   
	
	 
    $scope.delegate = function(fn,data){
	       fn(data);
	 }
	
	
    $scope.toTrustedHTML = function( html ){
				return $sce.trustAsHtml( html );
			}
	
	$scope.deletePic = function(img){
		   $scope.forConfirmData = {
                               fn: $scope.removePic,	   
                               item: img,	   
                               query: "Czy chcesz usunąć obrazek: <br />plik "+img.name+" ?"							
								    };		
		    $scope.openSubWindow('/templates/confirm_renderer.html','ngdialog-theme-dialog');
	}

			$scope.removePic = function(img){		   						
		     var dat = img;
			 console.log('imeassdff ',img)
			 	$http({url: 'galleries/data',
				headers:{'Content-Type':'application/json'},
				method: 'DELETE',
				data: dat
		      }).success(function(data){
                    console.log('ala ma kota',data);
                           if($scope.currPerson != ''){
							   $scope.loadPicByPerson(null,$scope.currPerson)
							   
						     }else{		  
							    $http.get('galleries/data').success(function(data){
								$scope.images = data.pathes;
								console.log('datka ',data)
						   })
						   }
			}).error(function(err){console.log('errror ',err)})

			 }

       $scope.incrementLimit = function(ev){
		   console.log('aaaa ',ev.target);
		   $scope.limit += 20;
	   }	
	
	
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


    $scope.loadToDBase = function(sub){
		console.log(sub.id)
		$scope.currSubDbase = [];
		angular.forEach($scope.subjectImages,function(item){
			$scope.currSubDbase.push(item.name);
		});
		$http({url:'galleries/data/subject',
				method:'PUT',
				data: {id:sub.id, photos: $scope.currSubDbase},
				headers : {'Content-Type':'application/json; charset=utf-8'}}
					).then(function successCallback(response){
						               console.log('odp',response)
							},function errorCallback(response) {
                                       console.log('odp',response)
		                       })
	   }

    $scope.test = function(){
	  alert('test');
	}
	
	/*
	$scope.loadIPTC = function(ev,img){
			
	    $http({url : 'galleries/iptcinfo',				
							method : "GET",				
							headers : {'Content-Type':'application/json; charset=utf-8',
										'path': img}})
						 .success(function(data){
							           $scope.iptc = data; 									   
							        	angular.forEach($scope.images,function(ig,$index){
												if(img === ig.name.name){
													ig.name.iptc = data;
													//$scope.images[$index].name.iptc = data;
													console.log('wybrany ',ig.name.iptc)	
												}
												
										})							 
					 }).error(function(err){console.log('błądziczek ',err)})
		
		
	};
		*/		
	$scope.loadPicData = function(add){
		$scope.images = [];				
		$http.get(add+'/data').success(function(data){			          
				  angular.forEach(data.pathes, function(item){                     
					  $scope.images.push({name : item.name, iptc : item.iptc});					  
				  })					

			   })                
		}	   
			
    $scope.dispPhoto = function(ev,image){	
       $scope.destinationgalleryData = []	
		console.log('img ', image);
		$scope.imagePath = image.name;
		$scope.iptc = image.iptc;	
     		
		angular.forEach(image.iptc.supplementalCategories,function(item,key){	
		  if(image.iptc.supplementalCategories instanceof Array){	   
		    //for(var i=0; i<image.iptc.supplementalCategories.length;i++){				
			//if(image.iptc.supplementalCategories[i] !== item){			
                 // console.log(image.iptc.supplementalCategories[i]+' = '+item)			
					$scope.destinationgalleryData.push({name:item})	
			//}			
		//}
		}})
     /*		
       angular.forEach($scope.galleryData,function(item,key){	
           if(image.iptc.supplementalCategories instanceof Array){	   
		    for(var i=0; i<image.iptc.supplementalCategories.length;i++){				
				if(image.iptc.supplementalCategories[i] !== item.name){
					continue;
				}
				delete item;
				//$scope.galleryData.splice(i, 1);
			}
		   }
	   })
       console.log('a tu co widzę? ',$scope.galleryData);
	   */
	}
			  
$scope.initUploader = function(data,mode){
    if(mode == 'person'){
		$scope.uploader = new FileUploader({           
			url: '/administrator/galleries/data/store',
			formData: [{person:data}]
        });       
	}else if(mode=='subject'){
		$scope.uploader = new FileUploader({           
			url: '/administrator/galleries/data/store',
			formData: [{subject:data}]
        }); 		
	}
		
		// FILTERS

        $scope.uploader.filters.push({
            name: 'imageFilter',
            fn: function(item /*{File|FileLikeObject}*/, options) {				   
                var type = '|' + item.type.slice(item.type.lastIndexOf('/') + 1) + '|';				
                return '|jpg|JPG|png|jpeg|bmp|gif|'.indexOf(type) !== -1;
            }
        });
			
			
        // CALLBACKS
		  

        $scope.uploader.onWhenAddingFileFailed = function(item /*{File|FileLikeObject}*/, filter, options) {		
            //console.info('onWhenAddingFileFailed', item, filter, options);
        };
        $scope.uploader.onAfterAddingFile = function(fileItem) {
            console.log('notoco',fileItem)
            //console.info('onAfterAddingFile', fileItem);
        };
        $scope.uploader.onAfterAddingAll = function(addedFileItems) {
            //console.info('onAfterAddingAll', addedFileItems);
        };
        $scope.uploader.onBeforeUploadItem = function(item) {
             //$scope.uploader.formData =  [{person:$scope.currPerson,subject:$scope.currSubject}] 
			  
			  console.log('person ',$scope.currPerson)            
			  //console.info('onBeforeUploadItem', item);			  			  
        };
        $scope.uploader.onProgressItem = function(fileItem, progress) {
            //console.info('onProgressItem', fileItem, progress);
        };
        $scope.uploader.onProgressAll = function(progress) {
            //console.info('onProgressAll', progress);
        };
        $scope.uploader.onSuccessItem = function(fileItem, response, status, headers) {
            //console.info('onSuccessItem', fileItem, response, status, headers);			  		  
			console.info('onSuccessItem', response);			  		  
        };
        $scope.uploader.onErrorItem = function(fileItem, response, status, headers) {
            //console.info('onErrorItem', fileItem, response, status, headers);
			  console.info('onErrorItem', response);
        };
        $scope.uploader.onCancelItem = function(fileItem, response, status, headers) {
            //console.info('onCancelItem', fileItem, response, status, headers);
        };
        $scope.uploader.onCompleteItem = function(fileItem, response, status, headers) {
            //console.log('onCompleteItem', response);              
			
				$http.get('galleries/data').success(function(data){	
                 if($scope.currPerson != '' || $scope.currSubject != null){
					  $scope.images = [];
					  console.log('patches',data.pathes)
					  angular.forEach(data.pathes,function(item){
						  //console.log('person ',item)
						  //console.log('demode',mode)
						 if(mode=='person'){ 
						  if(item.iptc.photographerName != null && item.iptc.photographerName.trim() == $scope.currPerson)
						  $scope.images.push(item)
						 }else if(mode=='subject'){
                          angular.forEach(item.iptc.supplementalCategories,function(itm){
							  if(itm.toLowerCase() == $scope.currSubject.name){
								 $scope.images.push(item);
                               return true;								 
							  }
						   })							 
						 }
					  
					  })
                   $scope.personImages = $scope.images;
				    console.log('po uploadzie', $scope.images)
				   }else{
					$scope.images = data.pathes;					
				   }
                 
			   })

			   
        };
        $scope.uploader.onCompleteAll = function() {
            //console.info('onCompleteAll');			  
        };
		}
			
		$scope.changeDescript = function(name,descript,add){				
				var itemDat = {'name': name,                              
						       'descript' : descript                    
					          }	                 
				//console.log(itemDat)			  
				$http({
				    url: add,
			       method: "PUT",
				   data: itemDat
			      }).success(function(data){console.log(data)})
				     .error(function(err){console.log(err)})
		 };
		 
		 
		$scope.loadSubjects = function(ev){
            $scope.images = [];
            $scope.currSubject = null;
            $scope.currPerson = '';
		   	$http({url : 'galleries/data/subjects',				
			      method : "GET"})
				  .success(function(data){
					  $scope.subjects = data; 				 
					  console.log(data);			  
					 });			
		} 
		 
		 
		$scope.loadPersons = function(ev){
            $scope.images = [];
            $scope.currSubject = null;
            $scope.currPerson = '';
		   	$http({url : 'galleries/data/persons',				
			      method : "GET"})
				  .success(function(data){
					  $scope.persons = data; 				 
					  console.log(data);			  
					 });						
			} 
		
		
		$scope.loadPicByPerson = function(ev,person){
		$scope.images = [];
		$scope.imagePath = '';
		$scope.currSubject = null;	
        $scope.currPerson = person;		    
		console.log('tutaj jest ',person);
				   	$http({url : 'galleries/data',				
						   method : "GET",
				          params: {person: person}
						 })
				  .success(function(data){
                    $scope.orderMode = 'iptc.urgency'					  
                    $scope.images = [];
					  angular.forEach(data.pathes, function(dt){	
                                     dt.iptc.urgency = parseFloat(dt.iptc.urgency); 			  					  
										$scope.images.push({'name': dt.name,'iptc': dt.iptc})										
					                   });
					  $scope.personImages = $scope.images;			  
					 });
	}
	
	
	$scope.loadPicBySubject = function(ev,subject){
        $scope.images = [];
		$scope.imagePath = '';
		$scope.currPerson = '';
		$scope.currSubject = subject;
		console.log('taKi temat ',subject);
       $http({url : 'galleries/data',				
						   method : "GET",
				          params: {subject: subject.name}
						 }).success(function(data){
							 $scope.images = [];
							  angular.forEach(data.pathes, function(dt){	
							     $scope.images.push({'name': dt.name,'iptc': dt.iptc})										
							  });
							$scope.subjectImages = $scope.images;
                       console.log('scope.subjectImages',$scope.subjectImages.length)
						 });
		
	}
	
    $scope.loadFromDBase = function(ev,subject){
        $scope.images = [];
        $scope.subjectImages = [];
		$http.get('galleries/galleryid/'+subject.id).then(function successCallback(response){
                                $scope.currSubject = subject
								$scope.images = response.data.images;
								$scope.subjectImages = response.data.images;
                                console.log('zdjątka: ',response.data.images);
							},function errorCallback(response) {
								console.log('errorzasty',response)
							})
			
	}
	
	  $scope.removeImages = function(ev){
		  $scope.images = []; 
	  }
	
	$scope.$watch('images',function(nVal,oVal){
		$scope.images = nVal
		console.log('images ',$scope.images)
		});	 

    }]);
		
	
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
app.config(function($routeProvider, $locationProvider) {
    $routeProvider.
    when('/', {
        templateUrl: '/templates/admin/super/galleries/master.html',
        controller: 'GalleriesController'
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