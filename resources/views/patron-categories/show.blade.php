@extends('layouts.app')

@section('content')

<div class="row">
  <div class="col">
    <div class="card">
      <h3 class="card-header">{{ $category->name }}</h3>
      <div class="card-body">
        <h4>Abbreviation</h4>
        <p>{{ $category->abbreviation }}</p>

        @if($category->notes)
        <h4>Notes</h4>
        <p>
          {!! nl2br(e($category->notes)) !!}
        </p>
        @endif

        <hr>

        <p>Back to the <a href="{{ route('patron-categories.index') }}">list of patron categories</a>.</p>
      </div>

    </div>
  </div>

  <div class="col-md-3">
    <div class="card">
      <div class="card-body">
        <p>Created: {{ $category->created_at->diffForHumans() }}</p>
        <p>Last updated {{ $category->updated_at->diffForHumans() }}</p>

        <div>
          <a href="{{ $category->path() . '/edit' }}" class="btn btn-outline-secondary btn-sm">Edit this category</a>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
