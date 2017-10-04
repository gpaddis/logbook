@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
  <div class="col">
    <div class="card">
      <div class="card-header">Live Counter Index</div>
      <div class="card-body">
        <!-- Start categories cards. -->
        <div class="row justify-content-center">
          @forelse($patronCategories as $category)
          @include('logbook.livecounter.category-card')
          @empty
          <div class="col">
            @include('layouts.partials.no-patron-categories')
          </div>
          @endforelse
        </div>
        <!-- End categories cards. -->
        @if($patronCategories->where('is_primary', false)->count())
        <div class="col text-center">
          <p>
            <a href="#" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false">
              Toggle secondary categories...
            </a>
          </p>
        </div>
        @endif
      </div>
    </div>
  </div>

  @endsection
