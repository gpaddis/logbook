@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
  <div class="col">
    <div class="card">
      <div class="card-header">Live Counter Index</div>
      <div class="card-body">
        @if($patronCategories->isNotEmpty())
        <category-cards
          :patron-categories="{{ $patronCategories }}"
          :initial-count="{{ $initialCount }}"
        ></category-cards>
        @else
        @include('layouts.partials.no-patron-categories')
        @endif
      </div>
    </div>
  </div>
</div>

@endsection
