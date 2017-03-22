@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Horaires</div>
                <div class="panel-body">
                    <rhpharma-schedule :rows="{{ $schedules }}"></rhpharma-schedule>
                </div>
            </div>
        </div>
    </div>

@endsection