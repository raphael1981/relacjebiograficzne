<!doctype html>
<html ng-app="app" lang="pl">
<head>
    <meta charset="UTF-8">
    <title>{!! $title !!}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{--<link rel="stylesheet" type="text/css" href="https://cloud.typography.com/6999356/6155372/css/fonts.css" />--}}
    <link rel="stylesheet" type="text/css" href="{{ url('css/font.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">	
	<link type="text/css" rel="stylesheet" href="{{ asset('css/recordstyle.css') }}">
	<link type="text/css" rel="stylesheet" href="{{ asset('css/ngDialog.css') }}">
	<link type="text/css" rel="stylesheet" href="{{ asset('css/ngDialog-theme-default.css') }}">
	<link type="text/css" rel="stylesheet" href="{{ asset('css/ngDialog-theme-small.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('css/photoswipe.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('default-skin/default-skin.css') }}">
{{--    <link type="text/css" rel="stylesheet/less" href="{{ asset('less/front.less') }}">--}}
    <link type="text/css" rel="stylesheet" href="{{ asset('css/front.css') }}">
    {{--<script src="//cdnjs.cloudflare.com/ajax/libs/less.js/2.7.1/less.min.js"></script>--}}
	<script type="text/javascript" src="{{ asset('js/helpers.js') }}"></script>
	<link rel="shortcut icon" href="/public/images/favicon.png" />
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-63055772-3"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-63055772-3');
    </script>

</head>
<body ng-controller="GlobalController" id="body" class="alphaHide" ng-init="initWcag()">
{{--<perfect-scrollbar class="scroller" wheel-propagation="true" wheel-speed="50">--}}

    @include('front.header')

    @include('front.breadcrumbs')

    <div class="content-cont">
    {!! $content !!}
    </div>

    @include('front.footer')

{{--</perfect-scrollbar>--}}
<script type="text/javascript" src="{{ asset('js/jquery-3.1.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-migrate-1.4.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/angular.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/angular-sanitize.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/angular-route.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/angular-cookies.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/ngDialog.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/photoswipe.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/photoswipe-ui-default.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/angular-photoswipe.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/perfect-scrollbar.with-mousewheel.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/angular-perfect-scrollbar.js') }}"></script>
<script type="text/javascript" src="{{ asset('controllers/'.$controller) }}"></script>

</body>
</html>