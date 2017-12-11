@extends('logbook.index')

@section('tab-content')
<div class="col col-4">
    <chart-selector :years-available="{{ $yearsAvailable }}"></chart-selector>
</div>

<div class="col">
    <bar-chart></bar-chart>
</div>
@endsection
