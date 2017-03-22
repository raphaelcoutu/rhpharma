@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">Créer un nouveau type de contraintes</div>
            <div class="panel-body">
                @include('constraintTypes.form')
            </div>
        </div>
        <a href="{{ route('constraintTypes.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Liste des types de contraintes</a>
    </div>
</div>
@stop