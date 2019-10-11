<div class="container-fluid ahm-container" ng-controller="RecordController" ng-model="frase" ng-init="init({{$data}},{{$fragments}},{{$time}}); initData({{$data->id}}); frase='{{$phrase}}';">

	@if(!is_null($phrase))
		<div class="row frase-row" ng-init="getSearchResult({{$time}},'{{$phrase}}')">
			<div class="col-xs-12 col-sm-12">
				<i class="fa fa-search" aria-hidden="true"></i> Powrót do przeszukiwania frazy
				<a href="{{ url('wyszukiwanie#/?type=t&q='.$phrase) }}">
					{{ $phrase }}
				</a>
			</div>
		</div>
		@else
			@if(!is_null($time))
				<div class="row frase-row" ng-init="getIndexSearchResult({{$time}})">
					<div class="col-xs-12 col-sm-12"></div>
				</div>
		     @endif
	@endif



	<div class="row">
		<div class="col-md-6">
			<div class="row" >
				<div class="col-xs-12 col-sm-12">
					@for($i=0, $ile=count($interviewees); $i < $ile; $i++)
						<h2 class="section-title-bigger">{{strtoupper($interviewees[$i]->name)}} {{strtoupper($interviewees[$i]->surname)}}<h2 class="section-title-bigger">
					@endfor
					@if(isset($interviewees[0]->portrait))
					<div><img src="/image/{{ $interviewees[0]->portrait }}/portraits/405" style="width:100%" ng-show="currentRecord.type == 'audio'" /></div>
				    @endif
					<div video-player ng-show="currentRecord.type == 'video'"></div>
					<div audio-player ng-show="currentRecord.type == 'audio'"></div>
				</div>
			</div>
			<div class="row" ng-if="showgallerysection">
				<div class="col-xs-12 col-sm-12">
				<h3 class="section-title-h3">Galeria</h3>
					@include('front.scenes.recordcards.recordgallery')
				</div>
			</div>
			<div class="row biogram-row">
			@if(isset($interviewees[0]) && $interviewees[0]->biography!=='')
				<div class="col-xs-12 col-sm-12">
					<h3 class="section-title-h3">Biogram</h3>
					  {!! $interviewees[0]->biography !!}
				</div>
			 @endif
			</div>
			<div class="row" ng-hide="">
				<div class="col-xs-12 col-sm-12">

					<div linked-records linked="{{ $data->id }}"></div>

				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="row">
				<div class="col-xs-12 col-sm-12">
				{{--<form ng-submit="searchByPhrase($event)" class="form-inline form-search-inline">--}}
					<form ng-submit="searchByPhraseES({{$data->id}})" class="form-inline form-search-inline">
					<input type="text"
						   class="form-control search"
						   placeholder="Wpisz szukaną frazę"
						   maxlength="1000"
						   ng-model="search.phrase"
						   ng-value="search.phrase"
						   ng-focus="clearSearch($event)">
                   <button type="submit" class="btn-search"></button>
                  </form>
					<!--<image src="/images/lupka.png" ng-click="searchByPhrase($event)" />-->
				</div>
				{{--<button ng-click="searchByPhraseES({{$data->id}})">Jadymy</button>--}}
			</div>
    		<div class="row">
				<div class="col-xs-12 col-sm-12">

					<div class="mywell content">
						<!--<div class="transcript" id="listXML">-->

						<perfect-scrollbar class="scroller" wheel-propagation="true" wheel-speed="50"  on-scroll="onScroll(scrollTop, scrollHeight)" id="listXML">
						 <section ng-repeat="frag in fragments"
								   ng-click="changeTime($event, frag.start)"
								   ng-class="klasa.normal">
							<span ng-hide="true">[[frag.start]]</span><time ng-bind="frag.start | secondsToTime"></time>
							<div class="txtList" ng-bind-html="toTrustedHTML(frag.content)"></div>
						 </section>
						 </perfect-scrollbar>
						<!--</div>-->
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
