@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Créer un nouveau type de shifts</div>
                <div class="panel-body">
                    @include('shiftTypes.form')
                </div>
            </div>
        </div>
    </div>
    <a href="{{ route('shiftTypes.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Liste des types de shifts</a>
@stop