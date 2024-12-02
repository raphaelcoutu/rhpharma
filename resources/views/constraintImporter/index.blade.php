@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Importer des contraintes</div>
                <div class="panel-body">
                    @if(session('status'))
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-12">
                            @if(session('newUsers') || session('newConstraintTypes'))
                                <div class="alert alert-info">
                                    @if(session('newUsers'))
                                        <b>Nouveaux utilisateurs:</b>
                                        <ul>
                                            @foreach (session('newUsers') as $user)
                                                <li><strong>Azure Id:</strong> {{ $user['Id'] }}
                                                    <br>{{ $user['LastName'] }}
                                                    , {{ $user['FirstName'] }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                    @if(session('newConstraintTypes'))
                                        <b>Nouveaux types de contraintes:</b>
                                        <ul>
                                            @foreach (session('newConstraintTypes') as $type)
                                                <li><strong>Azure Id:</strong> {{ $type['Id'] }}
                                                    <br><strong>Nom:</strong> {{ $type['Name'] }}
                                                    <br><strong>Description:</strong> {{ $type['Description'] }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    <rhpharma-constraint-importer></rhpharma-constraint-importer>
                </div>
            </div>
        </div>
    </div>
@endsection
