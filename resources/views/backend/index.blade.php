@extends(config('media.views.layout'))
@section(config('media.views.section'))

<form method="POST" action="{!! route(config('media.routes.backend.name').'store') !!}">
	{!! csrf_field() !!}

	<div class="page-header">
		<div class="pull-right">
			<a href="{!! route(config('media.routes.backend.name').'create') !!}" class="btn btn-primary">Add</a>
		</div>
		<h1>Media: {!! $media->total() !!} items found <small>page {!! $media->currentPage() !!} of {!! $media->lastPage() !!}</small></h1>
	</div>

	<div class="row">

		<div class="col-xs-12">

		<div class="panel panel-default">

			@if ($media->count() > 0)
			<table class="table table-striped table-hover table-heading table-popover">
				<thead>
					<tr>
						<th><input type="checkbox" class="check-all" data-scope=".check"></th>
						<th>name</th>
						<th>size</th>
						<th>date</th>
						<th width="25">&nbsp;</th>
					</tr>
				</thead>
				<tbody> @foreach ($media as $item)
					<tr data-link="{!! route(config('media.routes.backend.name').'edit',[$item->id]) !!}" @title="Preview" data-html="true" data-container="body" data-toggle="popover" data-placement="bottom" data-content="<img src='{!! $item->thumbnail !!}'>" >
						<td><input type="checkbox" name="items[{!! $item->id !!}]" value="{!! $item->id !!}" class="check"></td>
						<td>{!! $item->name !!}</td>
						<td>{!! $item->sizeFormatted !!}</td>
						<td>{!! $item->created_at->format('d-m-Y') !!}</td>
						<td>
							<form method="POST" action="{!! route(config('media.routes.backend.name').'destroy', $item->id) !!}">
								{!! csrf_field() !!}
								{!! method_field('delete') !!}
								<button data-method="delete"><span class="glyphicon glyphicon-remove text-danger"></span></button>
							</form>
						</td>
					</tr>
				@endforeach </tbody>
			</table>

			<div class="panel-footer">

				<button type="submit" class="btn btn-danger" data-confirm="Weet u zeker dat u de geselecteerde bestanden wilt verwijderen?">Delete</button>

			</div>

			@endif


		</div>


					<div class="text-center">
							{!! $media->appends(Request::except('page'))->render() !!}
					</div>
	</div>

</div>

</form>

@endsection
