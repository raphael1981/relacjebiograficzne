<!doctype html>
<html ng-app="app">
<head>
    <meta charset="UTF-8">
    <title>{!! $title !!}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="text/css" rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
	<link type="text/css" rel="stylesheet" href="{{ asset('css/ngDialog.css') }}">    
	<link type="text/css" rel="stylesheet" href="{{ asset('css/ngDialog-theme-dialog.css') }}">
    <link type="text/css" rel="stylesheet/less" href="{{ asset('less/app.less') }}">	
    	<link type="text/css" rel="stylesheet" href="{{ asset('css/myxeditable.css') }}">
		<link type="text/css" rel="stylesheet" href="{{ asset('css/angular-side-by-side-select.min.css') }}">
		<link type="text/css" rel="stylesheet" href="{{ asset('css/admingallery.css') }}">		
		<script src="//cdnjs.cloudflare.com/ajax/libs/less.js/2.7.1/less.min.js"></script>
	
	
<script type="text/javascript" src="{{ asset('js/jquery-3.1.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-ui.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-migrate-1.4.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/angular.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/ui-bootstrap-tpls-2.3.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/angular-sanitize.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/angular-route.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/ng-file-upload.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/ng-file-upload-shim.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/angular-file-upload.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/angular-drag-and-drop-lists.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/ngDialog.js') }}"></script>
<script type="text/javascript" src="{{ asset('tinymce/js/tinymce.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/xeditable.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/angular-side-by-side-select.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('controllers/'.$controller) }}"></script>
<script type="text/javascript" src="{{ asset('js/sidebyside.directive.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/thumbnails-directive.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/draggable.js') }}"></script>


    <script src="{{ asset('tinymce/js/tinymce/tinymce.min.js') }}"></script>
</head>
<body ng-controller="GlobalController" id="body" class="alphaHide">
<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>                <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/administrator') }}">
                AHM - superadmin
            </a>
        </div>
        <div class="collapse navbar-collapse" id="app-navbar-collapse">                <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                <li role="presentation"><a href="{{ url('administrator/interviewees') }}#/">Świadkowie</a></li>
                <li role="presentation" class="dropdown">

                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" ng-click="$event.preventDefault()">
                        Transkrypcje <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu">
                        <li>
                            <form action="http://timemarker.dsh.waw.pl" method="post" style="display:block-inline">
                                <input type="hidden" name="code" value="{{ Auth::user()->password }}" />
                                <input type="submit" value="Czasooznaczacz" style="border:0; background:0;" />
                            </form>
                        </li>
                        <li>
                            <a href="{{ url('administrator/records') }}#/">Transkrypcje</a>
                        </li>
                        <li>
                            <a href="{{ url('administrator/tags') }}#/">Słowa kluczowe</a>
                        </li>
                        <li>
                            <a href="{{ url('administrator/threads') }}#/">Tematy</a>
                        </li>
                        <li>
                            <a href="{{ url('administrator/intervals') }}#/">Interwały czasowe</a>
                        </li>
                        <li>
                            <a href="{{ url('administrator/places') }}#/">Miejsca</a>
                        </li>
                    </ul>


                </li>
                <li role="presentation"><a href="{{ url('administrator/redactors') }}#/">Redaktorzy</a></li>
                <li role="presentation"><a href="{{ url('administrator/employees') }}#/">Współtwórcy</a></li>
                <li role="presentation"><a href="{{ url('administrator/customers') }}#/">Użytkownicy</a></li>
                <li role="presentation"><a href="{{ url('administrator/catergories') }}#/">Kategorie</a></li>
                <li role="presentation"><a href="{{ url('administrator/articles') }}#/">Artykuły</a></li>
                <li role="presentation"><a href="{{ url('administrator/galleries') }}#/">Galerie</a></li>
                <li role="presentation"><a href="{{ url('administrator/elasticsearch') }}#/">Indeksowanie</a></li>
                {{--<li role="presentation"><a href="#">Zdjęcia</a></li>--}}
            </ul>
            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li><a href="{{ url('/login') }}">Login</a></li>
                    <li><a href="{{ url('/register') }}">Register</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="{{ url('/logout') }}"
                                   onclick="event.preventDefault();
                                   document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
<section class="content">
    <div class="container-fluid">
        {!! $content !!}
    </div>
</section>
<!--
<script type="text/javascript" src="{{ asset('js/jquery-3.1.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-ui.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-migrate-1.4.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/angular.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/ui-bootstrap-tpls-2.3.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/angular-sanitize.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/angular-route.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/angular-file-upload.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/angular-drag-and-drop-lists.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/ngDialog.js') }}"></script>
<script type="text/javascript" src="{{ asset('tinymce/js/tinymce.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/xeditable.js') }}"></script>
<script type="text/javascript" src="{{ asset('controllers/'.$controller) }}"></script>
<script type="text/javascript" src="{{ asset('js/thumbnails-directive.js') }}"></script> 
<script type="text/javascript" src="{{ asset('js/draggable.js') }}"></script>
-->
</body>
</html>