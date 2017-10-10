@extends('logbook.index')

@section('tab-content')
@include('logbook.info-cards.today')
@include('logbook.info-cards.this-week')
@endsection
