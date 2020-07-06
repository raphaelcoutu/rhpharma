@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Shifts</div>
                <div class="panel-body">
                    <a href="{{ route('shifts.create') }}" class="btn btn-default pull-right"><span class="fa fa-plus"></span> Ajouter un shift</a>
                    <rhpharma-shifts
                            model-url="{{ route('shifts.index') }}"
                            :rows="{{ $shifts }}"
                    ></rhpharma-shifts>
                </div>
            </div>
        </div>
    </div>


@endsection