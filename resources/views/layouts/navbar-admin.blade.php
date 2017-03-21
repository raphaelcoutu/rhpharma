@if(
    Auth::user()->can('read', App\Branch::class) ||
    Auth::user()->can('read', App\User::class) ||
    Auth::user()->can('read', App\Workplace::class) ||
    Auth::user()->can('read', App\Department::class) ||
    Auth::user()->can('read', App\Role::class) ||
    Auth::user()->can('read', App\Schedule::class ||
    Auth::user()->can('read', App\ConstraintType::class))
    )
<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
        Administration <span class="caret"></span>
    </a>

    <ul class="dropdown-menu" role="menu">
        @can('read', App\Branch::class)
            <li>
                <a href="{{ route('branches.index') }}">Gestion des branches</a>
            </li>
        @endcan
        @can('read', App\User::class)
            <li>
                <a href="{{ route('users.index') }}">Gestion des utilisateurs</a></li>
            </li>
        @endcan
        @can('read', App\Role::class)
            <li>
                <a href="{{ route('roles.index') }}">Gestion des permissions</a></li>
            </li>
        @endcan
        @can('read', App\Workplace::class)
            <li>
                <a href="{{ route('workplaces.index') }}">Gestion des lieux de travail</a></li>
            </li>
        @endcan
        @can('read', App\Department::class)
            <li>
                <a href="{{ route('departments.index') }}">Gestion des secteurs</a></li>
            </li>
        @endcan
        @can('read', App\Schedule::class)
            <li>
                <a href="{{ route('schedules.index') }}">Gestion des horaires</a></li>
            </li>
        @endcan

        @can('read', App\ConstraintType::class)
            <li>
                <a href="{{ route('constraintTypes.index') }}">Gestion des types de contraintes</a></li>
            </li>
        @endcan
    </ul>
</li>
@endif