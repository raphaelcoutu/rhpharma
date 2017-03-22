@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">{{ __('users.title') }}</div>

            <div class="panel-body">
                <a href="{{ route('users.create') }}" class="btn btn-default pull-right"><span class="fa fa-plus"></span> {{ __('users.add_user') }}</a>
                <rhpharma-users
                        model-url="{{ route('users.index') }}"
                        :rows="{{ $users }}"
                ></rhpharma-users>
            </div>
        </div>
    </div>
</div>
@endsection