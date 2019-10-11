<div class="container-fluid ahm-container">

    <div
            class="row"
            ng-controller="GalleriesController"
            ng-init=";initData()"
    >


        <div class="col-xs-12 col-sm-12">

            <div class="row">
                <div class="col-xs-12 col-sm-12">
				   <!--
                    <h2 class="section-title">
                        Galerie
                    </h2>
					-->
                </div>
            </div>

            <div class="row galleries-row">
                <div
                        class="col-xs-12 col-sm-12 col-md-3 col-gallery-element"
                        ng-repeat="gallery in galleries"
                >

                    <div class="image-gallery-content [[ gallery.orientation ]]">                       
                        <a href="[[ gallery.link ]]">						  
						 <!--<div class="curtain"></div>-->
                            <img src="/image/[[ gallery.first_picture.source ]]/[[ gallery.first_picture.disk ]]/600" class="img-responsive">
                            <img src="/images/loading.svg" class="loading">
                        </a>                     				
                    </div>
                    <h2 class="gallery-title" style="margin:0 0 4% 0">
                        <a href="[[ gallery.link ]]">
                            [[ gallery.gallery.name  | capitalize]]
                        </a>
                    </h2>

                </div>
            </div>

        </div>
    </div>

</div>