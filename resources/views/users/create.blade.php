@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Créer un nouveau utilisateur</div>

                <div class="panel-body">
                    @include('users.form')
                </div>
            </div>
        </div>
    </div>
    <a href="{{ route('users.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Liste des utilisateurs</a>
@endsection