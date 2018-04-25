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
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Calendrier <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('calendar.show', $schedule->id) }}">Complète</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="{{ route('calendar.showByDepartment', ['schedule' => $schedule->id, 'department' => 12]) }}">C.I.M.</a></li>
                                <li><a href="{{ route('calendar.showByDepartment', ['schedule' => $schedule->id, 'department' => 1]) }}">Coumadin</a></li>
                                <li><a href="{{ route('calendar.showByDepartment', ['schedule' => $schedule->id, 'department' => 10]) }}">Insuffisance cardiaque</a></li>
                                <li><a href="{{ route('calendar.showByDepartment', ['schedule' => $schedule->id, 'department' => 2]) }}">Médecine interne</a></li>
                                <li><a href="{{ route('calendar.showByDepartment', ['schedule' => $schedule->id, 'department' => 14]) }}">Mère enfant</a></li>
                                <li><a href="{{ route('calendar.showByDepartment', ['schedule' => $schedule->id, 'department' => 3]) }}">Oncologie</a></li>
                                <li><a href="{{ route('calendar.showByDepartment', ['schedule' => $schedule->id, 'department' => 15]) }}">Pédiatrie</a></li>
                                <li><a href="{{ route('calendar.showByDepartment', ['schedule' => $schedule->id, 'department' => 9]) }}">Psychiatrie</a></li>
                                <li><a href="{{ route('calendar.showByDepartment', ['schedule' => $schedule->id, 'department' => 8]) }}">SIPA</a></li>
                                <li><a href="{{ route('calendar.showByDepartment', ['schedule' => $schedule->id, 'department' => 4]) }}">Soins intensifs</a></li>
                                <li><a href="{{ route('calendar.showByDepartment', ['schedule' => $schedule->id, 'department' => 11]) }}">Soins palliatifs</a></li>
                                <li><a href="{{ route('calendar.showByDepartment', ['schedule' => $schedule->id, 'department' => 16]) }}">Urgence</a></li>
                                <li><a href="{{ route('calendar.showByDepartment', ['schedule' => $schedule->id, 'department' => 7]) }}">VIH</a></li>
                            </ul>
                        </div>
                        <a href="{{ route('export', $schedule->id) }}" class="btn btn-success">Exportation Excel</a>
                    </div>

                    <h3>Processus</h3>
                    <rhpharma-schedule-processus
                            :schedule="{{ $schedule }}"
                            :constraints-count="{{ $constraints_count }}"
                    ></rhpharma-schedule-processus>

                    <rhpharma-schedule-output
                            :schedule="{{ $schedule }}"
                            :conflicts="{{ $schedule->conflicts }}"
                    ></rhpharma-schedule-output>
                </div>
            </div>
        </div>
    </div>
    <a href="{{ route('schedules.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Liste des horaires</a>
@stop