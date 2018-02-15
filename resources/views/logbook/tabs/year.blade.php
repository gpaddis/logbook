@extends('logbook.index')

@section('tab-content')
<div class="col">
    <div class="row mb-4">
        <div class="col col-lg-4">
            <chart-selector :years-available="{{ $yearsAvailable }}"></chart-selector>
        </div>

        <div class="col">
            <bar-chart></bar-chart>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <table-report></table-report>
        </div>
    </div>
</div>
@endsection
