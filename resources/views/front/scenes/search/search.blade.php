<div
        class="container-fluid ahm-container"
        ng-controller="SearchNoAuthController"
>

    {{ csrf_field() }}


    <div class="row">

        <div class="col-xs-12 col-sm-12">
         <!--
            <h2 class="section-title-bigger">
                Wyszukaj
            </h2>
         -->
            @include('front.scenes.search.searchform')


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

                        <div class="row row-search-result" ng-repeat="r in data.results" ng-if="(($index % 2) == 0)">

                            <div class="col-xs-12 col-sm-12 col-md-2 col-icon-search">
                                <img src="/images/[[ r.record.type ]]-icon.svg" class="img-responsive">
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-10 col-fragments">


                                <div ng-if="auth">

                                    <h2 class="interviewee-name">

                                            <a
                                                    href="[[ r.record.type ]]/[[ r.record.rid ]]-[[ r.record.alias ]]/?fraza=[[ data.search.frase ]]&stype=[[ data.search.searchmode ]]"

                                            >
                                                [[ r.record.title ]]
                                            </a>

                                    </h2>
                                    <div
                                            search-fragments="[[ r.fragments ]]"
                                            record="[[ r.record ]]"
                                            frase="[[ data.search.frase ]]"
                                            auth="[[ auth ]]"
                                            type="[[ data.search.searchmode ]]"
                                    >

                                    </div>

                                </div>


                                <div ng-if="!auth">

                                    <h2 class="interviewee-name">

                                        <a
                                                href="demo/[[ r.record.type ]]/[[ r.record.rid ]]-[[ r.record.alias ]]/?fraza=[[ data.search.frase ]]&stype=[[ data.search.searchmode ]]"

                                        >
                                            [[ r.record.title ]]
                                        </a>

                                    </h2>
                                    <div
                                            search-fragments="[[ r.fragments ]]"
                                            record="[[ r.record ]]"
                                            frase="[[ data.search.frase ]]"
                                            auth="[[ auth ]]"
                                            type="[[ data.search.searchmode ]]"
                                    >

                                    </div>

                                </div>



                                {{--<div ng-if="auth">--}}

                                    {{--<h2 class="interviewee-name">--}}

                                        {{--<a--}}
                                                {{--href="#"--}}
                                                {{--ng-click="sendPostToRecordWithFrase(r.record.type+'/'+r.record.rid+'-'+r.record.alias, data.search.frase);$event.preventDefault();"--}}
                                        {{-->--}}
                                            {{--[[ r.record.title ]]--}}
                                        {{--</a>--}}

                                    {{--</h2>--}}
                                    {{--<div--}}
                                            {{--search-fragments="[[ r.fragments ]]"--}}
                                            {{--record="[[ r.record ]]"--}}
                                            {{--frase="[[ data.search.frase ]]"--}}
                                            {{--auth="[[ auth ]]"--}}
                                    {{-->--}}

                                    {{--</div>--}}

                                {{--</div>--}}


                                {{--<div ng-if="!auth">--}}

                                    {{--<h2 class="interviewee-name">--}}

                                        {{--<a href="#" ng-click="$event.preventDefault();sendPostIntentToLogin(r, data.search.frase, false)">--}}
                                            {{--[[ r.record.title ]]--}}
                                        {{--</a>--}}

                                    {{--</h2>--}}
                                    {{--<div--}}
                                            {{--search-fragments="[[ r.fragments ]]"--}}
                                            {{--record="[[ r.record ]]"--}}
                                            {{--frase="[[ data.search.frase ]]"--}}
                                            {{--auth="[[ auth ]]">--}}

                                    {{--</div>--}}

                                {{--</div>--}}


                            </div>

                        </div>


                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6">

                        <div class="row row-search-result" ng-repeat="r in data.results" ng-if="(($index % 2) == 1)">

                            <div class="col-xs-12 col-sm-12 col-md-2 col-icon-search">
                                <img src="/images/[[ r.record.type ]]-icon.svg" class="img-responsive">
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-10 col-fragments">


                                <div ng-if="auth">

                                    <h2 class="interviewee-name">

                                        <a
                                                href="[[ r.record.type ]]/[[ r.record.rid ]]-[[ r.record.alias ]]/?fraza=[[ data.search.frase ]]&stype=[[ data.search.searchmode ]]"

                                        >
                                            [[ r.record.title ]]
                                        </a>

                                    </h2>
                                    <div
                                            search-fragments="[[ r.fragments ]]"
                                            record="[[ r.record ]]"
                                            frase="[[ data.search.frase ]]"
                                            auth="[[ auth ]]"
                                            type="[[ data.search.searchmode ]]"
                                    >

                                    </div>

                                </div>


                                <div ng-if="!auth">

                                    <h2 class="interviewee-name">

                                        <a
                                                href="demo/[[ r.record.type ]]/[[ r.record.rid ]]-[[ r.record.alias ]]/?fraza=[[ data.search.frase ]]&stype=[[ data.search.searchmode ]]"

                                        >
                                            [[ r.record.title ]]
                                        </a>

                                    </h2>
                                    <div
                                            search-fragments="[[ r.fragments ]]"
                                            record="[[ r.record ]]"
                                            frase="[[ data.search.frase ]]"
                                            auth="[[ auth ]]"
                                            type="[[ data.search.searchmode ]]"
                                    >

                                    </div>

                                </div>


                            </div>
                        </div>

                    </div>


                </div>

                <div class="more-section" ng-if="morebutton">
                    <a href="#" class="button-more-search" ng-click="addNext();$event.preventDefault()">
                        pokaż więcej
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>