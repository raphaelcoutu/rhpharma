@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Horaires</div>
                <div class="panel-body">

                    <a href="{{ route('schedules.create') }}" class="btn btn-default pull-right">Ajouter un horaire</a>

                    <table class="table table-hovered">
                        <thead>
                        <tr>
                            <th>Description</th>
                            <th>Début</th>
                            <th>Fin</th>
                            <th>Status</th>
                            <th width="30%">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($schedules as $schedule)
                            <tr>
                                <td>{{ $schedule->name }} <a href="{{ route('schedules.edit', ['schedule' => $schedule->id]) }}"><i class="fa fa-pencil"></i></a></td>
                                <td>{{ $schedule->start_date_string }}</td>
                                <td>{{ $schedule->end_date_string }}</td>
                                <td>
                                    @if($constraints_in_schedule[$schedule->id] > 0)
                                        <i class="fa fa-exclamation-triangle text-warning fa-2x"
                                           data-toggle="tooltip" title="Il reste {{ $constraints_in_schedule[$schedule->id] }} contrainte(s) à valider"></i>
                                    @else
                                        <i class="fa fa-check-circle-o text-success fa-2x"></i>
                                    @endif
                                    <i class="{{ status($schedule->status_holidays) }} fa-2x"></i>
                                    <i class="{{ status($schedule->status_weekends) }} fa-2x"></i>
                                    <i class="{{ status($schedule->status_last_evening) }} fa-2x"></i>
                                    <i class="{{ status($schedule->status_clinical_departments) }} fa-2x"></i>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('calendar.show', ['schedule' => $schedule->id]) }}" class="btn btn-default">
                                            <i class="fa fa-eye"></i> Calendrier
                                        </a>
                                        <a href="{{ route('schedules.show', $schedule->id) }}" class="btn btn-success">
                                            <i class="fa fa-chevron-circle-right"></i> Générer
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
