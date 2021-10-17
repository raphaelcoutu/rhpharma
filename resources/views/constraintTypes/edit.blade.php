@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Modification d'un type de contrainte : {{ $constraintType->name }}</div>

                <div class="panel-body">
                    @include('constraintTypes.form', $constraintType)
                </div>
            </div>
        </div>
    </div>
    <a href="{{ route('constraintTypes.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Liste des types de contraintes</a>
@endsection