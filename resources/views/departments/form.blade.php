<form action="{{ route('departments.store') }}" method="POST" class="form form-horizontal">
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
        <div class="col-md-3 {{ $errors->first('code') ? 'has-error' : '' }}">
            <input type="text" id="code" name="code" class="form-control" value="{{ old('code') }}">
            <span class="help-block">{{ $errors->first('code') }}</span>
        </div>

        <label for="workplace_id" class="col-md-3 control-label">Lieu de travail:</label>
        <div class="col-md-3 {{ $errors->first('workplace_id') ? 'has-error' : '' }}">
            <select id="workplace_id" name="workplace_id" class="form-control">
                @foreach ($workplaces as $wp)
                    <option value="{{ $wp->id }}" {{ old('workplace_id') == $wp->id ? 'selected' : '' }}>{{$wp->name}} ({{ $wp->code }})</option>
                @endforeach
            </select> 
            <span class="help-block">{{ $errors->first('workplace_id') }}</span>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-3 pull-right">
            <input type="submit" class="btn btn-primary" value="Enregistrer">
        </div>
    </div>
</form>