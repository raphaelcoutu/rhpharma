@if(isset($model))
<form action="{{ route('shifts.update', $model->id) }}" method="POST" class="form form-horizontal">
    <input type="hidden" name="id" value="{{ $model->id }}">
@else
<form action="{{ route('shifts.store') }}" method="POST" class="form form-horizontal">
@endif
    {{ csrf_field() }}

    <div class="form-group">
        <label for="name" class="col-md-3 control-label">Code:</label>
        <div class="col-md-9 {{ $errors->first('code') ? 'has-error' : '' }}">
        @if(isset($model) && is_null(old('code')))
            <input type="text" id="name" name="code" class="form-control" value="{{ $model->code }}">
        @else
            <input type="text" id="name" name="code" class="form-control" value="{{ old('code') }}">
        @endif
            <span class="help-block">{{ $errors->first('code') }}</span>
        </div>
    </div>

    <div class="form-group">
        <label for="department_id" class="col-md-3 control-label">Départment:</label>
        <div class="col-md-3 {{ $errors->first('department_id') ? 'has-error' : '' }}">
            <select id="department_id" name="department_id" class="form-control">
                @if(isset($model) && is_null(old('department_id')))
                    @foreach ($departments->sortBy('name') as $department)
                        <option value="{{ $department->id }}" {{ $model->department_id == $department->id ? 'selected' : '' }}>
                            {{$department->name}}
                        </option>
                    @endforeach
                @else
                    @foreach ($departments->sortBy('name') as $department)
                        <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                            {{$department->name}}
                        </option>
                    @endforeach
                @endif
            </select>
            <span class="help-block">{{ $errors->first('department_id') }}</span>
        </div>
    </div>

    <div class="form-group">
        <label for="shift_type_id" class="col-md-3 control-label">Type de shift:</label>
        <div class="col-md-3 {{ $errors->first('shift_type_id') ? 'has-error' : '' }}">
            <select id="shift_type_id" name="shift_type_id" class="form-control">
                @if(isset($model) && is_null(old('shift_type_id')))
                    @foreach ($shiftTypes->sortBy('name') as $shiftType)
                        <option value="{{ $shiftType->id }}" {{ $model->shift_type_id == $shiftType->id ? 'selected' : '' }}>
                            {{$shiftType->name}}
                        </option>
                    @endforeach
                @else
                    @foreach ($shiftTypes->sortBy('name') as $shiftType)
                        <option value="{{ $shiftType->id }}" {{ old('shift_type_id') == $shiftType->id ? 'selected' : '' }}>
                            {{$shiftType->name}}
                        </option>
                    @endforeach
                @endif
            </select>
            <span class="help-block">{{ $errors->first('shift_type_id') }}</span>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-3 pull-right">
            <input type="submit" class="btn btn-primary" value="{{ isset($model) ? 'Modifier' : 'Enregistrer' }}">
        </div>
    </div>
</form>
