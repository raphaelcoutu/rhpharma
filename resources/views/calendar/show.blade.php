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
                    <li><a href="{{ route('calendar.show', $schedule->id) }}">Complète</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="{{ route('calendar.showByDepartment', ['schedule' => $schedule->id, 'department' => 1]) }}">Antico</a></li>
                    <li><a href="{{ route('calendar.showByDepartment', ['schedule' => $schedule->id, 'department' => 2]) }}">Médecine interne</a></li>
                    <li><a href="{{ route('calendar.showByDepartment', ['schedule' => $schedule->id, 'department' => 4]) }}">Soins intensifs</a></li>
                </ul>
            </div>
        </div>
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Pharmacien</th>
                        @for($i = 0; $i < $schedule->duration_in_weeks*7; $i++)
                            <th class="text-nowrap">{{ $schedule->start_date->addDays($i)->format('j-n')}}</th>
                        @endfor
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($pharmaciens as $pharmacien)
                        <tr>
                            <td class="text-nowrap">{{$pharmacien->id}}-{{ $pharmacien->fullname }}</td>
                            @foreach($shifts[$pharmacien->id] as $day)
                                @if($day)
                                    <td>
                                        @foreach($day as $innerDay)
                                        {{ $innerDay->shift->code }}
                                        @endforeach
                                    </td>
                                @else
                                    <td></td>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection