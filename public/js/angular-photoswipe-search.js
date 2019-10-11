var ngPhotoSwipe = angular.module('ngPhotoSwipe', []);

ngPhotoSwipe.directive('photoSwipe', ['$http', '$location', function ($http, $location) {
	var template =
			'<div class="photoswipe-gallery" itemscope itemtype="http://schema.org/ImageGallery">' +
			'<div photo-gallery images="images" class="{{ figureclass }}"></div>' +
			'</div>';

	var initPhotoSwipeFromDOM = function(gallerySelector, config) {


		var parseThumbnailElements = function(el) {
			//console.log(el);
			var thumbElements = el.childNodes,
					numNodes = thumbElements.length,
					items = [],
					figureEl,
					childElements,
					linkEl,
					size,
					item;



			for(var i = 0; i < numNodes; i++) {
				figureEl = thumbElements[i];
				// include only element nodes
				if(figureEl.nodeType !== 1) {
					continue;
				}

				linkEl = figureEl.getElementsByTagName('a')[0];
				size = linkEl.getAttribute('data-size').split('x');

				// create slide object
				item = {
					src: linkEl.getAttribute('href'),
					w: parseInt(size[0], 10),
					h: parseInt(size[1], 10)
				};

				if(figureEl.children.length > 1) {
					// <figcaption> content
					item.title = figureEl.children[1].innerHTML;
				}

				if(linkEl.children.length > 0) {
					// <img> thumbnail element, retrieving thumbnail url
					item.msrc = linkEl.children[0].getAttribute('src');
				}

				item.el = figureEl; // save link to element for getThumbBoundsFn
				items.push(item);
			}
			//console.log(items);
			return items;
		};

		// find nearest parent element
		var closest = function closest(el, fn) {
			return el && ( fn(el) ? el : closest(el.parentNode, fn) );
		};

		// triggers when user clicks on thumbnail
		var onThumbnailsClick = function(e) {
			e = e || window.event;
			e.preventDefault ? e.preventDefault() : e.returnValue = false;

			var eTarget = e.target || e.srcElement;

			var clickedListItem = closest(eTarget, function(el) {
				return (el.tagName && el.tagName.toUpperCase() === 'FIGURE');
			});

			if(!clickedListItem) {
				return;
			}

			// find index of clicked item
			var clickedGallery = clickedListItem.parentNode,
					childNodes = clickedListItem.parentNode.childNodes,
					numChildNodes = childNodes.length,
					nodeIndex = 0,
					index;

			for (var i = 0; i < numChildNodes; i++) {
				if(childNodes[i].nodeType !== 1) {
					continue;
				}

				if(childNodes[i] === clickedListItem) {
					index = nodeIndex;
					break;
				}
				nodeIndex++;
			}

			if(index >= 0) {
				openPhotoSwipe( index, clickedGallery );
			}
			return false;
		};

		// parse picture index and gallery index from URL (#&pid=1&gid=2)
		var photoswipeParseHash = function() {
			var hash = window.location.hash.substring(1),
					params = {};

			if(hash.length < 5) {
				return params;
			}

			var vars = hash.split('&');
			for (var i = 0; i < vars.length; i++) {
				if(!vars[i]) {
					continue;
				}
				var pair = vars[i].split('=');
				if(pair.length < 2) {
					continue;
				}
				params[pair[0]] = pair[1];
			}

			if(params.gid) {
				params.gid = parseInt(params.gid, 10);
			}

			if(!params.hasOwnProperty('pid')) {
				return params;
			}
			params.pid = parseInt(params.pid, 10);
			return params;
		};

		// opens photoswipe
		var openPhotoSwipe = function(index, galleryElement, disableAnimation) {
			var pswpElement = document.querySelectorAll('.pswp')[0],
					gallery,
					options,
					items;

			items = parseThumbnailElements(galleryElement);


			// define options (if needed)
			options = {
				index: index,
				galleryUID: galleryElement.getAttribute('data-pswp-uid'),
				getThumbBoundsFn: function(index) {
					// See Options -> getThumbBoundsFn section of docs for more info

					var thumbnail = items[index].el.getElementsByTagName('img')[0]; // find thumbnail
					if (!thumbnail)
						thumbnail = items[index].el.getElementsByTagName('video')[0]

					var pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
							rect = thumbnail.getBoundingClientRect();

					return { x:rect.left, y:rect.top + pageYScroll, w:rect.width };
				},
				maxSpreadZoom: 4,
				getDoubleTapZoom: function(isMouseClick, item) {
					// isMouseClick          - true if mouse, false if double-tap
					// item                  - slide object that is zoomed, usually current
					// item.initialZoomLevel - initial scale ratio of image
					//                         e.g. if viewport is 700px and image is 1400px,
					//                              initialZoomLevel will be 0.5
					if(isMouseClick) {
						// is mouse click on image or zoom icon
						// zoom to original
						return 1;
						// e.g. for 1400px image:
						// 0.5 - zooms to 700px
						// 2   - zooms to 2800px
					} else {
						// is double-tap
						// zoom to original if initial zoom is less than 0.7x,
						// otherwise to 1.5x, to make sure that double-tap gesture always zooms image
						return item.initialZoomLevel < 0.7 ? 1 : 1.5;
					}
				}
			};

			if(disableAnimation) {
				options.showAnimationDuration = 0;
			}


			//items = [
			//	{
			//		src: 'https://farm2.staticflickr.com/1043/5186867718_06b2e9e551_b.jpg',
			//		w: 964,
			//		h: 1024
			//	},
			//	{
			//		src: 'https://farm7.staticflickr.com/6175/6176698785_7dee72237e_b.jpg',
			//		w: 1024,
			//		h: 683
			//	}
			//];
			//
			//
			//options = {
			//	// history & focus options are disabled on CodePen
			//	history: false,
			//	focus: false,
			//
			//	showAnimationDuration: 0,
			//	hideAnimationDuration: 0
			//
			//};

			//var id = gallerySelector.substring(1);
			//var gid = angular.element(document.getElementById(id)).attr('data-gallery-id');




			if(config.gid && config.loadAll && config.getUrl && config.getType){



				$http.get($location.protocol()+'://'+$location.host()+config.getUrl+config.gid)
						.then(
								function successCallback(response){

									//console.log(response.data);

									switch (config.getType){

										case 'gallery':

											items = refactorAllDataGallerySection(response.data);

											break;

										case 'interviewee':

											items = refactorAllDataIntervieweeSection(response.data[0]);
											//console.log(items);

											break;

										case 'article':

											items = refactorAllDataArticleSection(response.data);

											break;

									}



									options = {
										// history & focus options are disabled on CodePen
										history: false,
										focus: false,
										captionEl: true,
										showAnimationDuration: 0,
										hideAnimationDuration: 0,
										index: options.index

									};

									gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
									gallery.init();

								},
								function errorCallback(response){

								}
						)

			}else{

				console.log(items);
				console.log(options);
				gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
				gallery.init();

			}


			// Pass data to PhotoSwipe and initialize it

		};


		var refactorAllDataGallerySection = function(data){
			//console.log(data);

			var items = [];

			angular.forEach(data, function(value, key){

				items.push({
					src: $location.protocol()+'://'+$location.host()+'/image/'+value.source+'/'+value.disk,
					title: value.caption,
					w: value.size[0],
					h: value.size[1]
				});

			});

			return items;

		}


		var refactorAllDataIntervieweeSection = function(data){
			//console.log(data);

			var items = [];

			angular.forEach(data, function(value, key){

				items.push({
					src: value.src,
					title: value.caption,
					w: value.sizeArray[0],
					h: value.sizeArray[1]
				});

			});

			return items;

		}


		var refactorAllDataArticleSection = function(data){
			//console.log(data);

			var items = [];

			angular.forEach(data, function(value, key){

				items.push({
					src: $location.protocol()+'://'+$location.host()+'/image/'+value.source+'/'+value.disk,
					title: value.caption,
					w: value.size[0],
					h: value.size[1]
				});

			});

			return items;

		}



		// loop through all gallery elements and bind events
		var galleryElements = document.querySelectorAll(gallerySelector);

		for(var i = 0, l = galleryElements.length; i < l; i++) {
			galleryElements[i].setAttribute('data-pswp-uid', i + 1);
			galleryElements[i].onclick = onThumbnailsClick;
		}

		// Parse URL and open gallery if it contains #&pid=3&gid=1
		var hashData = photoswipeParseHash();
		if(hashData.pid > 0 && hashData.gid > 0) {
			openPhotoSwipe(hashData.pid - 1,  galleryElements[hashData.gid - 1], true);
		}





	};

	var linkFn = function(scope, elem, attr) {
		//console.log(attr);
		//console.log(attr.galleryId);
		//console.log(Boolean(attr.loadAll));

		scope.figureclass = attr.figureClass;

		var actionOptions = {};

		if(attr.galleryId) {

			actionOptions = {
				gid: attr.galleryId
			};

		}

		if(Boolean(attr.loadAll)){
			actionOptions.loadAll = true;
		}

		if(attr.getUrl){
			actionOptions.getUrl = attr.getUrl;
		}


		if(attr.getType){
			actionOptions.getType = attr.getType;
		}



		initPhotoSwipeFromDOM('#' + elem.attr('id'), actionOptions);
	};

	return {
		restrict: 'EA',
		replace: true,
		scope: {
			images: '=',
			width: '=',
			height: '=',
			template: '='
		},
		template: template,
		link: linkFn
	};
}]);

