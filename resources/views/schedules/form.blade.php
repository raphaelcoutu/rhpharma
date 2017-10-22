@isset($schedule)
<form action="{{ route('schedules.update', $schedule->id) }}" method="POST" class="form form-horizontal">
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="id" value="{{ $schedule->id }}">
@else
<form action="{{ route('schedules.store') }}" method="POST" class="form form-horizontal">
@endisset
    {{ csrf_field() }}

    <div class="form-group">
        <label for="name" class="col-md-3 control-label">Nom de l'horaire:</label>
        <div class="col-md-3 {{ $errors->first('name') ? 'has-error' : '' }}">
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($schedule) ? $schedule->name : '') }}">
            <span class="help-block">{{ $errors->first('name') }}</span>
        </div>

        <label for="constraint_limit_date" class="col-md-3 control-label">Date limite pour contraintes:</label>
        <div class="col-md-3 {{ $errors->first('constraint_limit_date') ? 'has-error' : '' }}">
            <input type="text" id="constraint_limit_date" name="constraint_limit_date" class="form-control"
                   value="{{ old('constraint_limit_date', isset($schedule) ? $schedule->constraint_limit_date_string : '') }}">
            <span class="help-block">{{ $errors->first('constraint_limit_date') }}</span>
        </div>

    </div>

    <div class="form-group">
        <label for="start_date" class="col-md-3 control-label">Début:</label>
        <div class="col-md-3 {{ $errors->first('start_date') ? 'has-error' : '' }}">
            <input type="text" id="start_date" name="start_date" class="form-control"
                   value="{{ old('start_date', isset($schedule) ? $schedule->start_date_string : '') }}">
            <span class="help-block">{{ $errors->first('start_date') }}</span>
        </div>

        <label for="end_date" class="col-md-3 control-label">Fin:</label>
        <div class="col-md-3 {{ $errors->first('end_date') ? 'has-error' : '' }}">
            <input type="text" id="end_date" name="end_date" class="form-control"
                   value="{{ old('end_date', isset($schedule) ? $schedule->end_date_string : '')  }}">
            <span class="help-block">{{ $errors->first('end_date') }}</span>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-3 pull-right">
            <input type="submit" class="btn btn-primary" value="Enregistrer">
        </div>
    </div>

</form>