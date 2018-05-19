<li><a href="{{ route('calendar.show', $schedule->id) }}">Complète</a></li>
<li role="separator" class="divider"></li>
<li><a href="{{ route('calendar.showByDepartment', ['schedule' => $schedule->id, 'department' => 12]) }}">C.I.M.</a></li>
<li><a href="{{ route('calendar.showByDepartment', ['schedule' => $schedule->id, 'department' => 1]) }}">Coumadin</a></li>
<li><a href="{{ route('calendar.showByDepartment', ['schedule' => $schedule->id, 'department' => 23]) }}">Douleur pelvienne</a></li>
<li><a href="{{ route('calendar.showByDepartment', ['schedule' => $schedule->id, 'department' => 10]) }}">Insuffisance cardiaque</a></li>
<li><a href="{{ route('calendar.showByDepartment', ['schedule' => $schedule->id, 'department' => 2]) }}">Médecine interne</a></li>
<li><a href="{{ route('calendar.showByDepartment', ['schedule' => $schedule->id, 'department' => 14]) }}">Mère enfant</a></li>
<li><a href="{{ route('calendar.showByDepartment', ['schedule' => $schedule->id, 'department' => "3,24,25,26"]) }}">Oncologie</a></li>
<li><a href="{{ route('calendar.showByDepartment', ['schedule' => $schedule->id, 'department' => 15]) }}">Pédiatrie</a></li>
<li><a href="{{ route('calendar.showByDepartment', ['schedule' => $schedule->id, 'department' => 21]) }}">Prévoir</a></li>
<li><a href="{{ route('calendar.showByDepartment', ['schedule' => $schedule->id, 'department' => 9]) }}">Psychiatrie</a></li>
<li><a href="{{ route('calendar.showByDepartment', ['schedule' => $schedule->id, 'department' => 8]) }}">SIPA</a></li>
<li><a href="{{ route('calendar.showByDepartment', ['schedule' => $schedule->id, 'department' => "4,5,6"]) }}">Soins intensifs</a></li>
<li><a href="{{ route('calendar.showByDepartment', ['schedule' => $schedule->id, 'department' => 11]) }}">Soins palliatifs</a></li>
<li><a href="{{ route('calendar.showByDepartment', ['schedule' => $schedule->id, 'department' => 16]) }}">Urgence</a></li>
<li><a href="{{ route('calendar.showByDepartment', ['schedule' => $schedule->id, 'department' => 7]) }}">VIH</a></li>

