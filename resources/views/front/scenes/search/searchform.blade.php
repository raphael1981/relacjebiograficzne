<form
        class="form-inline form-search-inline"
        ng-submit="onSubmit()"
        ng-init="initOnLoad()"
>
    <div class="form-group group-search">

        <div class="input-group">
            <input
                    type="text"
                    class="form-control"
                    id="ahmsearch"
                    placeholder="[[ searchplaceholder ]]"
                    ng-model="data.search.frase"
                    ng-change="getSuggest()"
            >

        </div>
    </div>
    <button type="submit" class="btn-search"></button>


    <img
            src="{{ asset('images/checkbox-select.png') }}"
            class="img-responsive select-image"
            ng-if="!is_perfect"
            ng-click="changeSearchMode('perfect')"
    >
    <img
            src="{{ asset('images/checkbox-deselect.png') }}"
            class="img-responsive select-image"
            ng-if="is_perfect"
            ng-click="changeSearchMode('regex')"
    >



</form>