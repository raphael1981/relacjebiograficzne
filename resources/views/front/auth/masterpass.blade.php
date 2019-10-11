<!doctype html>
<html ng-app="app" lang="pl">
<head>
    <meta charset="UTF-8">
    <title>{!! $title !!}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{--<link rel="stylesheet" type="text/css" href="https://cloud.typography.com/6999356/6155372/css/fonts.css" />--}}
    <link rel="stylesheet" type="text/css" href="{{ url('css/font.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
{{--    <link type="text/css" rel="stylesheet/less" href="{{ asset('less/front.less') }}">--}}
    <link type="text/css" rel="stylesheet" href="{{ asset('css/front.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('css/perfect-scrollbar.min.css') }}">
    {{--<script src="//cdnjs.cloudflare.com/ajax/libs/less.js/2.7.1/less.min.js"></script>--}}
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body ng-controller="GlobalController" id="body" class="alphaHide" ng-init="initWcag()" resize style="overflow:hidden">
{{--<perfect-scrollbar class="scroller" id="scrollID" wheel-propagation="true" wheel-speed="50" style="width:100vw;height:100vh">--}}

    @include('front.header')
    <div class="content-cont">
        {!! $content !!}
    </div>

    @include('front.footer')
{{--</perfect-scrollbar>--}}
<script type="text/javascript" src="{{ asset('js/jquery-3.1.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-migrate-1.4.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap-select.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/angular.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/angular-sanitize.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/angular-cookies.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/ui-bootstrap-tpls.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/angular-animate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/select.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/perfect-scrollbar.with-mousewheel.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/angular-perfect-scrollbar.js') }}"></script>
<script type="text/javascript" src="{{ asset('controllers/'.$controller) }}"></script>
</body>
</html>