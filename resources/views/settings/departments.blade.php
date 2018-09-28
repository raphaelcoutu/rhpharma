@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <a href="{{ url()->previous() }}" class="btn btn-default">Retour</a>
            <div class="panel panel-default">
                <div class="panel-heading">Paramètres généraux</div>
                <div class="panel-body">
                    @foreach($departments as $department)
                        @if($loop->index % 2 == 0)
                        <div class="row">
                        @endif
                            <rhpharma-settings-department-user
                                    class="col-md-6"
                                    :data-department="{{ $department }}"
                            ></rhpharma-settings-department-user>
                        @if($loop->index % 2 == 1)
                        </div>
                        @endif
                    @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection