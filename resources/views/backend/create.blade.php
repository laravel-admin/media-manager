@extends(config('media.views.layout'))

@section(config('media.views.section'))

<form method="POST" action="{!! route(config('media.routes.backend.name').'store') !!}" enctype="multipart/form-data">
	{!! csrf_field() !!}

	<div class="page-header">
		<div class="pull-right">
			<a href="{!! route(config('media.routes.backend.name').'index') !!}" class="btn btn-default">Back</a>
		</div>
		<h1>Add media</h1>
	</div>

	<div class="row">

		<div class="col-xs-12">

			<div class="panel panel-default">
				<div class="panel-heading"><h3 class="panel-title">Upload file</h3></div>

				<div class="panel-body">

				  	<div class="row form-group">
				    	<label for="replace" class="col-sm-3 control-label">File</label>
						<div class="col-sm-9">
							<input type="file" name="file" class="form-control" />
						</div>
				  	</div>

				</div>

				<div class="panel-footer">
					<button type="submit" class="btn btn-primary">Upload</button>
				</div>

			</div>

		</div>

	</div>

</form>

@endsection
