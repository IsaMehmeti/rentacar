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
            <form id="myForm" class="form-horizontal" method="POST" action="{{route('registers.update', $register->id)}}">
                @csrf
                @method('PATCH')
                <div class="tab-content">
                    <div id="w1-account" class="tab-pane p-3 active">
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-sm-right pt-1" for="w1-first-name">{{__('Vetura')}}</label>
                            <div class="col-sm-8">
                                <select id="company" name="car_id" class="form-control valid" aria-invalid="false">
                                    @foreach($cars->where('model', '!==', null) as $car)
                                    <option value="{{$car->id}}" {{$car->id == $register->car_id ? 'selected' : ''}}>{{ucfirst($car->model)}} - {{$car->marsh}}</option>
                                    @endforeach
                                </select>
                                <label id="company-error" class="error" for="company"></label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-sm-right pt-1" for="w1-first-name">{{__('Klienti')}}</label>
                            <div class="col-sm-8">
                                <select id="company" name="client_id" class="form-control valid" aria-invalid="false">

                                    @foreach($clients as $client)
                                        <option value="{{$client->id}}" {{$client->id == $register->client_id ? 'selected' : ''}}>{{ucfirst($client->full_name)}} - {{ucfirst($client->address)}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-sm-right pt-1" for="w1-username">{{__('Shoferi')}}</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="driver" id="w1-username" value="{{ $register->driver }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-sm-right pt-1" for="w1-username">{{__('Data e nisjes')}}</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" name="start_date" id="w1-username" value="{{ $register->start_date }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-sm-right pt-1" for="w1-password">{{__('Data e kthimit')}}</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" name="end_date" id="w1-username" value="{{ $register->end_date }}">
                            </div>
                        </div>
                         <div class="form-group row">
                            <label class="col-sm-4 control-label text-sm-right pt-1">Cmimi</label>
                            <div class="col-sm-8">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <input type="text" name="price_per_day" class="form-control disabled" placeholder="dita">
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" name="total_price" class="form-control" placeholder="totali">
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-sm-right pt-1" for="w1-username">{{__('Gjendja e derivatit')}}</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="fuel_status" id="w1-username" value="{{ $register->fuel_status }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-sm-right pt-1" for="w1-password">{{__('Koment')}}</label>
                            <div class="col-sm-8">
                                <textarea type="text" class="form-control" name="comment" id="w1-username">{{ $register->comment }} </textarea>
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
