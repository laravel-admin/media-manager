<div class="form-group">
	<label for="{{ $field->id() }}" class="col-sm-3 control-label">{{ $field->label() }}</label>
	<div class="col-sm-9">
		<media-item name="{{ $field->id() }}" :item="{{ $field->format($model) ?: 'null' }}" controller="{{ route('admin.media-manager.ajax.index') }}"></media-item>
	</div>
</div>
