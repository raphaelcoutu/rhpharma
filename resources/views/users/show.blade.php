@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            @if(Request::segment(1) == 'profile')
            <div class="panel-heading">Mon Profil</div>
            @else
            <div class="panel-heading">Visualisation d'un profil</div>
            @endif

            <div class="panel-body">

                @if(Request::segment(1) == 'profile')
                    <div class="col-md-3 col-md-offset-9">
                        <a href="#"><i class="fa fa-eye"></i> Mes anciennes contraintes</a>
                    </div>
                @else
                    <div class="col-md-3 col-md-offset-9">
                    @can('read', \App\Constraint::class)
                        <a href="{{ route('constraintsValidator.history', ['user' => $user->id]) }}"><i class="fa fa-eye"></i> Anciennes contraintes de {{ $user->firstname }}</a>
                    @endcan
                    </div>

                @endif

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
                            <td>{{ $user->is_active ? 'Actif' : 'Inactif'}}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>

                @can('write', App\User::class)
                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">Modifier l'utilisateur</a>
                @endcan

                @if(Request::segment(1) != 'profile')
                <h3>Secteurs cliniques</h3>

                <rhpharma-departments-users
                        :user-id="{{ $user->id }}"
                        :departments-prop="{{ $user->departments }}"
                        :departments-list="{{ $departments }}"
                ></rhpharma-departments-users>

                <h3>Permissions</h3>

                @endif

                <h3>Attributs</h3>

                <table class="table table-responsive">
                    <thead>
                    <tr>
                        <th>Type</th>
                        <th>Début</th>
                        <th>Fin</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if($user->attributes->count() > 0)
                            @foreach($user->attributes as $attribute)
                                <tr>
                                    <td>{{ $attribute->type }}</td>
                                    <td>{{ $attribute->start_date }}</td>
                                    <td>{{ $attribute->end_date }}</td>
                                    <td>{{ $attribute->description }}</td>
                                    <td></td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

@if(Request::segment(1) != 'profile')
<a href="{{ route('users.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Liste des utilisateurs</a>
@endif
@endsection