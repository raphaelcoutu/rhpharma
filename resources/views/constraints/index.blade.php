@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Mes contraintes</div>

                <div class="panel-body">
                    <rhpharma-constraints
                        :schedules="{{ $schedules }}"
                        :constraint-types="{{ $constraintTypes }}"
                        :availability-constraints="{{ $availabilityConstraints }}"
                        :fixed-constraints="{{ $fixedConstraints }}"
                    ></rhpharma-constraints>
                </div>
            </div>
        </div>
    </div>
@endsection