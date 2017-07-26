@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3>Logbook Index</h3>
                    <div class="container">
                        <a href="{{ route('logbook.create') }}" class="btn btn-primary">Create a New Entry</a>
                    </div>
                    <ul>
                        @foreach($entries as $entry)
                        <li>
                            From {{ $entry->start_time }} to {{ $entry->end_time }} we had {{ $entry->count }} {{ $entry->patronCategory->name }}.
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
