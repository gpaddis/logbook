@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3>Logbook Index</h3>
                    {{-- TODO: implement the functionality. --}}
                    <form class="form-inline" method="GET" action="{{ route('logbook.create') }}">
                        <div class="form-group">
                            <label class="sr-only" for="date">Pick a date:</label>
                            <input type="date" class="form-control" id="date" name="date" max="{{ Carbon\Carbon::now()->toDateString() }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Update the Logbook</button>
                    </form>

                    <ul>
                        @foreach($entries as $entry)
                        <li>
                            <p>From {{ $entry->start_time }} to {{ $entry->end_time }} we had {{ $entry->count }} {{ $entry->patronCategory->name }}.</p>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
