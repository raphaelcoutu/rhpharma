@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Lieux de travail</div>

                <div class="panel-body">
                    <a href="{{ route('workplaces.create') }}" class="btn btn-default pull-right"><i class="fa fa-plus"></i> Ajouter un lieu de travail</a>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Lieu de travail</th>
                            <th>Code</th>
                            <th>Adresse</th>
                            <th>Ville</th>
                            <th>Province</th>
                            <th>Pays</th>
                            <th>Code postal</th>
                            <th>Options</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($workplaces as $wp)
                        <tr>
                            <td>{{ $wp->name }}</td>
                            <td>{{ $wp->code }}</td>
                            <td>{{ $wp->address }}</td>
                            <td>{{ $wp->city }}</td>
                            <td>{{ $wp->province }}</td>
                            <td>{{ $wp->country }}</td>
                            <td>{{ $wp->postal_code }}</td>
                            <td><a href="{{ route('workplaces.show', $wp->id) }}" class="btn btn-default btn-xs"><i class="fa fa-list"></i> Secteurs ({{ $wp->departments_count }})</a></td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection