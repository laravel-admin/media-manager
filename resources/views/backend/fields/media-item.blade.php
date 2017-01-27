<div class="form-group">
	<label for="{{ $field->id() }}" class="col-sm-3 control-label">{{ $field->label() }}</label>
	<div class="col-sm-9">
		<media-item name="{{ $field->id() }}" :item="{!! $field->format($model) ?: 'null' !!}"></media-item>
	</div>
</div>
