@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Générer l'horaire : <strong>{{ $schedule->name }}</strong></div>
                <div class="panel-body">
                    <p>Dates: {{ $schedule->start_date_string }} - {{ $schedule->end_date_string }} ({{ $schedule->duration_in_weeks }} semaines)</p>

                    <div class="pull-right">
                        <div class="btn-group">
                            <a class="btn btn-default" href="{{ route('settings.departments') }}"><i class="fa fa-gear"></i> Secteurs</a>
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Calendrier <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                @include('calendar.dropdown')
                            </ul>
                        </div>
                        <a href="{{ route('export', $schedule->id) }}" class="btn btn-success">Exportation Excel</a>
                    </div>

                    <h3>Processus</h3>
                    <rhpharma-schedule
                            :data-schedule="{{ $schedule }}"
                            :data-constraints-count="{{ $constraints_count }}"
                            :data-conflicts="{{ $schedule->conflicts }}"
                    ></rhpharma-schedule>
                </div>
            </div>
        </div>
    </div>
    <a href="{{ route('schedules.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Liste des horaires</a>
@stop