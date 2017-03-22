@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">{{ __('users.title') }}</div>

            <div class="panel-body">

                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Courriel</th>
                        <th>Jours de travail par semaine</th>
                        <th>Ancienneté</th>
                        <th>Branche</th>
                        <th>Statut</th>
                        <th>Attributs</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $user->lastname }}</td>
                            <td>{{ $user->firstname }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->workdays_per_week }}</td>
                            <td>{{ $user->seniority }}</td>
                            <td>{{ $user->branch->name }}</td>
                            <td>{{ $user->is_active }}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>

                <h3>Secteurs</h3>

                <h3>Permissions</h3>

            </div>
        </div>
    </div>
</div>
<a href="{{ route('users.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Liste des utilisateurs</a>
@endsection