@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Générer l'horaire : <strong>{{ $schedule->name }}</strong></div>
                <div class="panel-body">
                    <p>Dates: {{ $schedule->start_date_string }} - {{ $schedule->end_date_string }} ({{ $schedule->duration_in_weeks }} semaines)</p>

                    <div class="pull-right">
                        <a href="{{ route('calendar.show', ['id' => $schedule->id]) }}" class="btn btn-default">
                            <i class="fa fa-eye"></i> Calendrier
                        </a>
                        <a href="{{ route('export', $schedule->id) }}" class="btn btn-success">Exportation Excel</a>
                    </div>

                    <h3>Processus</h3>
                    <rhpharma-schedule-processus
                            :schedule="{{ $schedule }}"
                            :constraints-count="{{ $constraints_count }}"
                    ></rhpharma-schedule-processus>

                    <h3>Conflits</h3>

                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Étape du processus</th>
                            <th>Sévérité</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="text-center">
                            <td colspan="4">Aucun conflit pour l'instant</td>
                        </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
    <a href="{{ route('schedules.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Liste des horaires</a>
@stop