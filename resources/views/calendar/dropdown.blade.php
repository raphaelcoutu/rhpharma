<li><a href="{{ route('calendar.show', $schedule->id) }}">Complète</a></li>
<li role="separator" class="divider"></li>
<li><a href="{{ route('calendar.showByDepartment', ['schedule' => $schedule->id, 'department' => "3,24,25,26,27,28,29"]) }}">Oncologie</a></li>
<li><a href="{{ route('calendar.showByDepartment', ['schedule' => $schedule->id, 'department' => "4,5,6"]) }}">Soins intensifs</a></li>
<li role="separator" class="divider"></li>
@foreach($departments as $department)
    <li><a href="{{ route('calendar.showByDepartment', ['schedule' => $schedule->id, 'department' => $department->id]) }}">{{ $department->name }}</a></li>
@endforeach

