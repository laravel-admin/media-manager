@extends(config('media.views.layout'))

@section(config('media.views.section'))

<form method="POST" action="{!! route(config('media.routes.backend.name').'update', [$media->id]) !!}" enctype="multipart/form-data">
	{!! csrf_field() !!}
	{!! method_field('put') !!}

	<div class="page-header">
		<div class="pull-right">
			<a href="{!! route(config('media.routes.backend.name').'index') !!}" class="btn btn-default">Back</a>
			<a href="{!! $media->url !!}" target="_blank" class="btn btn-primary">Show</a>
		</div>
		<h1>{!! $media->name !!}</h1>
	</div>

	<div class="row">

		<div class="col-xs-12">

			<div class="panel panel-default">
				<div class="panel-heading"><h3 class="panel-title">{!! $media->name !!}</h3></div>

				<div class="panel-body">

				  	<div class="row form-group">
				    	<label for="name" class="col-sm-3 control-label">Name</label>
						<div class="col-sm-9">
							<input type="text" name="name" value="{!! old('name', $media->name) !!}" class="form-control" />
						</div>
				  	</div>

				  	<div class="row form-group">
				    	<label for="replace" class="col-sm-3 control-label">Replace with</label>
						<div class="col-sm-9">
							<input type="file" name="replace" class="form-control" />
						</div>
				  	</div>

				</div>

				<div class="panel-footer">
					<button type="submit" class="btn btn-primary">Save</button>
				</div>

			</div>

		</div>

	</div>

</form>

@endsection
