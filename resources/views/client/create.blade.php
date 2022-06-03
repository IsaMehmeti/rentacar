@extends('layouts.app')

@section('page_name', __('Shto Klientin'))

@section('content')

<div class="col-lg-6 m-auto">
    <section class="card form-wizard" id="w1">
        <header class="card-header">
            <div class="card-actions">
                <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
            </div>

            <h2 class="card-title">{{__('Shto Klientin')}}</h2>
        </header>
        <div class="card-body card-body-nopadding">
            <div class="wizard-tabs">

            </div>
            <form id="myForm" class="form-horizontal" method="POST" action="{{route('clients.store')}}" enctype='multipart/form-data'>
                @csrf
                <div class="tab-content">
                    <div id="w1-account" class="tab-pane p-3 active">
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-sm-right pt-1" for="w1-username">{{__('Emri i plote')}}</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="full_name" id="w1-username" value="{{ old('full_name') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-sm-right pt-1" for="w1-password">{{__('Adresa')}}</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="address" id="w1-username" value="{{ old('address') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-sm-right pt-1" for="w1-password">{{__('Numri i telefonit')}}</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="phone" id="w1-username" value="{{ old('phone') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-sm-right pt-1" for="w1-password">{{__('Numri i Leternjoftimit')}}</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="id_card" id="w1-username" value="{{ old('id_card') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-sm-right pt-1" for="w1-password">{{__('Numri i Pasaportes')}}</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="passaport_nr" id="w1-username" value="{{ old('passaport_nr') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 control-label text-sm-right pt-1" for="w1-password">{{__('Datelindja')}}</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control" name="birth" id="w1-username" value="{{ old('birth') }}">
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
