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
                  @forelse($yearsAvailable as $year)
                  <option value="{{ $year }}" {{ $year == $year ? 'selected' : '' }}>{{ $year }}</option>
                  @empty
                  <option value="none">No data available</option>
                  @endforelse
                </select>
              </div>

              <div class="col-auto">
                <select name="y2" class="custom-select">
                  @forelse($yearsAvailable as $year)
                  <option value="{{ $year }}" {{ $year == $year - 1 ? 'selected' : '' }}>{{ $year }}</option>
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
      label="# of Users in 2017"
      :values="{{ $visitsByYear }}"></chart-month>
    </div>

    <div class="col">
      <h1>Visits by month</h1>
      <p>There will be two datasets, for comparison: one for the current year, one for the previous.</p>
    </div>
  </div>

  <!-- Table: visits per month / year -->
  <div class="row">
    <div class="col">
      <p>The data in detail:</p>
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
      <h1>User groups</h1>
      <p>This graph will display the visits in the year selected with stacked lines corresponding to the different user groups.</p>
    </div>
  </div>
</div>
@endsection
