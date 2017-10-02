@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Gestion des jours fériés</div>
                <div class="panel-body">
                    <rhpharma-holidays :rows="{{ $holidays }}"></rhpharma-holidays>
                </div>
            </div>
        </div>
    </div>
@stop