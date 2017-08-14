@extends('layouts.app')

@section('content')

<div class="row">
  <div class="col">
    <div class="card">
      <div class="card-header">List of Patron Categories</div>

      <div class="card-body">
        @if($patronCategories->count())
        <div class="table-responsive">
          <table class="table table-hover">

           <tr>
            <th>#</th>
            <th>Name</th>
            <th>Abbreviation</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>

          @foreach($patronCategories as $category)
          <tr>
            <td>
              {{ $category->id }}
            </td>
            <td>
              <a href="{{ $category->path() }}">
                {{ $category->name }}
              </a>
            </td>
            <td>
              {{ $category->abbreviation }}
            </td>
            <td>
              @if($category->is_active === true)
              Active
              @else
              Not active
              @endif
            </td>
            <td>
              <a href="#" class="btn btn-default btn-xs">Edit</a>
              @if($category->is_active === true)
              <a href="#" class="btn btn-danger btn-xs">Deactivate</a>
              @else
              <a href="#" class="btn btn-success btn-xs">Activate</a>
              @endif
            </td>
          </tr>
          @endforeach


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
