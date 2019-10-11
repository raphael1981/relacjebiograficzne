angular.module('app').directive('draggable', ['$http', function($http) {

 return {

		  restrict: 'A',
         link: function(scope, element, attrs) {
              
			   if(scope.currPerson == '' && scope.currSubject == null){
				   return false;
			   }
		 
				var parentTop = 0;
				var parentLeft = 0;
				
				var colThumbBoxes = [];
				var actThumbBox = null;
				var isPossible = false;	
				var colImages = null;
				var indexBoxOld = 0;
				var indexBoxNew = 0;
				
				element.draggable({
						   cursor: "move",							
				          start: function (event, ui) {
							      parentTop = this.parentElement.offsetTop;
								   parentLeft = this.parentElement.offsetLeft;								  
								  colThumbBoxes = element.parent().parent().children();
								  
								  
								  	for(var i=0; i<colThumbBoxes.length; i++){
												   if(colThumbBoxes[i] == this.parentNode){			  
													   indexBoxOld = i;									    
													}}								  
								  this.style.zIndex = colThumbBoxes.length+1
								  console.log('terefere',colThumbBoxes)
								  
				          },
						  
						   drag: function (event, ui){
								var halfPosLeft = 0;
								var halfPosTop = 0;	
								isPossible = false;
								for(var i=0; i<colThumbBoxes.length; i++){
								   halfPosLeft = colThumbBoxes[i].offsetLeft +
								                 colThumbBoxes[i].offsetWidth / 2;
									halfPosTop = colThumbBoxes[i].offsetTop +
								                 colThumbBoxes[i].offsetHeight / 2;

												 
									if((element.offset().top < halfPosTop &&
									   element.offset().top >= colThumbBoxes[i].offsetTop)&&
									   (element.offset().left < halfPosLeft &&
									   element.offset().left >= colThumbBoxes[i].offsetLeft)
									  )
								   {
										colThumbBoxes[i].style.border = "1px solid #0a7";
										colThumbBoxes[i].style.backgroundColor = "#0a7";
										actThumbBox = colThumbBoxes[i];
										console.log('currBox ',element.offset().left);
										console.log('toBox ',colThumbBoxes[i].offsetLeft);
										isPossible = true
														
									}else{
									   colThumbBoxes[i].style.border = "1px solid #ddd";
									   colThumbBoxes[i].style.backgroundColor = "#ccc";										
									}								  
								}															
													   
						   },
							
							
				          stop: function (event, ui) {
							     var domImages = [];
                               console.log('stop ',event.target)
							 	this.style.left = '0px';
								this.style.top = '0px';								
								console.log('actThumbox ', actThumbBox)
							     if(isPossible){
										actThumbBox.style.border = "1px solid #ddd";
										actThumbBox.style.backgroundColor = "#ccc";
										var oldContent = actThumbBox.getElementsByTagName('div')[0]					 
										
										if(oldContent !== this){
											var content;
											for(var i=0; i<colThumbBoxes.length; i++){						
													if(colThumbBoxes[i] == actThumbBox){			  
													   indexBoxNew = i;									    
													}												
											}
												                                        
											if(indexBoxOld > indexBoxNew){
												for(var i=indexBoxNew; i<indexBoxOld; i++){
												 try{
												       content = colThumbBoxes[i]
																		  .getElementsByTagName('div')[0]
														colThumbBoxes[i]
															.removeChild(content);
														colThumbBoxes[i+1]
															.appendChild(content);											 
													
													}catch(e){console.log('new>old ',e,i)}												
												}
											
											}else if(indexBoxOld < indexBoxNew){
													for(var i=indexBoxNew; i>indexBoxOld; i--){
													try{
														  content = colThumbBoxes[i]
																		  .getElementsByTagName('div')[0]
														colThumbBoxes[i]
															.removeChild(content);																								
														colThumbBoxes[i-1]
															.appendChild(content);											
													}catch(e){console.log('old<new ',e,i)}
																									
												}
											
											}
											
																				
											
										  try{
											this.parentNode.removeChild(this)
											actThumbBox.appendChild(this);
											}catch(e){console.log('finall',e)}
																																	
								
											
									   var temp;
									   var domId;
									   
									   colImages = document.getElementById('galeria').          
										                     getElementsByTagName('img');
															 
															 
										angular.forEach(colImages,function(item,index){
											domImages.push({path : item.src, id : item.id, name: item.name})
										})
									   
									   
										for(var i=0; i<colImages.length; i++){
										     temp = colThumbBoxes[i]
												       .getElementsByTagName('div')[0].id.split('_')
											  domId = temp[1];	      
												for(var j=0; j<scope.images.length; j++){
														if(scope.images[j].id == domId)
														scope.images[j].order = i+1;
												}															
												
												}
																					
											
											$http({
											//url:"photos/order",
											//url:scope.$parent.addForPicture+'/order',											
											url:'galleries/person/order',
											method: "PUT",
											headers: {'Content-Type': 'application/json'},
											data: domImages										
											}).success(function(data){
												    
												   //var temp = data.split(/\r\n\r\n/)
												   //scope.images = jQuery.parseJSON(temp[temp.length-1])
												   //console.log('scope.images ',scope.images)
												   console.log('data',data)
												});											    
											}
																													
										this.style.zIndex = '1';

									}

				          }
				      
					  })               
        }

    }
					
	
}]);
//////////////////////////////////////////////////////////////////////////