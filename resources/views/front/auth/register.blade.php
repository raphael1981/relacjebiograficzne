<form
        class="form-horizontal form-auth"
        ng-controller="RegisterFormController as ctrl"
        ng-submit="registerSubmit()"
        ng-model="intent_link"
        ng-init="intent_link='{{ (!is_null($intent_link))?$intent_link:'' }}'"
>
    <fieldset>

        <div loading-data></div>
        <div register-customer-end></div>

        @foreach($fields as $key=>$field)

                @if($field['type']=='text')

                <!-- Text input-->
                <div class="form-group" ng-class="form.classes.{{ $key  }}">
                    <div class="col-md-12">
                        <label for="{{$field['id']}}" class="hide-abs-label">{{$field['placeholder']}}</label>
                        <input
                                id="{{$field['id']}}"
                                name="{{ $key  }}"
                                type="{{ $field['type'] }}"
                                placeholder="{{ (isset($field['placeholder']))?$field['placeholder']:'' }}"
                                class="form-control input-md"
                                ng-model="form.data.{{ $key  }}"
                                ng-change="check{{ ucfirst($key) }}()"

                        >
                        <span class="" ng-class="form.vmessage.{{ $key  }}" ng-init="form.vmessage.{{ $key  }}='hidden'">
                            [[ form.vmessagetext.{{ $key  }} ]]
                        </span>

                    </div>
                </div>

                @endif


                @if($field['type']=='password')

                        <!-- Text input-->
                <div class="form-group" ng-class="form.classes.{{ $key  }}">
                    <div class="col-md-12">
                        <label for="{{$field['id']}}" class="hide-abs-label">{{$field['placeholder']}}</label>
                        <input
                                id="{{$field['id']}}"
                                name="{{ $key  }}"
                                type="{{ $field['type'] }}"
                                placeholder="{{ (isset($field['placeholder']))?$field['placeholder']:'' }}"
                                class="form-control input-md"
                                ng-model="form.data.{{ $key  }}"
                                ng-change="check{{ ucfirst($key) }}()"

                        >
                                <span class="" ng-class="form.vmessage.{{ $key  }}" ng-init="form.vmessage.{{ $key  }}='hidden'">
                                    [[ form.vmessagetext.{{ $key  }} ]]
                                </span>

                    </div>
                </div>

                @endif


                @if($field['type']=='radio')

                        <!-- Text input-->
                <div class="form-group" ng-class="form.classes.{{ $key  }}">

                    {{--<input type="hidden" ng-model="form.data.{{ $key  }}">--}}

                    <div class="col-md-12">
                        @foreach($field['values'] as $k=>$v)

                                <div class="radio">
                                    <label for="{{ str_slug($v) }}">
                                        <input
                                                id="{{ str_slug($v)  }}"
                                                name="{{ str_slug($v)  }}"
                                                type="{{ $field['type'] }}"
                                                placeholder="{{ (isset($field['placeholder']))?$field['placeholder']:'' }}"
                                                ng-model="form.data.{{ $key  }}"
                                                ng-value="'{{ $v }}'"
                                                style="display: none"

                                        >


                                        <span
                                                {{--href="#"--}}
                                                {{--ng-click="$event.preventDefault();"--}}
                                                ng-if="form.data.{{ $key  }}=='{{ $v }}'"
                                                class="select-circle red"
                                        >
                                        </span>

                                        <span
                                                {{--href="#"--}}
                                                {{--ng-click="$event.preventDefault();"--}}
                                                ng-if="form.data.{{ $key  }}!='{{ $v }}'"
                                                class="select-circle white"
                                        >
                                        </span>

                                        {{ $field['labels'][$k] }}
                                    </label>
                                </div>


                        @endforeach
                    </div>

                </div>


                @if($field['has_oblique'])


                    @foreach($field['input_oblique'] as $okey=>$ofield)


                        @foreach($field['input_oblique'][$okey] as $o=>$f)


                            <div class="form-group" ng-class="form.classes.{{ $f['name'] }}" ng-if="form.data.{{ $key }}=='{{ $okey }}'">

                                <div class="col-md-12">

                                    <input
                                            id="{{ $f['name']  }}"
                                            name="{{ $f['name']  }}"
                                            type="{{ $f['type'] }}"
                                            placeholder="{{ (isset($f['placeholder']))?$f['placeholder']:'' }}"
                                            class="form-control input-md"
                                            ng-model="form.data.{{ $f['name']  }}"
                                            ng-change="check{{ ucfirst($f['name']) }}()"
                                            ng-if="form.data.{{ $key }}=='{{ $okey }}'"

                                    >

                                </div>

                            </div>


                        @endforeach

                    @endforeach


                @endif

            @endif



            @if($field['type']=='select')


                @if($field['suggest'])


                    <div class="form-group" ng-class="form.classes.{{ $key  }}">

                        <div class="col-md-12">

                            <ui-select
                                    ng-model="ctrl.usersAsync.selected"
                                    theme="bootstrap"
                                    ng-disabled="ctrl.disabled"
                                    ng-change="check{{ ucfirst($key) }}()"
                                    title=""
                            >
                                <ui-select-match placeholder="{{ $field['placeholder'] }}">
                                    [[$select.selected.value || $select.selected]]
                                </ui-select-match>

                                <ui-select-choices
                                        repeat="person.value as person in ctrl.usersAsync | propsFilter: {key: $select.search, value: $select.search}"
                                >
                                    <h5 class="el-list-select">
                                        [[person.value]]
                                    </h5>
                                </ui-select-choices>
                            </ui-select>


                        </div>

                    </div>



                @else

                    <div class="form-group" ng-class="form.classes.{{ $key  }}">

                        <div class="col-md-12">

                            <select name="mySelect" id="mySelect" class="form-control"
                                    ng-options="option.name for option in {{ $key  }} track by option.id"
                                    ng-change="check{{ ucfirst($key) }}()"
                                    ng-model="form.data.{{ $key  }}"></select>
                        </div>

                    </div>

                @endif



            @endif


            @if($field['type']=='checkbox')

                <div class="form-group" ng-class="form.classes.{{ $key  }}">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label for="{{ $key  }}">
                                <input
                                        type="{{ $field['type'] }}"
                                        name="{{ $key  }}"
                                        id="{{ $key  }}"
                                        ng-model="form.data.{{ $key  }}"
                                        style="display: none"
                                >
                                <span
                                        {{--href="#"--}}
                                        {{--ng-click="$event.preventDefault();"--}}
                                        ng-if="form.data.{{ $key  }}"
                                        class="select-circle red"
                                ></span>
                                <span
                                        {{--href="#"--}}
                                        {{--ng-click="$event.preventDefault();"--}}
                                        ng-if="!form.data.{{ $key  }}"
                                        class="select-circle white"
                                ></span>

                                {!! $field['label'] !!}
                            </label>
                        </div>
                    </div>
                </div>

            @endif


        @endforeach

        {{--<div class="form-group" ng-class="form.classes.{{ $key  }}">--}}
            {{--<div class="col-md-12">--}}

                {{--<div class="g-recaptcha" data-sitekey="{{ $captcha['site_key'] }}"></div>--}}

                {{--<div class="captach-message" ng-if="captcha_error">--}}
                    {{--[[ captcha_error_message ]]--}}
                {{--</div>--}}

            {{--</div>--}}
        {{--</div>--}}


        {{--<div class="form-group" ng-class="form.classes.woj">--}}
            {{--<label class="col-sm-12">Wojewodztwo</label>--}}
            {{--<div class="col-sm-12">--}}
                {{--<select name="mySelect" id="mySelect" class="form-control"--}}
                        {{--ng-options="option.name for option in woj track by option.id"--}}
                        {{--ng-change="checkIsSelectWoj()"--}}
                        {{--ng-model="form.data.woj"></select>--}}
            {{--</div>--}}
        {{--</div>--}}


        {{--<div class="form-group" ng-class="form.classes.ocupation">--}}
            {{--<label class="col-sm-12">Zawód</label>--}}
            {{--<div class="col-sm-12">--}}
                {{--<select name="mySelect" id="mySelect" class="form-control"--}}
                        {{--ng-options="option.name for option in ocupations track by option.id"--}}
                        {{--ng-change="checkIsSelectOcupation()"--}}
                        {{--ng-model="form.data.ocupation"></select>--}}
            {{--</div>--}}
        {{--</div>--}}



        <!-- Button -->
        <div class="form-group">
            <div class="col-md-12">
                <button
                        id="singlebutton"
                        name="singlebutton"
                        class="btn btn-full-length btn-brown-color btn-left-text"
                >
                    Rejestruj
                </button>
            </div>
        </div>

    </fieldset>
</form>

<div loading-data></div>
