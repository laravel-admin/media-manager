@extends('crud::templates.index')

@section('records')

    <div class="panel panel-default">

        <table class="table table-striped table-hover table-heading">
            <thead>
                <tr>
                    @if($handle_bulk)<th width="25"><input type="checkbox" class="check-all" data-scope=".check-item"></th>@endif
                    @foreach ($fields->headings() as $heading) 
                        <th>{{ $heading['label'] }} @if($heading['id'])<a href="{{ $fields->getOrderLink($heading['id']) }}" class="fa fa-fw fa-sort text-muted"></a>@endif</th> 
                    @endforeach
                </tr>
            </thead>
            <tbody>
            @foreach ($records as $record)
            <tr data-href="{{ route("{$route}edit", $record->id) }}" @if(!is_null($record->is_active) && !$record->is_active)class="danger"@endif>
                @if($handle_bulk)<td><input type="checkbox" name="record[{!! $record->id !!}]" value="{!! $record->id !!}" class="check-item"></td>@endif
                @foreach ($fields->values($record) as $item) <td> {{ $item }} </td> @endforeach
            </tr>
            @endforeach
            </tbody>
        </table>

        <div class="panel-footer">
            {{ $records->appends(Request::except('page'))->render() }}
        </div>

    </div>

@stop
