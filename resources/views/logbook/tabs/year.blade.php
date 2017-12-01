@extends('logbook.index')

@section('tab-content')
<example></example>
{{--  <year-view inline-template>
<div class="col" v-cloak>
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

      <selector-year :years-available={{ $yearsAvailable }}>
      </selector-year>

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
      <p>In {{ $year }}, most of the users who visited the library were <strong>{{ $visitsByPatronCategory->sort()->reverse()->keys()->first() }}</strong> ({{ $visitsByPatronCategory->max() }} visits of {{ $visitsByYear->first() != null ? array_sum($visitsByYear->sort()->first()) : 0 }} in total). </p>
      @if($visitsByPatronCategory->isNotEmpty())
      <p><strong>Todo:</strong> display a comparison between this year and last year (only if a second year is available).</p>
      @endif
    </div>
  </div>
</div>
</year-view>  --}}
@endsection
