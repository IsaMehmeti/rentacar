@extends('layouts.app')

@section('page_name', __('Shto Veturen'))

@section('content')

<div class="col-lg-6 m-auto">
    <section class="card form-wizard" id="w1">
        <header class="card-header">
            <div class="card-actions">
                <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
            </div>

            <h2 class="card-title">{{__('Shto Veturen')}}</h2>
        </header>
        <div class="card-body card-body-nopadding">
            <div class="wizard-tabs">

            </div>
            <form id="myForm" class="form-horizontal" method="POST" action="{{route('cars.store')}}">
                @csrf
                <div class="tab-content">
                    <div id="w1-account" class="tab-pane p-3 active">
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-sm-right pt-1" for="w1-username">{{__('Modeli')}}</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="model" id="w1-username" value="{{ old('model') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-sm-right pt-1" for="w1-password">{{__('Marshi')}}</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="marsh" id="w1-username" value="{{ old('marsh') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-sm-right pt-1" for="w1-password">{{__('Ngjyra')}}</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="color" id="w1-username" value="{{ old('color') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-sm-right pt-1" for="w1-password">{{__('Viti i prodhimit')}}</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="production_year" id="w1-username" value="{{ old('production_year') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-sm-right pt-1" for="w1-password">{{__('Targeti')}}</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="target" id="w1-username" value="{{ old('target') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-sm-right pt-1" for="w1-password">{{__('Numri i shasise')}}</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="shasi_nr" id="w1-username" value="{{ old('shasi_nr') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-sm-right pt-1" for="w1-password">{{__('Pronari')}}</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="owner" id="w1-username" value="{{ old('owner') }}">
                            </div>
                        </div>
                    <div class="form-group row">
                        <label class="col-sm-4 control-label text-sm-right pt-1" for="w1-password">{{__('Koment')}}</label>
                        <div class="col-sm-8">
                            <textarea type="text" class="form-control" name="comment" id="w1-username" >{{ old('comment') }} </textarea>
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
