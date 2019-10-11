<div class="container-fluid ahm-container" ng-controller="ResetPasswordController">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-4 col-md-offset-4">

            <h2 class="section-title">
                Reset Hasła
            </h2>

            <form
                    class="form-horizontal form-reset"
                    ng-model="cid"
                    ng-model="token"
                    ng-submit="onSubmit()"
                    ng-init="cid={{ $cid }};token='{{ $token }}'"
            >

                <div class="pass-change" ng-class="passchange" ng-init="passchange='hidden'">
                    Hasło zostało zmienione
                </div>

                <div class="form-group">

                        <input
                                type="password"
                                class="form-control"
                                id="npassword"
                                placeholder="Wpisz nowe hasło"
                                ng-model="npassword"
                        >

                </div>
                <div class="form-group">

                        <input
                                type="password"
                                class="form-control"
                                id="verifynewpassword"
                                placeholder="Wpisz ponownie nowe hasło"
                                ng-model="verifynewpassword"
                        >


                </div>

                <div class="form-group">
                    <div class="alert-for-reset" ng-class="badpass" ng-init="badpass='hidden'">
                        Hasło zbyt słabe
                    </div>
                    <div class="alert-for-reset" ng-class="verifyerror" ng-init="verifyerror='hidden'">
                        Hasła nie zgodne
                    </div>
                    <div class="alert-for-reset" ng-class="verify" ng-init="verify='hidden'">
                        Wpisz hasło ponownie
                    </div>
                </div>


                <div class="form-group button-group">

                <button type="submit" class="btn-reset">Zmień Hasło</button>

                </div>

            </form>
        </div>
    </div>
</div>