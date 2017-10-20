@extends('layouts.app')

@section('content')

<div class="row">
  <div class="col">
    <div class="card">
      <div class="card-body">
        <h3>{{ $category->name }}</h3>

        <p>Back to the <a href="{{ route('patron-categories.index') }}">list of patron categories</a>.</p>
      </div>

    </div>
  </div>

  <div class="col-md-3">
    <div class="card">
      <div class="card-body">
        <p>Created: {{ $category->created_at->diffForHumans() }}</p>
        <p>Last updated {{ $category->updated_at->diffForHumans() }}</p>
      </div>
    </div>
  </div>
</div>

@endsection
