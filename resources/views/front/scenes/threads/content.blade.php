<div class="container-fluid ahm-container">

    <div
            class="row"
            ng-controller="ThreadsController"
            ng-init="initData()"
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

            <div class="threads-row">
                <div
                        class="col-thread-element"
                        ng-repeat="thread in threads"
                >

                    <div class="threads-row-el">

                        <div class="thread-title">
                            <h2>
                                <a href="/temat/[[ thread.thread.id ]]-[[ thread.thread.alias ]]">
                                    [[ thread.thread.name ]]
                                </a>
                            </h2>
                        </div>


                        <div class="left-thread">
                            <div class="rel-inside">
                                <span class="info-count-data">
                                    <span class="hidden">Ilość nagrań typu video</span>
                                    <span class="how-many">
                                        [[ thread.rvideo ]]
                                    </span>
                                </span>
                                <img src="/images/video-icon.svg" class="img-responsive img-type-icon" alt="ikona video">
                            </div>
                        </div>

                        <div class="right-thread">
                            <div class="rel-inside">
                                <span class="info-count-data">
                                    <span class="hidden">Ilość nagrań typu audio</span>
                                    <span class="how-many">
                                        [[ thread.raudio ]]
                                    </span>
                                </span>
                                <img src="/images/audio-icon.svg" class="img-responsive img-type-icon" alt="ikona audio">
                            </div>
                        </div>

                    </div>


                </div>
            </div>

        </div>
    </div>

</div>