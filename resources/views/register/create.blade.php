@extends('layouts.app')

@section('page_name', __('Shto ne Regjister'))

@section('content')

<div class="col-lg-8 m-auto">
    <section class="card form-wizard" id="w1">
        <header class="card-header">
            <div class="card-actions">
                <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
            </div>
            <h2 class="card-title">{{__('Shto ne regjister')}}</h2>
        </header>
        <div class="card-body card-body-nopadding">
            <div class="wizard-tabs">

            </div>
            <form id="myForm" class="form-horizontal" method="POST" action="{{route('registers.store')}}">
                @csrf
                <div class="tab-content">
                    <div id="w1-account" class="tab-pane p-3 active">
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-sm-right pt-1" for="w1-first-name">{{__('Vetura')}}</label>
                            <div class="col-sm-8">
                                <select id="company" name="car_id" class="form-control valid" aria-invalid="false">
                                    <option value="" disabled selected>{{__('Zgjedh Veturen')}}</option>
                                    @foreach($cars->where('model', '!==', null) as $car)
                                    <option value="{{$car->id}}">{{ucfirst($car->model)}} - {{$car->marsh}}</option>
                                    @endforeach
                                </select>
                                <label id="company-error" class="error" for="company"></label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-sm-right pt-1" for="w1-first-name">{{__('Klienti')}}</label>
                            <div class="col-sm-8">
                                <select id="company" name="client_id" class="form-control valid" aria-invalid="false">
                                    @if(!$client_id)
                                        <option value="" disabled selected>{{__('Zgjedh Klientin')}}</option>
                                    @endif
                                    @foreach($clients as $client)
                                        <option value="{{$client->id}}" {{$client_id == $client->id ? 'selected' : ''}}>{{ucfirst($client->full_name)}} - {{ucfirst($client->address)}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-sm-right pt-1" for="w1-username">{{__('Shoferi')}}</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="driver" id="w1-username" value="{{ old('driver') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-sm-right pt-1" for="w1-username">{{__('Data e nisjes')}}</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" name="start_date" id="w1-username" value="{{ old('start_date') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-sm-right pt-1" for="w1-password">{{__('Data e kthimit')}}</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" name="end_date" id="w1-username" value="{{ old('end_date') }}">
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
                                <input type="text" class="form-control" name="fuel_status" id="w1-username" value="{{ old('fuel_status') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-sm-right pt-1" for="w1-password">{{__('Koment')}}</label>
                            <div class="col-sm-8">
                                <textarea type="text" class="form-control" name="comment" id="w1-username" >{{ old('comment') }} </textarea>
                            </div>
                        </div>
                    </div>

                </div>

        <div class="card-footer">
            <ul class="pager">

                <li class="finish float-right">
                    <button id="butoni" type="submit" class="btn btn-default">{{__('Perfundo')}}</button>
                </li>

            </ul>
        </div>
            </form>
        </div>

    </section>

</div>


@endsection
@section('custom_footer')
<script src="{{asset('vendor/bootstrap-wizard/jquery.bootstrap.wizard.js')}}"></script>
<script src="{{asset('js/examples/examples.wizard.js')}}"></script>
@endsection
