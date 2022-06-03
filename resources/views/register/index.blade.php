@extends('layouts.app')

@section('page_name', __('Regjistri'))

@section('custom_header')
    <link rel="stylesheet" href="{{asset('vendor/select2/css/select2.css')}}" />
    <link rel="stylesheet" href="{{asset('vendor/select2-bootstrap-theme/select2-bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{asset('vendor/datatables/media/css/dataTables.bootstrap4.css')}}" />
@endsection

@section('content')
        <section class="card">

            <header class="card-header">
                <div class="card-actions">
                    <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                    <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
                </div>

                <h2 class="card-title">{{__('Regjistri')}}   <a href="{{route('registers.create')}}" class="btn btn-sm btn-dark">{{__('Shto ne Regjister')}}   <i class="fas fa-plus"></i></a>
</h2>

            </header>
            <div class="card-body">
                <table class="table table-bordered table-striped mb-0" id="datatable-editable">
                    <thead>
                    <tr>
                        <th>{{__('Klienti')}}</th>
                        <th>{{__('Vetura')}}</th>
                        <th>{{__('Data')}}</th>
                        <th>{{__('Statusi')}}</th>
                        <th>{{__('Cmimi')}}</th>
                        <th>{{__('Koment')}}</th>
                        <th>{{__('Veprime')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($registers as $register)
                        <td>{{$register->client->full_name}}</td>
                        <td>{{$register->car->model.' '.$register->car->marsh.' - '.$register->car->color}}</td>
                        <td>Nisja: {{$register->start_date}} - Kthimi: {{$register->end_date}}</td>
                        <td>
                        @if(\Carbon\Carbon::parse($register->date) >= \Carbon\Carbon::now())
                            <span class="highlight" style="background-color:#198754">E kryer</span>
                        @else
                            <span class="highlight" style="background-color:#dc3545">Aktive</span>
                        @endif
                        </td>
                        <td>{{$register->price_per_day}} - {{$register->total_price}}</td>
                        <td>{{$register->comment}}</td>
                        <td class="actions">
                            <form id="delete-form {{$register->id}}" class="hidden" action="{{route('registers.destroy', $register->id)}}" method="POST">
                                @csrf
                                @method('Delete')
                                <button type="submit" class="hidden" id="{{$register->id}}"></button>
                            </form>
                            <a data-toggle="tooltip" title="" href="{{route('registers.download', $register->id)}}" data-original-title="{{__('Download')}}"  class="delete on-default"><i class="fas fa-download"></i>
                            <a href="{{route('registers.edit', $register->id)}}" data-toggle="tooltip" title=""data-original-title="{{__('Ndrysho')}}" class="delete on-default"><i class="fas fa-pencil-alt"></i></a>
                            <a onclick="destroy({{$register->id}})" data-toggle="tooltip" title="" href="#" data-original-title="{{__('Fshije')}}" class="delete on-default"><i class="far fa-trash-alt"></i></a>
                        </td>
                    </tr>
                    @empty
                        <tr class="odd"><td valign="top" colspan="4" class="dataTables_empty">{{__('No data available in table')}}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </section>
@endsection

@section('custom_footer')
    <script>
    function destroy(id){
            $("#"+id).click();
        }
    </script>
    <script src="{{asset('vendor/select2/js/select2.js')}}"></script>
    <script src="{{asset('vendor/datatables/media/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('vendor/datatables/media/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('js/examples/examples.datatables.registers.js')}}"></script>
@endsection
