<div
        class="container-fluid ahm-container"
        ng-controller="ImagesSearchController"
>

    {{ csrf_field() }}


    <div class="row">

        <div class="col-xs-12 col-sm-12">

            @include('front.scenes.images.searchform')

        </div>

    </div>

    <div class="screen-search">

        <div class="searching-now" ng-if="showsearching">
            <img src="/images/loading.svg">
        </div>

        <div class="row row-search-results">
            <div class="col-xs-12 col-sm-12">


                <div class="row">
                    <div class="col-xs-12 col-sm-6">

                        <div class="slide-record-gallery" ng-if="data.images!=null">


                            <div id="photoswipe" photo-swipe images="data.images" figure-class="">
                            </div>

                            <div photo-slider oncontextmenu="return false"></div>

                        </div>

                    </div>
                </div>


            </div>
        </div>
    </div>
</div>