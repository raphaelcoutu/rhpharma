@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">Gestion des types de contraintes</div>

            <div class="panel-body">
                <a href="{{ route('constraintTypes.create') }}" class="btn btn-default pull-right">
                    <i class="fa fa-plus"></i> Ajouter un type de contraintes</i>
                </a>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Code</th>
                            <th>Travail</th>
                            <th>Une seule journée</th>
                            <th>Critères</th>
                            <th>Contrainte selon disponibilité</th>
                            <th>Journée à l'horaire</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($constraintTypes->count() > 0)
                            @foreach ($constraintTypes as $type)
                                <tr>
                                    <td>{{ $type->name }}</td>
                                    <td>{{ $type->description }}</td>
                                    <td>{{ $type->code }}</td>
                                    <td>{{ $type->is_work ? 'Oui' : 'Non' }}</td>
                                    <td>{{ $type->is_single_day ? 'Oui' : 'Non' }}</td>
                                    <td><a href="#" class="btn btn-default"><i class="fa fa-eye"></i></a></td>
                                    <td>{{ $type->is_group_constraint ? 'Oui' : 'Non' }}</td>
                                    <td>{{ $type->is_day_in_schedule ? 'Oui' : 'Non' }}</td>
                                    <td><a href="{{ route('constraintTypes.edit', ['constraintType' => $type->id]) }}" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="9" class="text-center">Aucun type de contrainte.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop
