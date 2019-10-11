<div
        class="container-fluid ahm-container"
        ng-controller="HomeController"
        ng-init="initData()"
>


    <div class="row row-first-news">
        <div class="left-col" id="leftHome">
            <div class="inside">
                <h2 class="section-title">
                    AKTUALNOŚCI
                </h2>
                <div
                        class="image-view"
                        style="
                            background: url([[ first_article.image ]]/700) top center no-repeat;
                            background-size: cover;
                        ">
                    <img src="[[ first_article.image ]]/700" class="hidden" ng-if="first_article.image!=null" alt="[[ first_article.title ]]">
                </div>

                {{--<img src="[[ defaultimage ]]" class="img-responsive" ng-if="first_article.image==null">--}}
            </div>
        </div>
        <div class="right-col" id="rightHome">

            <div class="inside inside-about">



                <div class="content-about">
                    <h2 class="section-title">ZAPRASZAMY</h2>

                    <div id="about-home-content">
                        <div class="circle" id="circleID">
                            <img src="{{ asset('images/circle.gif') }}" class="about-circle" alt="czerwone koło jak tło tekstu wprowadzającego">
                        </div>
                        @hookRender('1-zapraszamy')
                    </div>


                </div>

            </div>

        </div>
    </div>


    <div class="row row-content-home">

        <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3" ng-if="windowWidth<=991">
            @include('front.scenes.home.lastrecords')
        </div>

        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-9">

            <div
                    class="row row-arts"
            >

                <div class="more-width">

                    <!-- First News Content -->


                    <div class="art-content first-art">
                        <img elem-ready src="[[ first_article.image ]]/700" class="img-responsive hidden-sm hidden-lg" alt="[[ first_article.artdata.title ]]" ng-if="first_article.image!=null">
                        <div class="text-art-content">

                            <h3>
                                <a href="[[ first_article.link ]]" ng-if="first_article.artdata.target_type=='site'">
                                    <span class="hidden">przejście do pełnej treści artykułu</span> [[ first_article.artdata.title ]]
                                </a>
                                <a href="[[ first_article.artdata.external_url ]]" ng-if="first_article.artdata.target_type=='external'" target="blank">
                                   <span class="hidden">Przejdź do zewnętrzenj strony o tematyce</span> [[ first_article.artdata.title ]]
                                </a>
                            </h3>
                            <div class="short-line">
                                <span class="line-inside"></span>
                            </div>
                            <span class="art-publish-date">
                                [[ first_article.artdata.published_at | dateformat | date: 'dd.MM.yyyy' ]]
                            </span>
                            <div class="intro-content">
                                <div ng-bind-html="[[ first_article.artdata.intro ]]"></div>
                            </div>
                            <div class="more-link" ng-if="first_article.artdata.content!='' && first_article.artdata.target_type=='site'">
                                <a href="[[ first_article.link ]]">
                                    więcej <span class="hidden">treści artykułu [[ first_article.artdata.title ]]</span>
                                </a>
                            </div>

                        </div>
                    </div>


                    <!-- First News Content -->

                    <div
                            class="col-art fade"
                            ng-if="is_more_then_three &&  windowWidth<991"

                    >
                        <div class="art-content">
                            <img src="[[ first_three_article[0].image ]]/700" class="img-responsive" alt="[[ first_three_article[0].artdata.title ]]" ng-if="first_three_article[0].image!=null">
                            <div class="text-art-content">
                                <h3>
                                    <a href="[[ first_three_article[0].link ]]" ng-if="first_three_article[0].artdata.target_type=='site'">
                                        <span class="hidden">przejście do pełnej treści artykułu</span> [[ first_three_article[0].artdata.title ]]
                                    </a>
                                    <a href="[[ first_three_article[0].artdata.external_url ]]" ng-if="first_three_article[0].artdata.target_type=='external'" target="blank">
                                        <span class="hidden">Przejdź do zewnętrzenj strony o tematyce</span> [[ first_three_article[0].artdata.title ]]
                                    </a>
                                </h3>
                                <div class="short-line">
                                    <span class="line-inside"></span>
                                </div>
                                <span class="art-publish-date">
                                    [[ first_three_article[0].artdata.published_at | dateformat | date: 'dd.MM.yyyy' ]]
                                </span>
                                <div class="intro-content">
                                    <div ng-bind-html="[[ first_three_article[0].artdata.intro ]]"></div>
                                </div>
                                <div class="more-link" ng-if="first_three_article[0].artdata.content!='' && first_three_article[0].artdata.target_type=='site'">
                                    <a href="[[ first_three_article[0].link ]]">
                                        więcej <span class="hidden">treści artykułu [[ first_three_article[0].artdata.title ]]</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div
                            class="col-art fade"
                            ng-if="is_more_then_three"

                    >
                        <div class="art-content">
                            <img src="[[ first_three_article[1].image ]]/700" class="img-responsive" alt="[[ first_three_article[1].artdata.title ]]" ng-if="first_three_article[1].image!=null">
                            <div class="text-art-content">
                                <h3>
                                    <a href="[[ first_three_article[1].link ]]" ng-if="first_three_article[1].artdata.target_type=='site'">
                                        <span class="hidden">przejście do pełnej treści artykułu</span> [[ first_three_article[1].artdata.title ]]
                                    </a>
                                    <a href="[[ first_three_article[1].artdata.external_url ]]" ng-if="first_three_article[1].artdata.target_type=='external'" target="blank">
                                        <span class="hidden">Przejdź do zewnętrzenj strony o tematyce</span> [[ first_three_article[1].artdata.title ]]
                                    </a>
                                </h3>
                                <div class="short-line">
                                    <span class="line-inside"></span>
                                </div>
                                <span class="art-publish-date">
                                    [[ first_three_article[1].artdata.published_at | dateformat | date: 'dd.MM.yyyy' ]]
                                </span>
                                <div class="intro-content">
                                    <div ng-bind-html="[[ first_three_article[1].artdata.intro ]]"></div>
                                </div>
                                <div class="more-link" ng-if="first_three_article[1].artdata.content!='' && first_three_article[1].artdata.target_type=='site'">
                                    <a href="[[ first_three_article[1].link ]]">
                                        więcej <span class="hidden">treści artykułu [[ first_three_article[1].artdata.title ]]</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>




                    <div
                            class="col-art fade"
                            ng-if="is_more_then_three &&  windowWidth<991"

                    >
                        <div class="art-content">
                            <img src="[[ first_three_article[2].image ]]/700" class="img-responsive" alt="[[ first_three_article[2].artdata.title ]]" ng-if="first_three_article[2].image!=null">
                            <div class="text-art-content">
                                <h3>
                                    <a href="[[ first_three_article[2].link ]]" ng-if="first_three_article[2].artdata.target_type=='site'">
                                        <span class="hidden">przejście do pełnej treści artykułu</span> [[ first_three_article[2].artdata.title ]]
                                    </a>
                                    <a href="[[ first_three_article[2].artdata.external_url ]]" ng-if="first_three_article[2].artdata.target_type=='external'" target="blank">
                                        <span class="hidden">Przejdź do zewnętrzenj strony o tematyce</span> [[ first_three_article[2].artdata.title ]]
                                    </a>
                                </h3>
                                <div class="short-line">
                                    <span class="line-inside"></span>
                                </div>
                                <span class="art-publish-date">
                                    [[ first_three_article[2].artdata.published_at | dateformat | date: 'dd.MM.yyyy' ]]
                                </span>
                                <div class="intro-content">
                                    <div ng-bind-html="[[ first_three_article[2].artdata.intro ]]"></div>
                                </div>
                                <div class="more-link" ng-if="first_three_article[2].artdata.content!='' && first_three_article[2].artdata.target_type=='site'">
                                    <a href="[[ first_three_article[2].link ]]">
                                        więcej <span class="hidden">treści artykułu [[ first_three_article[2].artdata.title ]]</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div
                            ng-repeat="art in articles"
                            class="col-art fade"
                            ng-if="(($index % 2) == 0) && windowWidth>991"

                    >
                        <div class="art-content">
                            <img src="[[ art.image ]]/700" class="img-responsive" alt="[[ art.artdata.title ]]" ng-if="art.image!=null">
                            <div class="text-art-content">

                                <h3>
                                    <a href="[[ art.link ]]" ng-if="art.artdata.target_type=='site'">
                                        <span class="hidden">przejście do pełnej treści artykułu</span> [[ art.artdata.title ]]
                                    </a>
                                    <a href="[[ art.artdata.external_url ]]" ng-if="art.artdata.target_type=='external'" target="blank">
                                        <span class="hidden">Przejdź do zewnętrzenj strony o tematyce</span> [[ art.artdata.title ]]
                                    </a>
                                </h3>
                                <div class="short-line">
                                    <span class="line-inside"></span>
                                </div>
                                <span class="art-publish-date">
                                    [[ art.artdata.published_at | dateformat | date: 'dd.MM.yyyy']]
                                </span>
                                <div class="intro-content">
                                    <div ng-bind-html="[[ art.artdata.intro ]]"></div>
                                </div>
                                <div class="more-link" ng-if="art.artdata.content!='' && art.artdata.target_type=='site'">
                                    <a href="[[ art.link ]]">
                                        więcej <span class="hidden">treści artykułu [[ art.artdata.title ]]</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div
                            ng-repeat="art in articles"
                            class="col-art fade"
                            ng-if="windowWidth<991"

                    >
                        <div class="art-content">
                            <img src="[[ art.image ]]/700" class="img-responsive" alt="[[ art.artdata.title ]]" ng-if="art.image!=null">
                            <div class="text-art-content">
                                <h3>
                                    <a href="[[ art.link ]]" ng-if="art.artdata.target_type=='site'">
                                        <span class="hidden">przejście do pełnej treści artykułu</span> [[ art.artdata.title ]]
                                    </a>
                                    <a href="[[ art.artdata.external_url ]]" ng-if="art.artdata.target_type=='external'" target="blank">
                                        <span class="hidden">Przejdź do zewnętrzenj strony o tematyce</span> [[ art.artdata.title ]]
                                    </a>
                                </h3>
                                <div class="short-line">
                                    <span class="line-inside"></span>
                                </div>
                                <span class="art-publish-date">
                                    [[ art.artdata.published_at | dateformat | date: 'dd.MM.yyyy']]
                                </span>
                                <div class="intro-content">
                                    <div ng-bind-html="[[ art.artdata.intro ]]"></div>
                                </div>
                                <div class="more-link" ng-if="art.artdata.content!='' && art.artdata.target_type=='site'">
                                    <a href="[[ art.link ]]">
                                        więcej <span class="hidden">treści artykułu [[ art.artdata.title ]]</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{------------------------------------------------------------------------------------------------------------------}}

                <div class="less-width" ng-if="windowWidth>991">

                    <div
                            class="col-art fade"
                            ng-if="is_more_then_three"

                    >
                        <div class="art-content">
                            <img src="[[ first_three_article[0].image ]]/700" class="img-responsive" alt="[[ first_three_article[0].artdata.title ]]" ng-if="first_three_article[0].image!=null">
                            <div class="text-art-content">
                                <h3>
                                    <a href="[[ first_three_article[0].link ]]" ng-if="first_three_article[0].artdata.target_type=='site'">
                                        <span class="hidden">przejście do pełnej treści artykułu</span> [[ first_three_article[0].artdata.title ]]
                                    </a>
                                    <a href="[[ first_three_article[0].artdata.external_url ]]" ng-if="first_three_article[0].artdata.target_type=='external'" target="blank">
                                        <span class="hidden">Przejdź do zewnętrzenj strony o tematyce</span> [[ first_three_article[0].artdata.title ]]
                                    </a>
                                </h3>
                                <div class="short-line">
                                    <span class="line-inside"></span>
                                </div>
                                <span class="art-publish-date">
                                    [[ first_three_article[0].artdata.published_at | dateformat | date: 'dd.MM.yyyy' ]]
                                </span>
                                <div class="intro-content">
                                    <div ng-bind-html="[[ first_three_article[0].artdata.intro ]]"></div>
                                </div>
                                <div class="more-link" ng-if="first_three_article[0].artdata.content!='' && first_three_article[0].artdata.target_type=='site'">
                                    <a href="[[ first_three_article[0].link ]]">
                                        więcej <span class="hidden">treści artykułu [[ first_three_article[0].artdata.title ]]</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                            class="col-art fade"
                            ng-if="is_more_then_three"

                    >
                        <div class="art-content">
                            <img src="[[ first_three_article[2].image ]]/700" class="img-responsive" alt="[[ first_three_article[2].artdata.title ]]" ng-if="first_three_article[2].image!=null">
                            <div class="text-art-content">
                                <h3>

                                    <a href="[[ first_three_article[2].link ]]" ng-if="first_three_article[2].artdata.target_type=='site'">
                                        <span class="hidden">przejście do pełnej treści artykułu</span> [[ first_three_article[2].artdata.title ]]
                                    </a>
                                    <a href="[[ first_three_article[2].artdata.external_url ]]" ng-if="first_three_article[2].artdata.target_type=='external'" target="blank">
                                        <span class="hidden">Przejdź do zewnętrzenj strony o tematyce</span> [[ first_three_article[2].artdata.title ]]
                                    </a>
                                </h3>
                                <div class="short-line">
                                    <span class="line-inside"></span>
                                </div>
                                <span class="art-publish-date">
                                    [[ first_three_article[2].artdata.published_at | dateformat | date: 'dd.MM.yyyy' ]]
                                </span>
                                <div class="intro-content">
                                    <div ng-bind-html="[[ first_three_article[2].artdata.intro ]]"></div>
                                </div>
                                <div class="more-link" ng-if="first_three_article[2].artdata.content!='' && first_three_article[2].artdata.target_type=='site'">
                                    <a href="[[ first_three_article[2].link ]]">
                                        więcej <span class="hidden">treści artykułu [[ first_three_article[2].artdata.title ]]</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                            ng-repeat="art in articles"
                            class="col-art fade"
                            ng-if="(($index % 2) != 0) && windowWidth>991"
                        >
                        <div class="art-content">
                            <img src="[[ art.image ]]/700" class="img-responsive" alt="[[ art.artdata.title ]]" ng-if="art.image!=null">
                            <div class="text-art-content">
                                <h3>
                                    <a href="[[ art.link ]]" ng-if="art.artdata.target_type=='site'">
                                        <span class="hidden">przejście do pełnej treści artykułu</span> [[ art.artdata.title ]]
                                    </a>
                                    <a href="[[ art.artdata.external_url ]]" ng-if="art.artdata.target_type=='external'" target="blank">
                                        <span class="hidden">Przejdź do zewnętrzenj strony o tematyce</span> [[ art.artdata.title ]]
                                    </a>
                                </h3>
                                <div class="short-line">
                                    <span class="line-inside"></span>
                                </div>
                                <span class="art-publish-date">
                                    [[ art.artdata.published_at | dateformat | date: 'dd.MM.yyyy' ]]
                                </span>
                                <div class="intro-content">
                                    <div ng-bind-html="[[ art.artdata.intro ]]"></div>
                                </div>
                                <div class="more-link" ng-if="art.artdata.content!='' && art.artdata.target_type=='site'">
                                    <a href="[[ art.link ]]">
                                        więcej <span class="hidden">treści artykułu [[ art.artdata.title ]]</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
        <div class="col-xs-12 col-sm-12 col-md-5 col-lg-3" ng-if="windowWidth>991">
            @include('front.scenes.home.lastrecords')
        </div>
    </div>

</div>
