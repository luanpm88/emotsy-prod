<div class="row">
	<div class="col-md-6">
		<div class="sub_section">
            <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                <label class="form-label">{{ trans('messages.role.name') }} <span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{ $role->name }}" class="form-control">
                @if ($errors->has('name'))
                    <span class="help-block">
                        {{ $errors->first('name') }}
                    </span>
                @endif
            </div>
		</div>

        <h3>{{ trans('messages.permissions') }}</h3>
        <div class="sub_section">
            
            <h5>{{ trans('messages.permission.account') }}</h5>
            @foreach (config('permissions.account') as $permission)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission }}" id="{{ $permission }}" {{ $role->hasPermission($permission) ? 'checked' : '' }}>
                    <label class="form-check-label" for="{{ $permission }}">
                    {{ $permission }}
                    </label>
                </div>
            @endforeach
        </div>
        <div class="sub_section">

            <h5>{{ trans('messages.permission.system') }}</h5>
            @foreach (config('permissions.system') as $permission)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission }}" id="{{ $permission }}" {{ $role->hasPermission($permission) ? 'checked' : '' }}>
                    <label class="form-check-label" for="{{ $permission }}">
                    {{ $permission }}
                    </label>
                </div>
            @endforeach
		</div>
		<div class="text-left">
			<button class="btn btn-primary me-1"><i class="icon-check"></i> {{ trans('messages.save') }}</button>
			<a href="{{ action("Admin\RoleController@index") }}" class="btn btn-secondary"><i class="icon-cross"></i> {{ trans('messages.cancel') }}</a>
		</div>
	</div>
</div>