ngPhotoSwipe.directive('photoSlider', [ function () {
	var template =
			'<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">' +
			'<div class="pswp__bg"></div>' +
			'<div class="pswp__scroll-wrap">' +
			'<div class="pswp__container">' +
			'<div class="pswp__item"></div>' +
			'<div class="pswp__item"></div>' +
			'<div class="pswp__item"></div>' +
			'</div>' +
			'<div class="pswp__ui pswp__ui--hidden">' +
			'<div class="pswp__top-bar">' +
			'<div class="pswp__counter"></div>' +
			'<button class="pswp__button pswp__button--close" title="Close (Esc)"></button>' +
				//'<button class="pswp__button pswp__button--share" title="Share"></button>' +
			'<button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>' +
			'<button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>' +
			'<div class="pswp__preloader">' +
			'<div class="pswp__preloader__icn">' +
			'<div class="pswp__preloader__cut">' +
			'<div class="pswp__preloader__donut"></div>' +
			'</div>' +
			'</div>' +
			'</div>' +
			'</div>' +
			'<div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">' +
			'<div class="pswp__share-tooltip"></div>' +
			'</div>' +
			'<button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">' +
			'</button>' +
			'<button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">' +
			'</button>' +
			'<div class="pswp__caption">' +
			'<div class="pswp__caption__center"></div>' +
			'</div>' +
			'</div>' +
			'</div>' +
			'</div>';

	return {
		restrict: 'EA',
		replace: true,
		template: template
	};
}]);

