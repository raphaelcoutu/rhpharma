@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Éditer un secteur</div>
                <div class="panel-body">
                    @include('departments.form', ['model' => $department])
                    <hr>
                    <h3>Pharmaciens associés au secteur</h3>
                    <ul>
                        @forelse($department->users as $pharmacien)
                            <li><a href="{{ route('users.show', $pharmacien->id) }}">{{ $pharmacien->firstname }} {{ $pharmacien->lastname }}</a></li>
                        @empty
                            <li>Aucun pharmacien</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <a href="{{ route('departments.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Liste des secteurs</a>
@stop