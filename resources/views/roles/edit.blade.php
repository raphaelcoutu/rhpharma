@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Rôles et Permissions: {{ $role->name }}</div>
                <div class="panel-body">
                    @include('roles.form')
                </div>
            </div>
        </div>
    </div>

@endsection
