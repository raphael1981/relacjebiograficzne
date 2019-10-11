<div class="container-fluid ahm-container" ng-controller="EmailPasswordController">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-3">

            <h2 class="section-title">
                Przypomninie hasła
            </h2>


            <form
                    class="form-inline form-remember-inline"
                    ng-submit="onSubmit()"
            >
                <div class="email-was-send" ng-class="emailwassend" ng-init="emailwassend='hidden'">
                    Na adres email został wysłany email z linkiem do resetu hasła
                </div>

                <div class="form-group group-search" ng-class="emailclass">

                    <div class="input-group">
                        <input
                                type="text"
                                class="form-control"
                                id="emailforremember"
                                placeholder="Podaj adres email"
                                ng-model="emailforremember"
                        >

                        <div class="alert-ahm" ng-class="alertclass" ng-init="alertclass='hidden'">
                            Adres email jest nie prawidłowy
                        </div>

                    </div>
                </div>
                <button type="submit" class="btn-search"></button>

            </form>
        </div>
    </div>
</div>