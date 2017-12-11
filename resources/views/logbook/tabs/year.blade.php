@extends('logbook.index')

@section('tab-content')
<chart-selector :years-available="{{ $yearsAvailable }}"></chart-selector>
<bar-chart></bar-chart>
@endsection
