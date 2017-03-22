@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">Créer un nouveau lieu de travail</div>
            <div class="panel-body">
                @include('workplaces.form')
            </div>
        </div>
    </div>
</div>
<a href="{{ route('workplaces.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Liste des lieux de travail</a>
@stop