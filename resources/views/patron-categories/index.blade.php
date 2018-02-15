@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
  <div class="col">
    <div class="card">
      <div class="card-header">List of Patron Categories</div>
      <div class="card-body">
        @if($patron_categories->isNotEmpty())
        <div class="table-responsive">
          <table class="table table-hover">
            <thead class="thead-default">
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Abbreviation</th>
                <th>Type</th>
                <th>Status</th>
                @can('manage patron categories')
                <th>Actions</th>
                @endcan
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
                  {{ $category->is_active ? 'Active' : 'Not active' }}
                </td>
                @can('manage patron categories')
                <td>
                  <a href="{{ $category->path() . '/edit' }}" class="btn btn-outline-secondary btn-sm">Edit</a>
                </td>
                @endcan
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @else
        <p class="text-center">There are no patron categories yet.</p>
        @endif

        @can('manage patron categories')
        <div class="text-center">
          <a href="{{ route('patron-categories.create') }}" class="btn btn-primary">Add a new patron category</a>
        </div>
        @endcan
      </div>
    </div>
  </div>
</div>

@endsection
