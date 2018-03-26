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
                        <a href="#" class="btn btn-success">Exportation Excel</a>
                    </div>

                    <h3>Processus</h3>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Étape</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Validation des contraintes <br>
                                <ul>
                                    <li><strong>Limite:</strong> {{ $schedule->constraint_limit_date_string }}</li>
                                    <li><strong>Contraintes restantes à valider:</strong> {{ $constraints_count }}</li>
                                </ul>
                            </td>
                            <td>
                                @if($constraints_count > 0)
                                    <i class="fa fa-exclamation-triangle text-warning fa-2x"
                                       data-toggle="tooltip" title="Il reste {{ $constraints_count }} contrainte(s) à valider"></i>
                                @else
                                    <i class="fa fa-check-circle-o text-success fa-2x"></i>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('constraintsValidator.index', ['schedule' => $schedule->id]) }}" class="btn btn-default">Validation des contraintes</a>
                            </td>
                        </tr>
                        <tr>
                            <td>Assigner les fériés</td>
                            <td><i class="{{ status($schedule->status_holidays) }} fa-2x"></i></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="#" class="btn btn-success">Générer</a>
                                    <a href="#" class="btn btn-primary">Réanalyser</a>
                                    <a href="#" class="btn btn-danger">Mise à zéro</a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Assigner les jours de fins de semaine</td>
                            <td><i class="{{ status($schedule->status_weekends) }} fa-2x"></i></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="#" class="btn btn-success">Générer</a>
                                    <a href="#" class="btn btn-primary">Réanalyser</a>
                                    <a href="#" class="btn btn-danger">Mise à zéro</a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Assigner les derniers soirs de chaque semaine<br/><i>(ex: vendredi soir ou jeudi soir si férié)</i></td>
                            <td><i class="{{ status($schedule->status_last_evening) }} fa-2x"></i></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="#" class="btn btn-success">Générer</a>
                                    <a href="#" class="btn btn-primary">Réanalyser</a>
                                    <a href="#" class="btn btn-danger">Mise à zéro</a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Assigner les secteurs cliniques</td>
                            <td><i class="{{ status($schedule->status_clinical_departments) }} fa-2x"></i></td>
                            <td>
                                <rhpharma-build-buttons schedule-id="{{ $schedule->id }}"></rhpharma-build-buttons>
                            </td>
                        </tr>
                        <tr>
                            <td>Assigner la distribution</td>
                            <td><i>À VENIR</i></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="#" class="btn btn-success">Générer</a>
                                    <a href="#" class="btn btn-primary">Réanalyser</a>
                                    <a href="#" class="btn btn-danger">Mise à zéro</a>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>

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