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
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
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
                            @foreach($shifts[$pharmacien->id] as $index => $day)
                                @if($day)
                                    @if($index % 7 == 0 || $index % 7 == 6)
                                    <td class="alert-info">
                                    @else
                                    <td>
                                    @endif
                                        @foreach($day as $innerDay)
                                            @if($innerDay instanceof \App\AssignedShift)
                                                {{ $innerDay->shift->code }}
                                            @elseif ($innerDay instanceof \App\Constraint)
                                                <span class="text-danger">{{ $innerDay->constraintType->code }}</span>
                                            @endif
                                        @endforeach
                                    </td>
                                @else
                                    @if($index % 7 == 0 || $index % 7 == 6)
                                        <td class="alert-info"></td>
                                    @else
                                        <td></td>
                                    @endif
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