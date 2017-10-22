@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Horaires</div>
                <div class="panel-body">
                    {{--<rhpharma-schedule :rows="{{ $schedules }}"></rhpharma-schedule>--}}

                    <a href="{{ route('schedules.create') }}" class="btn btn-default pull-right">Ajouter un horaire</a>

                    <table class="table table-hovered">
                        <thead>
                        <tr>
                            <th>Description</th>
                            <th>Début</th>
                            <th>Fin</th>
                            <th>Limite</th>
                            <th>Status</th>
                            <th width="20%">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($schedules as $schedule)
                            <tr>
                                <td>{{ $schedule->name }}</td>
                                <td>{{ $schedule->start_date_string }}</td>
                                <td>{{ $schedule->end_date_string }}</td>
                                <td>{{ $schedule->constraint_limit_date_string }}</td>
                                <td>
                                    @if($constraints_in_schedule[$schedule->id] > 0)
                                        <i class="fa fa-exclamation-triangle text-warning fa-2x"
                                           data-toggle="tooltip" title="Il reste {{ $constraints_in_schedule[$schedule->id] }} contrainte(s) à valider"></i>
                                    @else
                                        <i class="fa fa-check-circle-o text-success fa-2x"></i>
                                    @endif
                                    <i class="{{ status($schedule->status_holidays) }} fa-2x"></i>
                                    <i class="{{ status($schedule->status_weekends) }} fa-2x"></i>
                                    <i class="{{ status($schedule->status_friday_nights) }} fa-2x"></i>
                                    <i class="{{ status($schedule->status_clinical_departments) }} fa-2x"></i>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                    <a href="{{ route('schedules.edit', $schedule->id) }}" class="btn btn-warning">
                                        <i class="fa fa-pencil"> Modifier</i>
                                    </a>
                                    <a href="{{ route('schedules.show', $schedule->id) }}" class="btn btn-success">
                                        <i class="fa fa-chevron-circle-right"> Générer</i>
                                    </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="text-center">
                        {{ $schedules->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection