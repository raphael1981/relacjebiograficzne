<div
        class="container-fluid ahm-container"
        ng-controller="RecordNoAuthController"
        ng-init="initData()"
>
    <div class="row row-record-noauth">
        <div class="col-md-6">
            <div class="row" >
                <div class="col-xs-12 col-sm-12">
                    @for($i=0, $ile=count($interviewees); $i<$ile; $i++)
                        <h2 class="section-title-bigger">{{strtoupper($interviewees[$i]->name)}} {{strtoupper($interviewees[$i]->surname)}}</h2>
                    @endfor
                    <div video-player ng-show="currentRecord.type == 'video'"></div>
                    <div audio-player ng-show="currentRecord.type == 'audio'"></div>
                </div>
            </div>
			@if($smallbiography != '')
            <div class="row biogram-row">               
                <div class="col-xs-12 col-sm-12">
                    <h3 class="section-title-h3">Biogram</h3>
                    {!! $smallbiography !!}
                </div>
            </div>
			@endif
        </div>
        <div class="col-md-6">

            <div class="row">
                <div class="col-xs-12 col-sm-12">
                    {{--<form action="{{ url('autoryzacja') }}" method="post" name="intentForm">--}}

                        {{--{{ csrf_field() }}--}}

                        {{--<input type="hidden" name="intent_uri" value="{{ $uri_intent }}">--}}

                        {{--<h2 class="info-not-login">--}}
                            {{--<a href="#" ng-click="goToLoginWithIntent({{ $data }});$event.preventDefault();">--}}
                                {{--Nagranie dostępne po zalogowaniu--}}
                            {{--ng-click="goToLoginWithIntent('{{ $uri_intent }}');$event.preventDefault();"--}}
                            {{--</a>--}}
                            {{--<a href="#" onclick="document.forms['intentForm'].submit(); return false;">--}}
                                {{--Nagranie dostępne po zalogowaniu--}}
                            {{--</a>--}}
                        {{--</h2>--}}

                    {{--</form>--}}

                    <h2 class="info-not-login">
                        <a href="{{ url('autoryzacja') }}?intent={{ $uri_intent }}">
                            Nagranie dostępne po zalogowaniu
                        </a>
                    </h2>

                </div>
            </div>

        </div>
    </div>
</div>