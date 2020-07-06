@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Éditer un shift</div>
                <div class="panel-body">
                    @include('shifts.form', ['model' => $shift])
                </div>
            </div>
        </div>
    </div>
    <a href="{{ route('shifts.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Liste des shifts</a>
@stop