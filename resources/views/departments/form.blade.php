@if(isset($model))
<form action="{{ route('departments.update', $model->id) }}" method="POST" class="form form-horizontal">
    <input type="hidden" name="id" value="{{ $model->id }}">
@else
<form action="{{ route('departments.store') }}" method="POST" class="form form-horizontal">
@endif
    {{ csrf_field() }}

    <h3>Paramètres généraux</h3>

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
        <label for="description" class="col-md-3 control-label">Description:</label>
        <div class="col-md-9 {{ $errors->first('description') ? 'has-error' : '' }}">
        @if(isset($model) && is_null(old('description')))
            <input type="text" id="description" name="description" class="form-control" value="{{ $model->description }}">
        @else
            <input type="text" id="description" name="description" class="form-control" value="{{ old('description') }}">
        @endif
            <span class="help-block">{{ $errors->first('description') }}</span>
        </div>
    </div>

    <div class="form-group">
        <label for="code" class="col-md-3 control-label">Code:</label>
        <div class="col-md-3 {{ $errors->first('code') ? 'has-error' : '' }}">
        @if(isset($model) && is_null(old('name')))
            <input type="text" id="code" name="code" class="form-control" value="{{ $model->code }}">
        @else
            <input type="text" id="code" name="code" class="form-control" value="{{ old('code') }}">
        @endif
            <span class="help-block">{{ $errors->first('code') }}</span>
        </div>

        <label for="workplace_id" class="col-md-3 control-label">Lieu de travail:</label>
        <div class="col-md-3 {{ $errors->first('workplace_id') ? 'has-error' : '' }}">
            <select id="workplace_id" name="workplace_id" class="form-control">
                @if(isset($model) && is_null(old('workplace_id')))
                    @foreach ($workplaces as $wp)
                    <option value="{{ $wp->id }}" {{ $model->workplace_id == $wp->id ? 'selected' : '' }}>
                        {{$wp->name}} ({{ $wp->code }})
                    </option>
                    @endforeach
                @else
                    @foreach ($workplaces as $wp)
                    <option value="{{ $wp->id }}" {{ old('workplace_id') == $wp->id ? 'selected' : '' }}>
                        {{$wp->name}} ({{ $wp->code }})
                    </option>
                    @endforeach
                @endif
            </select> 
            <span class="help-block">{{ $errors->first('workplace_id') }}</span>
        </div>
    </div>

    @include('departments.form-score')

    <div class="form-group">
        <div class="col-md-3 pull-right">
            <input type="submit" class="btn btn-primary" value="{{ isset($model) ? 'Modifier' : 'Enregistrer' }}">
        </div>
    </div>
</form>
