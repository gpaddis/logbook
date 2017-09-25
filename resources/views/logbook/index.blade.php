@extends('layouts.app')

@section('content')

<div class="row">
  <div class="col">
    <div class="card">
      <div class="card-header">Logbook Index</div>

      <div class="card-body">
        <form class="form-inline" method="GET" action="{{ route('logbook.update') }}">
          <div class="form-group">
            <label class="sr-only" for="date">Pick a date:</label>
            <input type="date" class="form-control" id="date" name="date" max="{{ Carbon\Carbon::now()->toDateString() }}" value="{{ Carbon\Carbon::now()->toDateString() }}">
          </div>
          <button type="submit" class="btn btn-primary">Update the Logbook</button>
        </form>

        <ul>
          @foreach($entries as $entry)
          <li>
            <p>On {{ $entry->visited_at }} we had one {{ $entry->patronCategory->name }}.</p>
          </li>
          @endforeach
        </ul>
      </div>
    </div>

  </div>
</div>

@endsection
