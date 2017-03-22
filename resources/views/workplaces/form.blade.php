<form action="{{ route('workplaces.store') }}" method="POST" class="form form-horizontal">
    {{ csrf_field() }}

    <div class="form-group">
        <label for="name" class="col-md-3 control-label">Nom du lieu:</label>
        <div class="col-md-9 {{ $errors->first('name') ? 'has-error' : '' }}">
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}">
            <span class="help-block">{{ $errors->first('name') }}</span>
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
        <label for="address" class="col-md-3 control-label">Adresse:</label>
        <div class="col-md-3 {{ $errors->first('address') ? 'has-error' : '' }}">
            <input type="text" id="address" name="address" class="form-control" value="{{ old('address') }}">
            <span class="help-block">{{ $errors->first('address') }}</span>
        </div>

        <label for="city" class="col-md-3 control-label">Ville:</label>
        <div class="col-md-3 {{ $errors->first('city') ? 'has-error' : '' }}">
            <input type="text" id="city" name="city" class="form-control" value="{{ old('city') }}">
            <span class="help-block">{{ $errors->first('city') }}</span>
        </div>
    </div>

    <div class="form-group">
        <label for="province" class="col-md-3 control-label">Province:</label>
        <div class="col-md-3 {{ $errors->first('province') ? 'has-error' : '' }}">
            <input type="text" id="province" name="province" class="form-control" value="{{ old('province') }}">
            <span class="help-block">{{ $errors->first('province') }}</span>
        </div>

        <label for="country" class="col-md-3 control-label">Pays:</label>
        <div class="col-md-3 {{ $errors->first('country') ? 'has-error' : '' }}">
            <input type="text" id="country" name="country" class="form-control" value="{{ old('country') }}">
            <span class="help-block">{{ $errors->first('country') }}</span>
        </div>
    </div>

    <div class="form-group">
        <label for="postal_code" class="col-md-3 control-label">Code postal:</label>
        <div class="col-md-3 {{ $errors->first('postal_code') ? 'has-error' : '' }}">
            <input type="text" id="postal_code" name="postal_code" class="form-control" value="{{ old('postal_code') }}">
            <span class="help-block">{{ $errors->first('postal_code') }}</span>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-3 pull-right">
            <input type="submit" class="btn btn-primary" value="Enregistrer">
        </div>
    </div>
    
</form>