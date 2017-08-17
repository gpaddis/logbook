@extends('layouts.app')

@section('content')

<div class="row">
  <div class="col">
    <div class="card">
      <div class="card-header">Logbook Index</div>

      <div class="card-body">
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
            <p>From {{ $entry->start_time }} to {{ $entry->end_time }} we had {{ $entry->visits_count }} {{ $entry->patron_category->name }}.</p>
          </li>
          @endforeach
        </ul>
      </div>
    </div>

  </div>
</div>

@endsection
