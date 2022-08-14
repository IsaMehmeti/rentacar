@extends('layouts.app')

@section('page_name', __('Klientet'))

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

                <h2 class="card-title">{{__('Klientet')}}   <a href="{{route('clients.create')}}" class="btn btn-sm btn-dark">{{__('Shto Klientin')}}   <i class="fas fa-plus"></i></a>
    </h2>

            </header>
            <div class="card-body">
                <table class="table table-bordered table-striped mb-0" id="datatable-editable">
                    <thead>
                    <tr>
                        <th>{{__('Emri i plote')}}</th>
                        <th>{{__('Adresa')}}</th>
                        <th>{{__('Nr. i telefonit')}}</th>
                        <th>{{__('Leternjoftimi')}}</th>
                        <th>{{__('Datelindja')}}</th>
                        <th>{{__('Ne regjister')}}</th>
                        <th>{{__('Veprime')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($clients as $client)
                        <td>{{$client->full_name}}</td>
                        <td>{{$client->address}}</td>
                        <td>{{$client->phone}}</td>
                        <td>{{$client->id_card}}</td>
                        <td>{{$client->birth}}</td>
                        @if ($client->registers->count())
                            <td><a href="{{route('client.registers', $client->id)}}">{{$client->registers->count()}}</a></td>
                        @else
                            <td>{{$client->registers->count()}}</td>
                        @endif
                        <td class="actions">
                            <form id="delete-form {{$client->id}}" class="hidden" action="{{route('clients.destroy', $client->id)}}" method="POST">
                                @csrf
                                @method('Delete')
                                <button type="submit" class="hidden" id="{{$client->id}}"></button>
                            </form>
                            <a href="{{url('/registers/create/'.$client->id)}}" data-toggle="tooltip" title=""data-original-title="{{__('Shto ne regjister')}}" class="delete on-default"><i class="fa fa-plus"></i></a>
                            <a href="{{route('clients.edit', $client->id)}}" data-toggle="tooltip" title=""data-original-title="{{__('Ndrysho')}}" class="delete on-default"><i class="fas fa-pencil-alt"></i></a>
                            <a onclick="destroy({{$client->id}})" data-toggle="tooltip" title="" href="#" data-original-title="{{__('Fshije')}}" class="delete on-default"><i class="far fa-trash-alt"></i></a>
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
    <script src="{{asset('js/examples/examples.datatables.clients.js')}}"></script>
@endsection
