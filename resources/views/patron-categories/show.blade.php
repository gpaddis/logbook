@extends('layouts.app')

@section('content')

<div class="row">
  <div class="col">
    <div class="card">
      <div class="card-body">
        <h3>{{ $category->name }}</h3>
        <p>Abbreviation: {{ $category->abbreviation }}</p>

        <h4>Notes</h4>
        <p>
          @if($category->notes)
          {{ $category->notes }}
          @else
          This category has no notes yet.
          @endif
        </p>

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
