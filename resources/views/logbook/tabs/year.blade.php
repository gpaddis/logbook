@extends('logbook.index')

@section('tab-content')
<chart-selector :years-available="{{ $yearsAvailable }}"></chart-selector>
@endsection
