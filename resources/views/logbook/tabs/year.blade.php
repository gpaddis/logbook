@extends('logbook.index')

@section('tab-content')
<div class="col">

  <div class="row">
    <box-sum-sm value="{{ $days or 0 }}">
      <i class="fa fa-calendar" aria-hidden="true"></i>
      Days open
    </box-sum-sm>

    <box-sum-sm value="{{ array_sum($visitsByYear->first()) ?: 0 }}">
      <i class="fa fa-user" aria-hidden="true"></i>
      Visits
    </box-sum-sm>

    <box-avg-sm value="{{ array_sum($visitsByYear->first()) }}" number="{{ $days }}">
      <i class="fa fa-users" aria-hidden="true"></i>
      Visits / day
    </box-avg-sm>

    <div class="col-6 mb-2">
      <div class="card">
        <div class="card-body">
          <h3>User statistics: 2017</h3>

          <p class="card-text">
            Choose a year from the dropdown to display the relative statistics:
          </p>

          <select class="custom-select">
            <option selected>Select a year</option>
            @forelse($years as $year)
            <option value="{{ $year }}">{{ $year }}</option>
            @empty
            <option value="none">No data available</option>
            @endforelse
          </select>
        </div>
      </div>
    </div>
  </div>

  <hr>

  <div class="row">
    <div class="col-md-8">
      <chart type="bar"
      label="# of Users in 2017"
      :keys="{{ $visits->keys() }}"
      :values="{{ $visits->values() }}"
      background-color="rgba(255, 12, 2, 0.2)"
      border-color="rgba(254, 43, 132, 1)"></chart>
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
      name="#"
      :fields="['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']"
      :dataset1="{{ $visits }}"
      dataset1-name="2016"
      :dataset2="{{ $visits }}"
      dataset2-name="2017">
    </table-report>
  </div>
</div>

<hr>

<div class="row">
  <div class="col-md-8">
    <chart type="line"
    label="Students"
    :keys="{{ $visits->keys() }}"
    :values="{{ $visits->values() }}"
    background-color="rgba(255, 99, 132, 0.2)"
    border-color="rgba(255, 99, 132, 1)"></chart>
  </div>
  <div class="col">
    <h1>User groups</h1>
    <p>This graph will display the visits in the year selected with stacked lines corresponding to the different user groups.</p>
  </div>
</div>
</div>
@endsection
