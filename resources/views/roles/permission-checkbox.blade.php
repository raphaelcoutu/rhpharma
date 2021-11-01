<label><input type="checkbox" disabled="disabled" value="{{ $permission->id }}"
            {{ $role->permissions->contains('code', $permission->code) ? 'checked' : '' }}
    > {{ $permission->code }}
</label>
