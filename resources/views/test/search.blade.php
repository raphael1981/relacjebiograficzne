<div class="container-fluid ahm-container" style="height: 100vh"
     ng-controller="SearchNoAuthController">
    <form class="form-inline form-search-inline"
          ng-submit="onSubmit()"
          ng-init="initOnLoad()">
        <div class="form-group group-search">

            <div class="input-group">
                <input
                        autocomplete="off"
                        type="text"
                        name="frase"
                        class="form-control"
                        id="tag"
                        placeholder="Wpisz frazę"
                        ng-model="searchTextTag"
                        ng-change="getSuggest()"
                >

            </div>
            <div style="position:relative; top:3px" ng-show="searchtag.length > 0" id="tag">
                <div style="padding: 0 0 0 0;
		         min-height:100px;
                 max-height:200px;
				 overflow:auto;
				 position:absolute;
				 width: 100%;
				 z-index:101;
				 line-height:130%;
				 font-size:1.2em;
				 ">
                    <div class="well roleta">
                        <ul>
                            <li ng-repeat="item in searchtag | unique:'id'">
                                <a ng-href="/bookstore/booksbytag/[[item.id]]">[[ item.text ]]</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <br />
    <div style="clear:both">
        {!! $intervals !!}
    </div>
</div>