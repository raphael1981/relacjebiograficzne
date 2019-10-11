<div class="container-fluid ahm-container">
    <div
            class="row row-gallery-single"
            ng-controller="GalleryController"
            ng-model="gid"
            ng-model="mode"
            ng-init="gid={{ $id  }};mode='{{ $mode }}';initData()"
    >
        <div class="col-xs-12 col-sm-12">         
            <div class="row title-gallery">
                <div class="col-xs-12 col-sm-12">
                    <h2 class="section-title">
                        {{ $name  }}
                    </h2>
                </div>				
            </div>

            {{--<div id="photoswipe" gallery-id="{{ $id  }}" load-all="true" get-from="/get/full/gallery/" get-type="gallery" photo-swipe images="gallery" figure-class="col-xs-12 col-sm-12 col-md-3">--}}
            {{--</div>--}}

            <div id="photoswipe" gallery-id="{{ $id  }}" photo-swipe images="gallery" figure-class="col-xs-12 col-sm-12 col-md-3">
            </div>
            <div photo-slider oncontextmenu="return false"></div>
        </div>
    </div>

</div>