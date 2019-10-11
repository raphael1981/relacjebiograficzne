<div class="container-fluid ahm-container">
    <div
            class="row row-art-view"
            ng-controller="ArticleController as vm"
            ng-init="initData({{ $article->id }})"
    >
        <div class="col-xs-12 col-sm-12 col-md-7">
            <h2 class="section-title">
                {{ $article->title }}
            </h2>
            <div class="art-content">
                {!! $article->content !!}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-5 image-thumbs col-art-gallery">



            <!-- Dem photoswipe -->



            <div
                    class="image-thumb"
                    ng-repeat="gallery in data.galleries"
            >

                    <div id="photoswipe" photo-swipe images="slides[$index]" figure-class="">
                    </div>
            </div>


            <div photo-slider></div>

            {{--@include('front.photoswipeview')--}}

        </div>
    </div>
</div>