@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Dernières contraintes validées</div>

                <div class="panel-body">
                    <a href="{{ route('constraintsValidator.index') }}">
                        <i class="glyphicon glyphicon-arrow-left"></i> Retour aux contraintes à valider
                    </a>

                    <table class="table table-responsive">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Utilisateur</th>
                            <th>Date</th>
                            <th>Raison</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($constraints as $constraint)
                            <tr>
                                <td>
                                    {{ $constraint->updated_at }} <br>
                                    {{ $constraint->validator->firstname }} {{ $constraint->validator->lastname }} <br>
                                    @if($constraint->status == 1)
                                        <span class="label label-success">Approuvé</span>
                                    @else
                                        <span class="label label-danger">Refusé</span>
                                    @endif
                                </td>
                                <td>{{ $constraint->user->firstname }} {{ $constraint->user->lastname }}</td>
                                <td>{{ $constraint->start_datetime }} <br> {{ $constraint->end_datetime }}</td>
                                <td>{{ $constraint->comment }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection