@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
  <div class="col">
    <div class="card">
      <div class="card-header">Live Counter Index</div>
      <div class="card-body">
        <category-cards
          :patron-categories="{{ $patronCategories }}"
        ></category-cards>
      </div>
    </div>
  </div>
</div>

@endsection
