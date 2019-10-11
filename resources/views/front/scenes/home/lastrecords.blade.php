
<div
        class="last-records"
        ng-controller="LastRecordsController"
        ng-init="initData()"
>
    <h2 class="section-title">
        NAJNOWSZE NAGRANIA
    </h2>

    <div class="record-list-el" ng-repeat="record in records">
        <div class="row">
            <div class="col-xs-4 col-sm-3 col-md-2 col-left-icon">
                <img src="images/[[ record.rddata.type ]]-icon.svg" class="img-responsive svg-icon" alt="ikona typu nagrania - typ: [[ record.rddata.type ]]">
            </div>
            <div class="col-xs-8 col-sm-9 col-md-10 col-right-info">
                <h2 class="record-title">

                    <a href="demo/[[ record.rddata.type ]]/[[ record.rddata.id ]]-[[ record.rddata.alias ]]" ng-if="!auth">
                        <span class="hidden">przejście do nagrania</span> [[ record.rddata.title ]]
                    </a>

                    <a href="[[ record.rddata.type ]]/[[ record.rddata.id ]]-[[ record.rddata.alias ]]" ng-if="auth">
                        <span class="hidden">przejście do nagrania</span> [[ record.rddata.title ]]
                    </a>

                </h2>
                <span class="length-time" ng-bind="record.rdtime | secondsToTime"></span>
            </div>
        </div>
        <hr class="kreska" align="left" />        
    </div>
</div>
