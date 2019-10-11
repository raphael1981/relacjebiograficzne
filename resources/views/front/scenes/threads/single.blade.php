<div
        class="container-fluid ahm-container"
        ng-controller="ThreadController"
        ng-model="tid"
        ng-init="tid={{ $tid }};initData()"
>

    <div
            class="row"
    >

        <div class="col-xs-12 col-sm-12">

            <h2 class="section-title">
                {{ $thread->name }}
            </h2>

        </div>


    </div>

    <div class="row interviewees-row">
        <div class="col-interviewee" ng-repeat="rec in records">

            <div class="interviewee-content">
                {{--<div class="red-over"></div>--}}
                <div class="loading">
                    <img src="/images/loading.svg" alt="obrazek sygnalizujący ładowanie obrazka">
                </div>
                <img src="/img/filter/[[ rec.interviewee.portrait ]]/[[ rec.interviewee.disk ]]/440" class="img-responsive" alt="[[ rec.interviewee.name ]] [[ rec.interviewee.surname ]]">

            </div>

            @if($auth)
                <h3 class="interviewee-title">
                    <a href="/[[ rec.type ]]/[[ rec.id ]]-[[ rec.alias ]]">
                        <span class="hidden">Przejście do nagrania</span> [[ rec.interviewee.name ]] [[ rec.interviewee.surname ]]
                    </a>
                </h3>
            @else
                <h3 class="interviewee-title">
                    <a href="/demo/[[ rec.type ]]/[[ rec.id ]]-[[ rec.alias ]]">
                        <span class="hidden">Przejście do nagrania</span> [[ rec.interviewee.name ]] [[ rec.interviewee.surname ]]
                    </a>
                </h3>
            @endif


        </div>
    </div>

</div>