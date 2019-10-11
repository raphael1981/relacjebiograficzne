@if(!is_null($breadcrumbs))
    <nav class="breadc">

        <div class="container-fluid ahm-container breadc">
            <div class="row">
                <div class="col-breadc">
                    <ol class="breadcrumb @if(\Illuminate\Support\Facades\Route::currentRouteName()=='home')home-breadc @endif">

                        @foreach($breadcrumbs as $b)

                            @if($b['active'])

                                <li class="active">{{ $b['name'] }}</li>

                            @else

                                <li><a href="{{ url($b['url']) }}">{{ $b['name'] }}</a></li>

                            @endif

                        @endforeach



                    </ol>
                </div>
            </div>
        </div>

    </nav>
@endif

