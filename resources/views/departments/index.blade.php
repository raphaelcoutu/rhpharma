@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Secteurs</div>
                <div class="panel-body">
                    <a href="{{ route('departments.create') }}" class="btn btn-default pull-right"><span class="fa fa-plus"></span> Ajouter un secteur</a>
                    <rhpharma-departments
                            model-url="{{ route('departments.index') }}"
                            :rows="{{ $departments }}"
                    ></rhpharma-departments>
                </div>
            </div>
        </div>
    </div>


@endsection