<div ng-controller="GalleriesController">
<div class="col-md-2">
<div ng-init="loadPictures($event,'photos')" style="height:1000px; overflow-y:scroll; overflow-x:hidden">
<div ng-repeat="image in imageList" style="display:inline-block;">
<div style="border:1px solid #777; padding:2px; margin:2px; cursor:pointer"  ng-click="displayPhoto($event,image)">
<div><%image.path%></div>
<img ng-src="photo/<%image.path%>/photos/200" style="margin:auto" />

</div>

</div>

</div>
</div>
<div class="col-md-4">

<div class="h4"><% iptc %></div>
<img ng-src="photo/<%imagePath%>/photos/800" class="img-responsive" ng-click="getIPTC($event,imagePath)"  ng-if="imagePath != ''"/><br />
</div>

     <style>
            .my-drop-zone { border: dotted 3px lightgray; }
            .nv-file-over { border: dotted 3px red; } /* Default class applied to drop zones on over */
            .another-file-over-class { border: dotted 3px green; }

            html, body { height: 100%; }

            canvas {
                background-color: #f3f3f3;
                -webkit-box-shadow: 3px 3px 3px 0 #e3e3e3;
                -moz-box-shadow: 3px 3px 3px 0 #e3e3e3;
                box-shadow: 3px 3px 3px 0 #e3e3e3;
                border: 1px solid #c3c3c3;
                height: 100px;
                margin: 6px 0 0 6px;
            }
        </style>


<div class="col-md-6">
<div ng-controller="UploadCtrl" ng-init="loadPicData('/administrator/galleries')">
              <script type="text/ng-template" id="thumbnail.html">	
                <div ng-show="false"><img ng-src="photo/<% img.name.name %>/photos/150" /></div>
              </script>
				<div class="row" style="margin: 0 0 20px 0" ng-model="images" id="galeria">
               
				 <div ng-repeat="img in images" class="col-md-2" style="border:1px solid #ddd; margin: 0 0 1px 1px; min-height:175px; min-width:170px; background-color:#ccc" id="box_<% img.id %>">
                 <div style="display:inline-block; background-color:#fff; margin:5px 0" id="thumb_<% img.id %>"  draggable>				 					 
					<div style="height:150px; overflow:hidden; padding:3px; margin:0 0 5px 0">
						<div class="collapse"  id="collapseRoll_<% $index+1 %>">
						
						<textarea style="width:100%; height:142px; font-size:.9em"
						ng-model="img.name.iptc.caption"
						ng-blur="changeDescript(img.id,img.descript,'/admin/phot/descript')">						
						</textarea>
						
						<!--
						<textarea style="width:100%; height:142px; font-size:.9em"
						ng-model="iptc.caption"
						ng-blur="changeDescript(img.id,img.descript,'/admin/phot/descript')">						
						</textarea>
						-->
					   </div> 
					
					    <img ng-src="photo/<% img.name.name || img.name %>/photos/150" style="width:150px" class="img-responsive mythumb" id="<% $index+1 %>" />	                   						
                    </div>					   
	   					<button type="button" class="btn btn-default btn-xs" class="btn btn-danger" data-toggle="collapse" data-target="#collapseRoll_<% $index+1 %>" ng-click="loadIPTC($event,img.name.name || img.name)">
                        <span class="glyphicon glyphicon-pencil"></span> Opis
                       </button>		
                        <span class="glyphicon glyphicon-trash"></span> Usuń
                       </button>	
					   </div>					   
					   </div>
					
                  </div>

            <div class="row">			

                <div class="col-md-3">

                   <input type="file" nv-file-select="" uploader="uploader" multiple  /><br/>

                </div>
                <div class="col-md-9" style="margin-bottom: 40px">								
                    <p>Queue length: <% uploader.queue.length %></p>
					<table class="table">
                        <thead>
                            <tr>
                                <th width="50%">Name</th>
                                <th ng-show="uploader.isHTML5">Size</th>
                                <th ng-show="uploader.isHTML5">Progress</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>						    
                            <tr ng-repeat="item in uploader.queue">
                                <td>
                                    <strong><% item.file.name %></strong>
                                    <!-- Image preview -->
                                    <!--auto height-->
                                    <!--<div ng-thumb="{ file: item.file, width: 100 }"></div>-->
                                    <!--auto width-->
                                    <div ng-show="uploader.isHTML5" ng-thumb="{ file: item._file, height: 100 }"></div>
                                    <!--fixed width and height -->
                                    <!--<div ng-thumb="{ file: item.file, width: 100, height: 100 }"></div>-->
                                </td>
                                <td ng-show="uploader.isHTML5" nowrap>
								<% item.file.size/1024|number:2 %> MB</td>
                                <td ng-show="uploader.isHTML5">
                                    <div class="progress" style="margin-bottom: 0;">
                                        <div class="progress-bar" role="progressbar" ng-style="{ 'width': item.progress + '%' }"></div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span ng-show="item.isSuccess"><i class="glyphicon glyphicon-ok"></i></span>
                                    <span ng-show="item.isCancel"><i class="glyphicon glyphicon-ban-circle"></i></span>
                                    <span ng-show="item.isError"><i class="glyphicon glyphicon-remove"></i></span>
                                </td>
                                <td nowrap>
                                    <button type="button" class="btn btn-success btn-xs" ng-click="item.upload()" ng-disabled="item.isReady || item.isUploading || item.isSuccess">
                                        <span class="glyphicon glyphicon-upload"></span> Upload
                                    </button>
                                    <button type="button" class="btn btn-warning btn-xs" ng-click="item.cancel()" ng-disabled="!item.isUploading">
                                        <span class="glyphicon glyphicon-ban-circle"></span> Cancel
                                    </button>
                                    <button type="button" class="btn btn-danger btn-xs" ng-click="item.remove()">
                                        <span class="glyphicon glyphicon-trash"></span> Remove
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div>
                        <div>
                            Queue progress:
                            <div class="progress" style="">
                                <div class="progress-bar" role="progressbar" ng-style="{ 'width': uploader.progress + '%' }"></div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-success btn-s" ng-click="uploader.uploadAll()" ng-disabled="!uploader.getNotUploadedItems().length">
                            <span class="glyphicon glyphicon-upload"></span> Upload all
                        </button>
                        <button type="button" class="btn btn-warning btn-s" ng-click="uploader.cancelAll()" ng-disabled="!uploader.isUploading">
                            <span class="glyphicon glyphicon-ban-circle"></span> Cancel all
                        </button>
                        <button type="button" class="btn btn-danger btn-s" ng-click="uploader.clearQueue()" ng-disabled="!uploader.queue.length">
                            <span class="glyphicon glyphicon-trash"></span> Remove all
                        </button>
                    </div>

                </div>

            </div>

				  
</div>

</div>
</div>

<div ng-view></div>