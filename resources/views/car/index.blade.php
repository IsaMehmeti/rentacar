@extends('layouts.app')

@section('page_name', __('Veturat'))

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

                <h2 class="card-title">{{__('Veturat')}}   <a href="{{route('cars.create')}}" class="btn btn-sm btn-dark">{{__('Shto Veturen')}}   <i class="fas fa-plus"></i></a>
</h2>

            </header>
            <div class="card-body">
                <table class="table table-bordered table-striped mb-0" id="datatable-editable">
                    <thead>
                    <tr>
                        <th>{{__('Modeli')}}</th>
                        <th>{{__('Viti i prodhimit')}}</th>
                        <th>{{__('Skadimi i Regjistrimit')}}</th>
                        <th>{{__('Kontrollimi Teknik i fundit')}}</th>
                        <th>{{__('Targeti')}}</th>
                        <th>{{__('Veprime')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($cars as $car)
                        <td>{{$car->model}} {{$car->marsh}} - {{$car->color}}</td>
                        <td>{{$car->production_year}}</td>
                        <td>
                            @php
                                $registrationDate = \Carbon\Carbon::parse($car->registration);
                                $oneYearAfter = $registrationDate->addYear();
                                $oneWeekBeforeOneYear = $oneYearAfter->copy()->subWeek();
                            @endphp

                            @if($oneYearAfter->isPast())
                                <span class="highlight" style="background-color:#dc3545">{{$oneYearAfter->format('d-m-Y')}}</span>
                            @elseif(\Carbon\Carbon::now()->between($oneWeekBeforeOneYear, $oneYearAfter))
                                <span class="highlight" style="background-color:#f8c867">{{$oneYearAfter->format('d-m-Y')}}</span>
                            @else
                                <span class="highlight" style="background-color:#198754">{{$oneYearAfter->format('d-m-Y')}}</span>
                            @endif
                            </td>
                        <td>{{$car->technical_control}}</td>
                        <td>{{$car->target}}</td>
                        <td class="actions">
                            <form id="delete-form {{$car->id}}" class="hidden" action="{{route('cars.destroy', $car->id)}}" method="POST">
                                @csrf
                                @method('Delete')
                                <button type="submit" class="hidden" id="{{$car->id}}"></button>
                            </form>
                            <a href="{{route('cars.edit', $car->id)}}" data-toggle="tooltip" title=""data-original-title="{{__('Ndrysho')}}" class="delete on-default"><i class="fas fa-pencil-alt"></i></a>
                            <a onclick="destroy({{$car->id}})" data-toggle="tooltip" title="" href="#" data-original-title="{{__('Fshije')}}" class="delete on-default"><i class="far fa-trash-alt"></i></a>
                        </td>
                    </tr>
                    @empty
                        <tr class="odd"><td valign="top" colspan="5" class="dataTables_empty">{{__('No data available in table')}}</td></tr>
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
    <script src="{{asset('js/examples/examples.datatables.cars.js')}}"></script>
@endsection
