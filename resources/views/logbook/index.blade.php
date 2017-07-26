@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3>Logbook Index</h3>
                    <ul>
                    @foreach($entries as $entry)
                    <li>
                        {{ $entry->start_time }},
                        {{ $entry->end_time }},
                        {{ $entry->patronCategory->name }} = 
                        {{ $entry->count }}
                    </li>
                    @endforeach
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
