@if(isset($model))
<form action="{{ route('shiftTypes.update', $model->id) }}" method="POST" class="form form-horizontal">
    <input type="hidden" name="id" value="{{ $model->id }}">
@else
<form action="{{ route('shiftTypes.store') }}" method="POST" class="form form-horizontal">
@endif
    {{ csrf_field() }}

    <div class="form-group">
        <label for="name" class="col-md-3 control-label">Nom:</label>
        <div class="col-md-9 {{ $errors->first('name') ? 'has-error' : '' }}">
        @if(isset($model) && is_null(old('name')))
            <input type="text" id="name" name="name" class="form-control" value="{{ $model->name }}">
        @else
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}">
        @endif
            <span class="help-block">{{ $errors->first('name') }}</span>
        </div>
    </div>

    <div class="form-group">
        <label for="name" class="col-md-3 control-label">Début:</label>
        <div class="col-md-9 {{ $errors->first('start_time') ? 'has-error' : '' }}">
            @if(isset($model) && is_null(old('start_time')))
                <input type="text" id="name" name="start_time" class="form-control" value="{{ $model->start_time }}" placeholder="00:00:00">
            @else
                <input type="text" id="name" name="start_time" class="form-control" value="{{ old('start_time') }}" placeholder="00:00:00">
            @endif
            <span class="help-block">{{ $errors->first('start_time') }}</span>
        </div>
    </div>


    <div class="form-group">
        <label for="name" class="col-md-3 control-label">Fin:</label>
        <div class="col-md-9 {{ $errors->first('end_time') ? 'has-error' : '' }}">
            @if(isset($model) && is_null(old('end_time')))
                <input type="text" id="name" name="end_time" class="form-control" value="{{ $model->end_time }}" placeholder="00:00:00">
            @else
                <input type="text" id="name" name="end_time" class="form-control" value="{{ old('end_time') }}" placeholder="00:00:00">
            @endif
            <span class="help-block">{{ $errors->first('end_time') }}</span>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-3 pull-right">
            <input type="submit" class="btn btn-primary" value="{{ isset($model) ? 'Modifier' : 'Enregistrer' }}">
        </div>
    </div>
</form>
