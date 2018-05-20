@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <h3>Calendrier</h3>
            <a href="{{ route('schedules.show', $schedule->id) }}" class="btn btn-sm btn-success">&leftarrow; Processus</a>
        </div>

        <div class="col-md-4 col-md-offset-4">
            <h3>{{ $schedule->start_date_string }} au {{ $schedule->end_date_string }}</h3>
            <div class="btn-group btn-group-sm pull-right">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Autres vues <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    @include('calendar.dropdown')
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('below-container')
    <rhpharma-calendar
        :data-schedule="{{ $schedule }}"
        :data-users="{{ $users }}"
    v-cloak>
    </rhpharma-calendar>
@endsection