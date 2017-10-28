@extends('logbook.index')

@section('tab-content')
<div class="col">
  <div class="row">
    <box-sum-sm value="{{ $openingDays or 0 }}">
      <i class="fa fa-calendar" aria-hidden="true"></i>
      Days open
    </box-sum-sm>

    <box-sum-sm value="{{ $visitsByYear->first() != null ? array_sum($visitsByYear->first()) : 0 }}">
      <i class="fa fa-user" aria-hidden="true"></i>
      Visits
    </box-sum-sm>

    <box-avg-sm value="{{ $visitsByYear->first() != null ? array_sum($visitsByYear->first()) : 0 }}" number="{{ $openingDays }}">
      <i class="fa fa-users" aria-hidden="true"></i>
      Visits / day
    </box-avg-sm>

    <div class="col-6 mb-2">
      <div class="card">
        <div class="card-body">
          <h3>User statistics: {{ $year }}</h3>

          <p class="card-text">
            Choose two years to compare:
          </p>
          <form method="get" action="{{ route('logbook.year') }}">
            <div class="form-row align-items-center">
              <div class="col-auto">
                <select name="y1" class="custom-select">
                  @forelse($yearsAvailable as $value)
                  <option value="{{ $value }}" {{ $year == $value ? 'selected' : '' }}>{{ $value }}</option>
                  @empty
                  <option value="none">No data available</option>
                  @endforelse
                </select>
              </div>

              <div class="col-auto">
                <select name="y2" class="custom-select">
                  @forelse($yearsAvailable as $value)
                  <option value="{{ $value }}" {{ $value == $year - 1 ? 'selected' : '' }}>{{ $value }}</option>
                  @empty
                  <option value="none">No data available</option>
                  @endforelse
                </select>
              </div>

              <div class="col-auto">
                <button class="btn btn-primary" type="submit">Update</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <hr>

  <div class="row">
    <div class="col-md-8">
      <chart-month type="bar"
      :values="{{ $visitsByYear }}"></chart-month>
    </div>

    <div class="col">
      <h1>Visits by month</h1>
      <p>There will be two datasets, for comparison: one for the current year, one for the previous.</p>
    </div>
  </div>

  <!-- Table: visits per month / year -->
  <div class="row mt-4">
    <div class="col">
      <table-report
      :values="{{ $visitsByYear }}"></table-report>
    </div>
  </div>

  <hr>

  <div class="row">
    <div class="col-md-8">
      <chart-categories
      :keys="{{ $visitsByPatronCategory->keys() }}"
      :values="{{ $visitsByPatronCategory->values() }}"></chart-categories>
    </div>

    <div class="col">
      <h1>Patron categories</h1>
      <p>In {{ $year }}, most of the users who visited the library were <strong>{{ $visitsByPatronCategory->sort()->reverse()->keys()->first() }}</strong> ({{ $visitsByPatronCategory->max() }} visits of {{ $visitsByYear->first() != null ? array_sum($visitsByYear->first()) : 0 }} in total). </p>
      <p>This graph shows the visits in the year selected with each segment of the doughnut chart corresponding to a different user group.</p>
    </div>
  </div>
</div>
@endsection
