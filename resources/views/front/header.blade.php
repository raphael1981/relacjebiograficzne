<header class="top">

    <div class="container-fluid ahm-container mainfront">
        <div class="row">
            <div class="left-col logo col">
                <div class="dot"></div>
                <h1 style="position:absolute; top: 0px; left:40px; z-index:100"><a href="/">archiwum historii mówionej</a></h1>

{{--                <a href="{{ url('/') }}" title="przejście do strony głównej">
                    <img src="{{ asset('images/logo_ahm.png') }}" class="img-responsive" alt="Logo Archiwum Historii Mówionej">
                </a>--}}

            </div>
            <div class="center-col col">

                <h1 class="absolute-title">Relacje&nbsp;<br>Biograficzne</h1>

                <div class="menu-box" ng-class="mobilecss" id="menu-top" ng-init="mobilecss='hidden-xs hidden-sm hidden-md'">
                    <ul class="menu">
                        <li class="{{ (Request::is('wyszukiwanie'))?'active':'' }}">
                            <a href="{{ url('wyszukiwanie') }}#/" title="przejście do wyszukiwania w transkrypcjach nagrań">
                                <span>Wyszukaj</span>
                            </a>
                        </li>
                        <li class="bull">
                            <span>&bull;</span>
                        </li>
                        <li class="{{ (Request::is('swiadkowie'))?'active':'' }}">
                            <a href="{{ url('swiadkowie') }}#/" title="przejście do spisu świadków">
                                <span>Świadkowie</span>
                            </a>
                        </li>
                        <li class="bull">
                            <span>&bull;</span>
                        </li>
                        <li class="{{ (Request::is('galerie'))?'active':'' }}">
                            <a href="{{ url('galerie') }}" title="przejście do gallerii fotografii">
                                <span>Galeria</span>
                            </a>
                        </li>
                        <li class="bull">
                            <span>&bull;</span>
                        </li>
                        <li class="{{ (Request::is('tematy'))?'active':'' }}">
                            <a href="{{ url('tematy') }}" alt="przejście do pogrupowanych tematami nagrań">
                                <span>Tematy</span>
                            </a>
                        </li>
                        <li class="bull">
                            <span>&bull;</span>
                        </li>
                        <li class="{{ (Request::is('projekt'))?'active':'' }}">
                            <a href="{{ url('projekt') }}" alt="przejście do informacji o projekcie relacjebiograficzne.pl">
                                <span>O Projekcie</span>
                            </a>
                        </li>

                        <li class="hidden-lg">

                            <div class="log-key-btn" ng-if="!auth">
                                <a href="{{ url('autoryzacja') }}" class="przedź do logowania lub rejestracji aby mieć dostęp do zasobów">
                                    Zaloguj
                                </a>
                            </div>
                            <div class="log-key-btn" ng-if="auth">
                                <form action="{{ url('customer/logout') }}" method="post" id="logOut" target="_self">
                                    {{ csrf_field() }}
                                    <a href="{{ url('/customer/logout') }}" onclick="event.preventDefault();document.getElementById('logOut').submit()" title="wyloguj się z serwisu">
                                        Wyloguj <span class="hidden">i przejdź do strony głównej</span>
                                    </a>
                                </form>

                            </div>

                        </li>

                    </ul>

                    <div class="close-x visible-xs visible-sm visible-md hidden-lg">
                        <img src="/images/close_menu_icon.svg" class="img-responsive" alt="obrazek zamknięcia menu mobilnego">
                        <a
                                href="#menu-top"
                                class="close-click"
                                title="zamknij menu w wersji mobilnej"
                                ng-click="$event.preventDefault();mobilecss='hidden-xs hidden-sm hidden-md'"
                        >
                            <span class="hidden">Zamknij menu w wersji mobilnej</span>
                        </a>
                    </div>


                </div>

            </div>

            <div class="right-col col col-right-mobile visible-xs visible-sm visible-md hidden-lg">

                <div class="sandwitch">
                    <div class="line"></div>
                    <div class="line"></div>
                    <div class="line"></div>
                    <a
                            href="#menu-top"
                            title="otwórz menu w wersji mobilnej"
                            ng-click="
                            $event.preventDefault();
                            (mobilecss=='')?(mobilecss='hidden-xs hidden-sm hidden-md'):(mobilecss='showabsolute')
                            "
                    >
                    <span class="hidden">Otwórz menu w wersji mobilnej</span>
                    </a>
                </div>

            </div>

            <div class="right-col col hidden-xs hidden-sm hidden-md">

                <div class="log-key-btn" ng-if="!auth">
                    @if(request()->route()->getName()!='authroute')
                        <a href="{{ url('autoryzacja') }}?intent={{ request()->path() }}[[ hash_emit ]]" class="login-title">
                            Zaloguj
                        </a>
                    @else
                        <a href="{{ url('autoryzacja') }}" class="login-title">
                            Zaloguj
                        </a>
                    @endif

                </div>
                {{--@if(request()->route()->getName()!='authroute')--}}
                    {{--@include('front.logincloud')--}}
                {{--@endif--}}

                <div class="log-key-btn" ng-if="auth">

                    <form action="{{ url('customer/logout') }}" method="post" id="logOutMobile" target="_self">
                        {{ csrf_field() }}
                        <a href="{{ url('/customer/logout') }}" onclick="event.preventDefault();document.getElementById('logOutMobile').submit()" title="wyloguj się z serwisu" class="login-title">
                            Wyloguj <span class="hidden">i przejdź do strony głównej</span>
                        </a>
                    </form>

                </div>

                <div class="wcag-params">
                    <span class="info-contrast">
                        Zmień kontrast strony
                    </span>
                    <span ng-click="changeWcagStyle('cminus')" class="btn-wcag less-contrast">
                       <span class="hidden">Kliknij zaby zwiększyć kontrast</span> A
                    </span>
                    <span ng-click="changeWcagStyle('cplus')" class="btn-wcag more-contrast">
                        <span class="hidden">Kliknij zaby zmniejszyć kontrast</span> A
                    </span>
                    {{--<span ng-click="changeWcagStyle('fminus')">--}}
                        {{--less f--}}
                    {{--</span>--}}
                    {{--<span ng-click="changeWcagStyle('fplus')">--}}
                        {{--more f--}}
                    {{--</span>--}}
                </div>

            </div>
        </div>
    </div>

</header>

