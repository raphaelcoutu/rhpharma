@isset($user)
<form action="{{ route('users.update', $user->id) }}" method="POST" class="form form-horizontal">
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="id" value="{{ $user->id }}">
@else
<form action="{{ route('users.store') }}" method="POST" class="form form-horizontal">
@endisset
    {{ csrf_field() }}
    <div class="form-group">
        <label for="firstname" class="col-md-3 control-label">Prénom:</label>
        <div class="col-md-3 {{ $errors->first('firstname') ? 'has-error' : '' }}">
            <input type="text" id="firstname" name="firstname" class="form-control" value="{{ old('firstname', isset($user) ? $user->firstname : '') }}">
            <span class="help-block">{{ $errors->first('firstname') }}</span>
        </div>

        <label for="lastname" class="col-md-3 control-label">Nom:</label>
        <div class="col-md-3 {{ $errors->first('lastname') ? 'has-error' : '' }}">
            <input type="text" id="lastname" name="lastname" class="form-control" value="{{  old('lastname', isset($user) ? $user->lastname : '') }}">
            <span class="help-block">{{ $errors->first('lastname') }}</span>
        </div>
    </div>

    <div class="form-group">
        <label for="email" class="col-md-3 control-label">Email:</label>
        <div class="col-md-3 {{ $errors->first('email') ? 'has-error' : '' }}">
            <input type="email" id="email" name="email" class="form-control" value="{{ old('email', isset($user) ? $user->email : '') }}">
            <span class="help-block">{{ $errors->first('email') }}</span>
        </div>

        <label for="branch_id" class="col-md-3 control-label">Branche:</label>
        <div class="col-md-3 {{ $errors->first('branch_id') ? 'has-error' : '' }}">
            <select id="branch_id" name="branch_id" class="form-control">
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" {{ old('branch_id', isset($user) ? $user->branch_id : '') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                @endforeach
            </select>
            <span class="help-block">{{ $errors->first('workdays_per_week') }}</span>
        </div>

    </div>
    <div class="form-group">
        <label for="workdays_per_week" class="col-md-3 control-label">Jours de travail par semaine:</label>
        <div class="col-md-3 {{ $errors->first('workdays_per_week') ? 'has-error' : '' }}">
            <select id="workdays_per_week" name="workdays_per_week" class="form-control">
                <option value="5" {{ old('workdays_per_week', isset($user) ? $user->workdays_per_week : '') == '5' ? 'selected' : '' }}>5 jours</option>
                <option value="4" {{ old('workdays_per_week', isset($user) ? $user->workdays_per_week : '') == '4' ? 'selected' : '' }}>4 jours</option>
                <option value="3" {{ old('workdays_per_week', isset($user) ? $user->workdays_per_week : '') == '3' ? 'selected' : '' }}>3 jours</option>
                <option value="2" {{ old('workdays_per_week', isset($user) ? $user->workdays_per_week : '') == '2' ? 'selected' : '' }}>2 jours</option>
                <option value="1" {{ old('workdays_per_week', isset($user) ? $user->workdays_per_week : '') == '1' ? 'selected' : '' }}>1 jour</option>
            </select>
            <span class="help-block">{{ $errors->first('workdays_per_week') }}</span>
        </div>

        <label for="seniority" class="col-md-3 control-label">Ancienneté:</label>
        <div class="col-md-3 {{ $errors->first('seniority') ? 'has-error' : '' }}">
            <input type="text" id="seniority" name="seniority" class="form-control" value="{{ old('seniority', isset($user) ? $user->seniority : '') }}">
            <span class="help-block">{{ $errors->first('seniority') }}</span>
        </div>
    </div>

    <div class="form-group">
        <label for="is_active" class="col-md-3 control-label">Actif:</label>
        <div class="col-md-3 {{ $errors->first('is_active') ? 'has-error' : '' }}">
            <label class="radio-inline">
                <input type="radio" name="is_active" value="1" {{ old('is_active', isset($user) ? $user->is_active : '') == '1' ? 'checked' : '' }}>Oui
            </label>
            <label class="radio-inline">
                <input type="radio" name="is_active" value="0" {{ old('is_active', isset($user) ? $user->is_active : '') == '0' ? 'checked' : '' }}>Non
            </label>
            <span class="help-block">{{ $errors->first('is_active') }}</span>
        </div>

        <label for="azure_id" class="col-md-3 control-label">ID Azure:</label>
        <div class="col-md-3 {{ $errors->first('azure_id') ? 'has-error' : '' }}">
            <input type="text" id="azure_id" name="azure_id" class="form-control" value="{{ old('azure_id', isset($user) ? $user->azure_id : '') }}">
            <span class="help-block">{{ $errors->first('azure_id') }}</span>
        </div>
    </div>

    <h3>Roles</h3>

    <div class="form-group">
        <div class="col-md-3">
            @foreach($roles as $role)
            <div>
                <label>
                    <input type="hidden" name="roles[{{ $role->id }}]" value="0">
                    <input type="checkbox"
                    name="roles[{{ $role->id }}]"
                    value="1"
                    {{ old('roles.'.$role->id, isset($user) ? $user->roles->contains('id', $role->id) : '') ? 'checked' : '' }}>
                    {{ $role->name }}

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
