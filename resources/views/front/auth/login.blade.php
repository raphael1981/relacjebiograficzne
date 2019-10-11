<div loading-data></div>
<form
        class="form-horizontal form-auth"
        ng-controller="LoginFormController"
        ng-submit="loginSubmit()"
        ng-model="form"
        ng-model="frase"
        ng-model="intent_link"
        ng-model="intent_hash"
        ng-init="intent_link='{{ (!is_null($intent_link))?$intent_link:'' }}';frase='{{ $frase }}';intent_hash='{{ (!is_null($intent_hash))?$intent_hash:'' }}'"
>

    <fieldset>

        <div class="alert-bad-login" ng-class="badlogin" ng-init="badlogin='hidden'">
            Błędne dane do logowania lub Twoja rejestracja nie została jeszcze zakceptowana.
        </div>

        <!-- Text input-->
        <div class="form-group">
            <div class="col-md-12">
                <label for="email-logowania" class="hide-abs-label">Email logowania</label>
                <input
                        id="email-logowania"
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
                <label for="haslo-logowania" class="hide-abs-label">Email logowania</label>
                <input
                        id="haslo-logowania"
                        name="password"
                        type="password"
                        ng-model="form.data.password"
                        placeholder="hasło"
                        ng-model="form.data.password"
                        class="form-control input-md"
                >

            </div>
        </div>

        <div class="form-group group-login-options">
            <div class="col-md-6 checkbox text-center remember-radio">
                <label ng-click="(form.data.remeber==false)?(remcircle='red'):(remcircle='white');(form.data.remeber==false)?(form.data.remeber=true):(form.data.remeber=false)">
                    {{--<input type="checkbox" ng-model="form.data.remeber">--}}
                    <input type="hidden" ng-model="form.data.remeber">

                    <a href="#"
                       ng-click="$event.preventDefault()"
                       ng-class="remcircle"
                       ng-init="(form.data.remeber==false)?(remcircle='white'):(remcircle='red')"
                       class="select-circle"
                    >

                    </a>


                    nie wylogowuj mnie
                </label>
            </div>
            <div class="col-md-6 checkbox text-center">
                {{--<i class="fa fa-circle-o" aria-hidden="true"></i> --}}
                <a href="{{ url('przypomnienie/hasla') }}">Nie pamiętam hasła</a>
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-12">
                <button id="singlebutton" name="singlebutton" class="btn btn-full-length btn-brown-color btn-left-text">Logowanie</button>
            </div>
        </div>

    </fieldset>
</form>