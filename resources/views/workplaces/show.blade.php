@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Lieu : {{ $workplace->name }}</div>
                <div class="panel-body">
                <a href="{{ route('departments.create') }}" class="btn btn-default pull-right"><i class="fa fa-plus"></i> Ajouter un secteur</a>
                    <table class="table table-stripped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Code</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($workplace->departments->count() > 0)
                            @foreach($workplace->departments as $dep)
                            <tr>
                                <td>{{ $dep->id }}</td>
                                <td>{{ $dep->name }}</td>
                                <td>{{ $dep->code }}</td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="text-center">Aucun secteur associé à ce lieu.</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<a href="{{ route('workplaces.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Liste des lieux</a>
@endsection
