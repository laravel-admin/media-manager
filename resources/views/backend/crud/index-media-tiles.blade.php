@extends('crud::templates.index')

@section('records')

    <div class="panel panel-default">
        <div class="panel-body" style="padding-left:0;padding-right:0;padding-bottom:0;">
            @foreach ($records as $record)
                <div class="col-xs-4 col-md-3">
                    <a href="{{ route("{$route}edit", $record->id) }}" class="thumbnail" data-toggle="tooltip" data-placement="bottom" title="{{ $record->name }}">
                        <img src="{{ (substr($record->type, 0, 5) == 'image') ? $record->getThumbnailAttribute() : "http://placehold.it/150x150?text={$record->name}" }}" alt="{{ $record->name }}">
                        <div class="caption">
                            {{ $record->type }}
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        <div class="panel-footer">
            {{ $records->appends(Request::except('page'))->render() }}
        </div>
    </div>

@stop
