@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Types de shifts</div>
                <div class="panel-body">
                    <a href="{{ route('shiftTypes.create') }}" class="btn btn-default pull-right"><span class="fa fa-plus"></span> Ajouter un type de shifts</a>
                    <rhpharma-shift-types
                            model-url="{{ route('shiftTypes.index') }}"
                            :rows="{{ $shiftTypes }}"
                    ></rhpharma-shift-types>
                </div>
            </div>
        </div>
    </div>


@endsection