<h3>Paramètres de pointage</h3>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="bonus_weeks" class="control-label col-md-9">Bonus (semaines):</label>
            <div class="col-md-3 {{ $errors->first('bonus_weeks') ? 'has-error' : '' }}">
                @if(isset($model) && is_null(old('bonus_weeks')))
                    <input type="text" id="bonus_weeks" name="bonus_weeks" class="form-control" value="{{ $model->bonus_weeks }}">
                @else
                    <input type="text" id="bonus_weeks" name="bonus_weeks" class="form-control" value="{{ old('bonus_weeks') }}">
                @endif
                <span class="help-block">{{ $errors->first('bonus_weeks') }}</span>
            </div>
        </div>

        <div class="form-group">
            <label for="bonus_pts" class="control-label col-md-9">Bonus (points):</label>
            <div class="col-md-3 {{ $errors->first('bonus_pts') ? 'has-error' : '' }}">
                @if(isset($model) && is_null(old('bonus_pts')))
                    <input type="text" id="bonus_pts" name="bonus_pts" class="form-control" value="{{ $model->bonus_pts }}">
                @else
                    <input type="text" id="bonus_pts" name="bonus_pts" class="form-control" value="{{ old('bonus_pts') }}">
                @endif
                <span class="help-block">{{ $errors->first('bonus_pts') }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="malus_weeks" class="col-md-9 control-label">Malus (semaines):</label>
            <div class="col-md-3 {{ $errors->first('malus_weeks') ? 'has-error' : '' }}">
                @if(isset($model) && is_null(old('malus_weeks')))
                    <input type="text" id="malus_weeks" name="malus_weeks" class="form-control" value="{{ $model->malus_weeks }}">
                @else
                    <input type="text" id="malus_weeks" name="malus_weeks" class="form-control" value="{{ old('malus_weeks') }}">
                @endif
                <span class="help-block">{{ $errors->first('malus_weeks') }}</span>
            </div>
        </div>

        <div class="form-group">
            <label for="malus_pts" class="col-md-9 control-label">Malus (points):</label>
            <div class="col-md-3 {{ $errors->first('malus_pts') ? 'has-error' : '' }}">
                @if(isset($model) && is_null(old('malus_pts')))
                    <input type="text" id="malus_pts" name="malus_pts" class="form-control" value="{{ $model->malus_pts }}">
                @else
                    <input type="text" id="malus_pts" name="malus_pts" class="form-control" value="{{ old('malus_pts') }}">
                @endif
                <span class="help-block">{{ $errors->first('malus_pts') }}</span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <hr class="col-md-4 col-md-offset-4">
</div>

<div class="form-group">
    <label for="monday_am" class="control-label col-md-3">Lundi:</label>
    <div class="col-md-1 {{ $errors->first('monday_am') ? 'has-error' : '' }}">
        @if(isset($model) && is_null(old('monday_am')))
            <input type="text" id="monday_am" name="monday_am" class="form-control" placeholder="AM"
                   value="{{ $model->monday_am }}">
        @else
            <input type="text" id="monday_am" name="monday_am" class="form-control" placeholder="AM"
                   value="{{ old('monday_am') }}">
        @endif
        <span class="help-block">{{ $errors->first('monday_am') }}</span>
    </div>
    <div class="col-md-1 {{ $errors->first('monday_pm') ? 'has-error' : '' }}">
        @if(isset($model) && is_null(old('monday_pm')))
            <input type="text" id="monday_pm" name="monday_pm" class="form-control" placeholder="PM"
                   value="{{ $model->monday_pm }}">
        @else
            <input type="text" id="monday_pm" name="monday_pm" class="form-control" placeholder="PM"
                   value="{{ old('monday_pm') }}">
        @endif
        <span class="help-block">{{ $errors->first('monday_pm') }}</span>
    </div>
</div>

<div class="form-group">
    <label for="monday_am" class="control-label col-md-3">Mardi:</label>
    <div class="col-md-1 {{ $errors->first('tuesday_am') ? 'has-error' : '' }}">
        @if(isset($model) && is_null(old('tuesday_am')))
            <input type="text" id="tuesday_am" name="tuesday_am" class="form-control" placeholder="AM"
                   value="{{ $model->tuesday_am }}">
        @else
            <input type="text" id="tuesday_am" name="tuesday_am" class="form-control" placeholder="AM"
                   value="{{ old('tuesday_am') }}">
        @endif
        <span class="help-block">{{ $errors->first('tuesday_am') }}</span>
    </div>
    <div class="col-md-1 {{ $errors->first('tuesday_pm') ? 'has-error' : '' }}">
        @if(isset($model) && is_null(old('tuesday_pm')))
            <input type="text" id="tuesday_pm" name="tuesday_pm" class="form-control" placeholder="PM"
                   value="{{ $model->tuesday_pm }}">
        @else
            <input type="text" id="tuesday_pm" name="tuesday_pm" class="form-control" placeholder="PM"
                   value="{{ old('tuesday_pm') }}">
        @endif
        <span class="help-block">{{ $errors->first('tuesday_pm') }}</span>
    </div>
</div>

<div class="form-group">
    <label for="monday_am" class="control-label col-md-3">Mercredi:</label>
    <div class="col-md-1 {{ $errors->first('wednesday_am') ? 'has-error' : '' }}">
        @if(isset($model) && is_null(old('wednesday_am')))
            <input type="text" id="wednesday_am" name="wednesday_am" class="form-control" placeholder="AM"
                   value="{{ $model->wednesday_am }}">
        @else
            <input type="text" id="wednesday_am" name="wednesday_am" class="form-control" placeholder="AM"
                   value="{{ old('wednesday_am') }}">
        @endif
        <span class="help-block">{{ $errors->first('wednesday_am') }}</span>
    </div>
    <div class="col-md-1 {{ $errors->first('wednesday_pm') ? 'has-error' : '' }}">
        @if(isset($model) && is_null(old('wednesday_pm')))
            <input type="text" id="wednesday_pm" name="wednesday_pm" class="form-control" placeholder="PM"
                   value="{{ $model->wednesday_pm }}">
        @else
            <input type="text" id="wednesday_pm" name="wednesday_pm" class="form-control" placeholder="PM"
                   value="{{ old('wednesday_pm') }}">
        @endif
        <span class="help-block">{{ $errors->first('wednesday_pm') }}</span>
    </div>
</div>

<div class="form-group">
    <label for="monday_am" class="control-label col-md-3">Jeudi:</label>
    <div class="col-md-1 {{ $errors->first('thursday_am') ? 'has-error' : '' }}">
        @if(isset($model) && is_null(old('thursday_am')))
            <input type="text" id="thursday_am" name="thursday_am" class="form-control" placeholder="AM"
                   value="{{ $model->thursday_am }}">
        @else
            <input type="text" id="thursday_am" name="thursday_am" class="form-control" placeholder="AM"
                   value="{{ old('thursday_am') }}">
        @endif
        <span class="help-block">{{ $errors->first('thursday_am') }}</span>
    </div>
    <div class="col-md-1 {{ $errors->first('thursday_pm') ? 'has-error' : '' }}">
        @if(isset($model) && is_null(old('thursday_pm')))
            <input type="text" id="thursday_pm" name="thursday_pm" class="form-control" placeholder="PM"
                   value="{{ $model->thursday_pm }}">
        @else
            <input type="text" id="thursday_pm" name="thursday_pm" class="form-control" placeholder="PM"
                   value="{{ old('thursday_pm') }}">
        @endif
        <span class="help-block">{{ $errors->first('thursday_pm') }}</span>
    </div>
</div>

<div class="form-group">
    <label for="monday_am" class="control-label col-md-3">Vendredi:</label>
    <div class="col-md-1 {{ $errors->first('friday_am') ? 'has-error' : '' }}">
        @if(isset($model) && is_null(old('friday_am')))
            <input type="text" id="friday_am" name="friday_am" class="form-control" placeholder="AM"
                   value="{{ $model->friday_am }}">
        @else
            <input type="text" id="friday_am" name="friday_am" class="form-control" placeholder="AM"
                   value="{{ old('friday_am') }}">
        @endif
        <span class="help-block">{{ $errors->first('friday_am') }}</span>
    </div>
    <div class="col-md-1 {{ $errors->first('friday_pm') ? 'has-error' : '' }}">
        @if(isset($model) && is_null(old('friday_pm')))
            <input type="text" id="friday_pm" name="friday_pm" class="form-control" placeholder="PM"
                   value="{{ $model->friday_pm }}">
        @else
            <input type="text" id="friday_pm" name="friday_pm" class="form-control" placeholder="PM"
                   value="{{ old('friday_pm') }}">
        @endif
        <span class="help-block">{{ $errors->first('friday_pm') }}</span>
    </div>
</div>