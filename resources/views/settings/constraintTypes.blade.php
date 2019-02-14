@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <a href="{{ url()->previous() }}" class="btn btn-default">Retour</a>
            <div class="panel panel-default">
                <div class="panel-heading">Paramètres généraux</div>
                <div class="panel-body">
                    <div class="row">
                        <rhpharma-settings-constraint-types
                                class="col-md-12"
                                :data-constraint-types="{{ $constraintTypes }}"
                        ></rhpharma-settings-constraint-types>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection