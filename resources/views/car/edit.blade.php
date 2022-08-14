@extends('layouts.app')

@section('page_name', __('Ndrysho Veturen'))

@section('content')

<div class="col-lg-8 m-auto">
    <section class="card form-wizard" id="w1">
        <header class="card-header">
            <div class="card-actions">
                <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
            </div>

            <h2 class="card-title">{{__('Ndrysho Veturen')}}</h2>
        </header>
        <div class="card-body card-body-nopadding">
            <div class="wizard-tabs">

            </div>
            <form id="myForm" class="form-horizontal" method="POST" action="{{route('cars.update', $car->id)}}">
                @csrf
                @method('PATCH')
                <div class="tab-content">
                    <div class="tab-content">
                    <div id="w1-account" class="tab-pane p-4 active">
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-sm-right pt-1" for="w1-username">{{__('Modeli')}}</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="model" id="w1-username" value="{{ $car->model }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-sm-right pt-1" for="w1-password">{{__('Marshi')}}</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="marsh" id="w1-username" value="{{ $car->marsh }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-sm-right pt-1" for="w1-password">{{__('Ngjyra')}}</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="color" id="w1-username" value="{{ $car->color }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-sm-right pt-1" for="w1-password">{{__('Viti i prodhimit')}}</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="production_year" id="w1-username" value="{{ $car->production_year }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-sm-right pt-1" for="w1-password">{{__('Targeti')}}</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="target" id="w1-username" value="{{ $car->target }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-sm-right pt-1" for="w1-password">{{__('Numri i shasise')}}</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="shasi_nr" id="w1-username" value="{{ $car->shasi_nr }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-sm-right pt-1" for="w1-password">{{__('Pronari')}}</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="owner" id="w1-username" value="{{ $car->owner }}">
                            </div>
                        </div>
                    </div>

                </div>

        </div>
        <div class="card-footer">
            <ul class="pager">

                <li class="finish float-right">
                    <button id="butoni" type="submit" class="btn btn-default">{{__('Ruaj')}}</button>
                </li>

            </ul>
        </div>
            </form>

    </section>

</div>


@endsection
@section('custom_footer')
    <script>
        $( document ).ready(function() {
            var locale = '{{ config('app.locale') }}';
            var message = "This field is required."
            var email = "Please enter a valid email address.";
            if (locale == 'sq'){
                message = "Kjo fushe nuk mund te jete e zbrazet";
                email = "Ju lutem shtypni nje email valide.";

            }else if(locale == 'sr'){
                message = "Ovo polje je obavezno.";
                email = "Unesite važeću e-adresu.";
            }
            $.extend($.validator.messages, {
                required: message,
                email: email,
            });
        });
    </script>
<script src="{{asset('vendor/jquery-validation/jquery.validate.js')}}"></script>
<script src="{{asset('vendor/pnotify/pnotify.custom.js')}}"></script>
<script src="{{asset('vendor/bootstrap-wizard/jquery.bootstrap.wizard.js')}}"></script>
<script src="{{asset('js/examples/examples.wizard.js')}}"></script>
@endsection
