@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">List of Patron Categories</div>

      <div class="card-body">
        @if($patronCategories->count())
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
            @foreach($patronCategories as $category)
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
                @if($category->is_primary === true)
                Primary
                @else
                Secondary
                @endif
              </td>
              <td>
                @if($category->is_active === true)
                Active
                @else
                Not active
                @endif
              </td>
              <td>
                <a href="#" class="btn btn-outline-secondary btn-sm">Edit</a>
                {{-- @if($category->is_active === true)
                <a href="#" class="btn btn-outline-danger">Deactivate</a>
                @else
                <a href="#" class="btn btn-outline-success">Activate</a>
                @endif --}}
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        <div class="text-center">
          <a href="#" class="btn btn-primary">Add a new patron category</a>
        </div>

        @else
        <h4>There are no patron categories yet. <a href="#">Add some!</a></h4>
        @endif

      </div>

    </div>
  </div>
</div>

@endsection
