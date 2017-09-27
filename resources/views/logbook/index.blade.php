@extends('layouts.app')

@section('content')

<div class="row">
  <div class="col">
    <div class="card">
      <div class="card-header">Logbook Index</div>

      <div class="card-body">
        <div class="row justify-content-left">
          @include('logbook.info-cards.today')
          @include('logbook.info-cards.this-week')
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="row">
      <div class="col">
        <div class="card mb-3">
          <div class="card-header">Update the logbook</div>
          <div class="card-body">
            <form class="form" method="GET" action="{{ route('logbook.update') }}">
              <div class="form-group">
                <label class="sr-only" for="date">Pick a date:</label>
                <input type="date" class="form-control" id="date" name="date" max="{{ Carbon\Carbon::now()->toDateString() }}" value="{{ Carbon\Carbon::now()->toDateString() }}">
              </div>
              <button type="submit" class="btn btn-primary">Show</button>
            </form>
          </div>
        </div>

        <div class="card">
          <div class="card-header">Browse the statistics</div>
          <div class="card-body">
            <ul>
              <li>Today</li>
              <li>This week</li>
              <li>This month</li>
              <li>This year</li>
            </ul>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

@endsection
