<form
        class="form-inline form-search-inline"
        ng-submit="onSubmit()"
        ng-init="initData()"
>
    <div class="form-group group-search">

        <div class="input-group">
            <input
                    type="text"
                    class="form-control"
                    id="ahmsearch"
                    placeholder="Wyszukaj zdjęcia"
                    ng-model="data.search.frase"
                    ng-change="getSuggest()"
            >

        </div>
    </div>
    <button type="submit" class="btn-search"></button>



</form>