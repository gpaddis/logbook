@extends('layouts.app')

@section('content')

<div class="row">
  <div class="col">
    <div class="card">
      <div class="card-header">Logbook Index</div>

      <div class="card-body">
        <!-- Tabs start -->
        <ul class="nav nav-tabs mb-2">
          <li class="nav-item">
            <a class="nav-link active" href="{{ route('logbook.index') }}">Overview</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('logbook.day') }}">Day</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Week</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Month</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Year</a>
          </li>
        </ul>
        <!-- Tabs end -->

        <div class="row justify-content-left">
          @yield('tab-content')
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
