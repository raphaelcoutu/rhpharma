<label><input type="checkbox" disabled="disabled" value="{{ $permission->id }}"
            {{ $role->permissions->contains('id', $permission->id) ? 'checked' : '' }}
    > {{ $permission->name }}
</label>