<!doctype html>
<html ng-app="app">
<head>
    <meta charset="UTF-8">
    <title>{!! $title !!}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <link type="text/css" rel="stylesheet/less" href="{{ asset('less/front.less') }}">
    <script src="//cdnjs.cloudflare.com/ajax/libs/less.js/2.7.1/less.min.js"></script>
</head>
<body style="width: 100vw;height: 100vh">

<nav class="front-beam">

</nav>


<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-3 col-info-verify">

            @if($was_verify)

                <div class="alert-ahm" role="alert">
                    <h3><i class="fa fa-info" aria-hidden="true"></i></h3>
                    Link weryfikacyjny nie aktywny.
                </div>

            @else
            <div class="alert-ahm" role="alert">
                <h3><i class="fa fa-info" aria-hidden="true"></i></h3>
                Dziękujemy. W ciągu dwóch dni roboczych otrzymasz e-mail potwierdzający rejestrację konta.

            </div>
            @endif


        </div>
    </div>
</div>


<script type="text/javascript" src="{{ asset('js/jquery-3.1.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-migrate-1.4.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/angular.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/angular-sanitize.min.js') }}"></script>
@if(!is_null($controller))
<script type="text/javascript" src="{{ asset('controllers/'.$controller) }}"></script>
@else
<script type="text/javascript">
    var app = angular.module('app',['ngSanitize'], function($interpolateProvider) {
        $interpolateProvider.startSymbol('[[');
        $interpolateProvider.endSymbol(']]');
    });
</script>
@endif
</body>
</html>