<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Współtwórca</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link type="text/css" rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link type="text/css" rel="stylesheet/less" href="{{ asset('less/app.less') }}">
    <script src="//cdnjs.cloudflare.com/ajax/libs/less.js/2.7.1/less.min.js"></script>

</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <h2>Witaj {{ Auth::user()->name }} {{ Auth::user()->surname }}</h2>
            <hr>
        </div>
        <div class="col-xs-12 col-sm-12">
            <form action="http://timemarker.dsh.waw.pl" method="post" style="display:block-inline">
                <input type="hidden" name="code" value="{{ Auth::user()->password }}" />
                <button type="submit" class="btn btn-primary btn-lg btn-full-length">
                    Przejdź do "Czasooznaczacza"
                </button>
            </form>
        </div>
    </div>
</div>



<script type="text/javascript" src="{{ asset('js/jquery-3.1.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-ui.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-migrate-1.4.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>

</body>
</html>