ngPhotoSwipe.directive('photoGallery', [ function () {
	var template =
			'<figure class="{{ fclass }} {{ img.orientation }}" itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject" ng-repeat="img in images">'+
			'<div class="image-content" ng-switch on="img.type">' +
			'<div ng-switch-when="video">' +
			'<a href="{{img.safeSrc}}" itemprop="contentUrl" data-size="{{img.size}}">' +
			'<video width="400" controls>' +
			'<source ng-src="{{img.safeSrc}}" type="video/mp4">' +
			'</video>' +
			'</a>' +
			'</div>' +
			'<div ng-switch-default>' +
			'<a href="{{img.src}}" itemprop="contentUrl" data-size="{{img.size}}">' +
			'<div class="curtain onegall"></div>'+
			'<img ng-src="{{img.thumb}}" itemprop="thumbnail" alt="{{img.caption}}" class="img-responsive" />' +
			'</a>' +
			'<div class="tlo-gif"><img src="/images/loading.svg"></div>'+
			'</div>' +
			'</div>' +

			'<figcaption itemprop="caption description">{{img.caption}}</figcaption>' +
			'</figure>';

	var link = function(scope, elem, attr) {
		//console.log(attr.$attr.figclass);
		//scope.fclass = attr.$attr.figclass;
	};

	return {
		restrict: 'EA',
		scope: {
			images: '=',
			width: '=',
			height: '='
		},
		replace: true,
		template: template,
		link: link
	};
}]);