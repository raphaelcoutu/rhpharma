@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Contraintes à valider</div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
                            @isset($schedule)
                                <p><strong>Filtre</strong> : <a href="{{ route('constraintsValidator.index') }}" class="btn btn-xs btn-default"
                                    data-toggle="tooltip" data-placement="top" title="Cliquer pour retirer le filtre">
                                        Du {{ $schedule->start_date_string }} au {{ $schedule->end_date_string }}</a>
                                </p>
                            @endisset
                        </div>
                        <div class="col-md-6 text-center">
                            <rhpharma-constraints-count></rhpharma-constraints-count>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('constraintsValidator.history') }}"><i class="fa fa-list"></i> Dern. contraintes validées</a><br>
                            <a href="{{ route('constraintsValidator.history', ['status' => 1]) }}"><i class="fa fa-check-circle-o"></i> Dern. contraintes approuvées</a><br>
                            <a href="{{ route('constraintsValidator.history', ['status' => 2]) }}"><i class="fa fa-times-circle-o"></i> Dern. contraintes refusées</a>
                        </div>
                    </div>

                    <rhpharma-constraints-validator
                            :constraints-props="{{ $constraints }}"
                            :validator-id-props="{{ \Auth::user()->id }}"
                    >
                    </rhpharma-constraints-validator>
                </div>
            </div>
        </div>
    </div>
@endsection