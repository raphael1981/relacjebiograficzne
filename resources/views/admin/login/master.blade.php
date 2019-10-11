<!doctype html>
<html ng-app="app">
<head>
    <meta charset="UTF-8">
    <title>{!! $title !!}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <link type="text/css" rel="stylesheet/less" href="{{ asset('less/app.less') }}">
    <script src="//cdnjs.cloudflare.com/ajax/libs/less.js/2.7.1/less.min.js"></script>
</head>
<body ng-controller="GlobalController" id="body" class="alphaHide">

<nav class="top-beam">

</nav>

<section class="login-form">
    <div class="container">
        <div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-3">
            <div loading-data></div>
            <form
                    class="form-horizontal"
                    ng-controller="LoginFormController"
                    ng-submit="loginSubmit()"
                    ng-model="form"
            >
                <fieldset>

                    <!-- Text input-->
                    <div class="form-group">
                        <div class="col-md-12">
                            <input
                                    id="email"
                                    name="email"
                                    type="text"
                                    ng-model="form.data.email"
                                    placeholder="email"
                                    ng-model="form.data.email"
                                    class="form-control input-md"
                            >

                        </div>
                    </div>

                    <!-- Password input-->
                    <div class="form-group">

                        <div class="col-md-12">
                            <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    ng-model="form.data.password"
                                    placeholder="hasło"
                                    ng-model="form.data.password"
                                    class="form-control input-md"
                            >

                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 checkbox text-center">
                            <i class="fa fa-life-ring" aria-hidden="true"></i> <a href="{{ url('password/email') }}">Nie pamiętam hasła</a>
                        </div>
                        <div class="col-md-6 checkbox text-center">
                            <label><input type="checkbox" ng-model="form.data.remeber">Pamiętaj mnie</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <button id="singlebutton" name="singlebutton" class="btn btn-primary btn-full-length">Logowanie</button>
                        </div>
                    </div>

                </fieldset>
            </form>

        </div>
    </div>
</section>


<script type="text/javascript" src="{{ asset('js/jquery-3.1.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-migrate-1.4.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/angular.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/angular-sanitize.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('controllers/'.$controller) }}"></script>
</body>
</html>