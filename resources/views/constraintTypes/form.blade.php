<form action="{{ route('constraintTypes.store') }}" method="POST" class="form form-horizontal">
    {{ csrf_field() }}
    <div class="form-group">
        <label for="name" class="col-md-3 control-label">Nom:</label>
        <div class="col-md-9 {{ $errors->first('name') ? 'has-error' : '' }}">
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}">
            <span class="help-block">{{ $errors->first('name') }}</span>
        </div>
    </div>

    <div class="form-group">
        <label for="description" class="col-md-3 control-label">Description:</label>
        <div class="col-md-9 {{ $errors->first('description') ? 'has-error' : '' }}">
            <input type="text" id="description" name="description" class="form-control" value="{{ old('description') }}">
            <span class="help-block">{{ $errors->first('description') }}</span>
        </div>
    </div>
    
    <div class="form-group">
        <label for="code" class="col-md-3 control-label">Code:</label>
        <div class="col-md-9 {{ $errors->first('code') ? 'has-error' : '' }}">
            <input type="text" id="code" name="code" class="form-control" value="{{ old('code') }}">
            <span class="help-block">{{ $errors->first('code') }}</span>
        </div>
    </div>
    
    <div class="form-group">
        <label for="is_work" class="col-md-3 control-label">Travail:</label>
        <div class="col-md-9 {{ $errors->first('is_work') ? 'has-error' : '' }}">
            <label class="radio-inline">
                <input type="radio" id="is_work" name="is_work" value="1" {{ old('is_work') == '1' ? 'checked' : '' }} > Oui
            </label>
            <label class="radio-inline">
                <input type="radio" id="is_work" name="is_work" value="0" {{ old('is_work') == '0' ? 'checked' : '' }}> Non
            </label>
            <span class="help-block">{{ $errors->first('is_work') }}</span>
        </div>
    </div>

    <div class="form-group">
        <label for="is_single_day" class="col-md-3 control-label">Une seule journée:</label>
        <div class="col-md-9 {{ $errors->first('is_single_day') ? 'has-error' : '' }}">
            <label class="radio-inline">
                <input type="radio" id="is_single_day" name="is_single_day" value="1" {{ old('is_single_day') == '1' ? 'checked' : '' }} > Oui
            </label>
            <label class="radio-inline">
                <input type="radio" id="is_single_day" name="is_single_day" value="0" {{ old('is_single_day') == '0' ? 'checked' : '' }}> Non
            </label>
            <span class="help-block">{{ $errors->first('is_single_day') }}</span>
        </div>
    </div>

    <div class="form-group">
        <label for="is_group_constraint" class="col-md-3 control-label">Contrainte selon disponibilité:</label>
        <div class="col-md-9 {{ $errors->first('is_group_constraint') ? 'has-error' : '' }}">
            <label class="radio-inline">
                <input type="radio" id="is_group_constraint" name="is_group_constraint" value="1" {{ old('is_group_constraint') == '1' ? 'checked' : '' }} > Oui
            </label>
            <label class="radio-inline">
                <input type="radio" id="is_group_constraint" name="is_group_constraint" value="0" {{ old('is_group_constraint') == '0' ? 'checked' : '' }}> Non
            </label>
            <span class="help-block">{{ $errors->first('is_group_constraint') }}</span>
        </div>
    </div>

    <div class="form-group">
        <label for="is_day_in_schedule" class="col-md-3 control-label">Journée à l'horaire:</label>
        <div class="col-md-9 {{ $errors->first('is_day_in_schedule') ? 'has-error' : '' }}">
            <label class="radio-inline">
                <input type="radio" id="is_day_in_schedule" name="is_day_in_schedule" value="1" {{ old('is_day_in_schedule') == '1' ? 'checked' : '' }} > Oui
            </label>
            <label class="radio-inline">
                <input type="radio" id="is_day_in_schedule" name="is_day_in_schedule" value="0" {{ old('is_day_in_schedule') == '0' ? 'checked' : '' }}> Non
            </label>
            <span class="help-block">{{ $errors->first('is_day_in_schedule') }}</span>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-3 pull-right">
            <input type="submit" class="btn btn-primary" value="Enregistrer">
        </div>
    </div>
</form>
