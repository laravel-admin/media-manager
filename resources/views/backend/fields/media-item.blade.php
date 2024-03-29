<div class="form-group">
	<label for="{{ $field->id() }}" class="col-sm-3 control-label">{{ $field->label() }}</label>
	<div class="col-sm-9">
        @if(old($field->id()))
            @php
            $current_id = $field->id();
            $model->$current_id = old($field->id());
            @endphp
        @endif

        @if($field->disabled())
            @if($field->format($model))
                <img src="{{ $field->format($model)->source }}">
                {{ $field->format($model)->name }}
            @endif
        @else
            <media-item name="{{ $field->id() }}" filetypes="{{ $field->filetypes() }}" :item="{{ $field->format($model) ?: 'null' }}" controller="{{ route('admin.media-manager.ajax.index') }}"></media-item>
            @if($field->description())
                <p style="padding-top:6px;">{{ $field->description() }}</p>
            @endif
        @endif
    </div>
</div>
