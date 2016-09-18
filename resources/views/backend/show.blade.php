@extends('admin.layout')

@section('content')

{!! Form::open(['url'=>route('admin.docs.update', $doc->id), 'method'=>'PUT', 'enctype'=>'multipart/form-data']) !!}

<div class="page-header">
	
	<span class="pull-right">
		<a href="{!! $doc->url !!}" target="_blank" class="btn btn-primary">Download</a>
	</span>
	
	<h1>{!! $doc->name !!}</h1>
</div>

<div class="row">	

	@include('admin.docs._nav_item')


	<div class="col-md-9">			
		<div class="row">
			
			<div class="panel panel-default">
				<div class="panel-heading"><h3 class="panel-title">Informatie</h3></div>
				<table class="table">
					<tr>
						<td>Naam</td>
						<td>{!! $doc->name !!}</td>
					</tr>
					<tr>
						<td>Grootte</td>
						<td>{!! $doc->sizeFormatted !!}</td>
					</tr>
					<tr>
						<td>Type</td>
						<td>{!! $doc->type !!}</td>
					</tr>
					<tr>
						<td>Disk</td>
						<td>{!! $doc->storage !!}</td>
					</tr>
					<tr>
						<td>Source</td>
						<td>{!! $doc->source !!}</td>
					</tr>
					<tr>
						<td>Aangemaakt</td>
						<td>{!! $doc->created_at->format('d-m-Y H:i') !!}</td>
					</tr>
					<tr>
						<td>Laatst gewijzigd</td>
						<td>{!! $doc->updated_at->format('d-m-Y H:i') !!}</td>
					</tr>
				</table>
			</div>
			
			<div class="panel panel-default">
			    <div class="panel-heading"><h3 class="panel-title">Crops</h3></div>
			    <table class="table">
				    @foreach (config('docs.crops') as $name=>$crop)
				    <tr>
					    <td>{{ $name }}</td>
					    <td>@if ($doc->cropExists($name)) <a href="{!! $doc->getCropUrl($name) !!}" target="_blank">{!! $doc->getCropPath($name) !!}</a> @else Auto @endif</td>
				    </tr>
				    @endforeach
			    </table>
			</div>
			
			
			<div class="panel panel-default">
			    <div class="panel-heading"><h3 class="panel-title">Vervang</h3></div>
			    <div class="panel-body">
				    <input type="file" class="form-control" name="replace">
			    </div>
			    <div class="panel-footer">
				    <button type="submit" class="btn btn-primary">Uploaden</button>				    
			    </div>
			</div>
			
		</div>
		
	</div>
   
</div>

{!! Form::close() !!}

@endsection