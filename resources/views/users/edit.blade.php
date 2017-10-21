@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Modification d'un utilisateur existant : {{ $user->firstname }} {{ $user->lastname }}</div>

                <div class="panel-body">
                    @include('users.form', $user)
                </div>
            </div>
        </div>
    </div>
    <a href="{{ route('users.show', $user->id) }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Liste des utilisateurs</a>
@endsection