@isset($role)
<form action="{{ route('roles.update', $role->id) }}" method="POST" class="form form-horizontal">
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="id" value="{{ $role->id }}">
@else
<form action="{{ route('roles.store') }}" method="POST" class="form form-horizontal">
@endisset
    {{ csrf_field() }}

    <div class="form-group">
        <label for="name" class="col-md-3 control-label">Nom de l'horaire:</label>
        <div class="col-md-3 {{ $errors->first('name') ? 'has-error' : '' }}">
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($role) ? $role->name : '') }}">
            <span class="help-block">{{ $errors->first('name') }}</span>
        </div>
    </div>
    <div class="form-group">
        <label for="description" class="col-md-3 control-label">Description:</label>
        <div class="col-md-9 {{ $errors->first('description') ? 'has-error' : '' }}">
            <input type="text" id="description" name="description" class="form-control"
                   value="{{ old('description', isset($role) ? $role->description : '') }}">
            <span class="help-block">{{ $errors->first('description') }}</span>
        </div>
    </div>

    <div class="form-group">
        <label for="permissions" class="col-md-3 control-label">Permissions:</label>
        <div class="permissions">
            @foreach($permissions as $permission)
            <div>
                <label>
                    <input type="hidden" name="permissions[{{ $permission->code }}]" value="0">
                    <input type="checkbox"
                    name="permissions[{{ $permission->code }}]"
                    value="1"
                    {{ $role->permissions->contains('code', $permission->code) ? 'checked' : '' }}>
                    {{ $permission->code }}

                </label>
            </div>
            @endforeach
            </div>
    </div>

    <div class="form-group">
        <div class="col-md-3 pull-right">
            <input type="submit" class="btn btn-primary" value="Enregistrer">
        </div>
    </div>

</form>
