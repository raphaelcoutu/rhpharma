@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Modification d'horaire</div>

                <div class="panel-body">
                    @include('schedules.form')
                </div>
            </div>
        </div>
    </div>
    <a href="{{ route('schedules.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Liste des horaires</a>
@endsection