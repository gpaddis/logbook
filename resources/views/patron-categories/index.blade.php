@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
  <div class="col">
    <div class="card">
      <div class="card-header">List of Patron Categories</div>

      @if($patron_categories->isEmpty())
      <div class="card-body">
        @include('layouts.partials.no-patron-categories')
      </div>
      @else
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead class="thead-default">
             <tr>
              <th>#</th>
              <th>Name</th>
              <th>Abbreviation</th>
              <th>Type</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($patron_categories as $category)
            <tr>
              <th scope="row">
                {{ $category->id }}
              </th>
              <td>
                <a href="{{ $category->path() }}">
                  {{ $category->name }}
                </a>
              </td>
              <td>
                {{ $category->abbreviation }}
              </td>
              <td>
                {{ $category->is_primary ? 'Primary' : 'Secondary' }}
              </td>
              <td>
                {{ $category->is_active ? 'Active' : 'Inactive' }}
              </td>
              <td>
                <a href="{{ $category->path() . '/edit' }}" class="btn btn-outline-secondary btn-sm">Edit</a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="text-center">
        <a href="{{ route('patron-categories.create') }}" class="btn btn-primary">Add a new patron category</a>
      </div>
      @endif
    </div>

  </div>
</div>
</div>

@endsection
