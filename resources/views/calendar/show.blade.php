@extends('layouts.app')

@section('content')
    <h1>Hello</h1>
    <div class="table-responsive">
        <table class="table">
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
                    @foreach($shifts[$pharmacien->id] as $shift)
                        <td>{{ $shift }}</td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection