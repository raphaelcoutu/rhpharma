@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Rôles et Permissions</div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Rôle</th>
                            <th>Description</th>
                            <th>Permissions associées</th>
                            <th>Options</th>
                        </tr>

                        </thead>
                        <tbody>
                        @foreach($roles as $role)
                            <tr>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->description }}</td>
                                <td>
                                    <div class="permissions">
                                    @foreach($permissions as $permission)
                                    <div>@include('roles.permission-checkbox', [$role, $permission])</div>
                                    @endforeach
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('roles.edit', ['role' => $role]) }}" class="btn btn-warning"><i class="fa fa-pencil"></i></a>
                                    <a href="#" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
