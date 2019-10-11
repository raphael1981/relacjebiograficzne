<div class="slide-record-gallery">
    <div id="photoswipe" gallery-id="{{ $data->id }}" load-all="true" get-url="/interviewee/images/get/photos/" get-type="interviewee" photo-swipe images="interimages" figure-class="">
    </div>
</div>

<div class="gallery-more-link">
    <a href="#" ng-click="showMoreImages();$event.preventDeafault()" ng-if="is_more" class="">
        więcej...
    </a>
</div>

<div photo-slider oncontextmenu="return false"></div>
