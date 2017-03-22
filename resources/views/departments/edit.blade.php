@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Éditer un secteur</div>
                <div class="panel-body">
                    @include('departments.form', ['model' => $department])
                </div>
            </div>
        </div>
    </div>
    <a href="{{ route('departments.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Liste des secteurs</a>
@stop