@extends('layouts.app')

@section('content')

<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Application Settings: Patron Categories</h3>
        </div>
        <div class="panel-body">
          <h4>List of Patron Categories</h4>

          <div class="table-responsive">
            <table class="table">

             @if($categories->count())
             <tr>
              <th>#</th>
              <th>Name</th>
              <th>Abbreviation</th>
              <th>Status</th>
            </tr>

            @foreach($categories as $category)
            <tr>
              <td>{{ $category->id }}</td>
              <td>{{ $category->name }}</td>
              <td>{{ $category->abbreviation }}</td>
              <td>
                @if($category->is_active === true)
                Active
                @else
                Not active
                @endif
              </td>
            </tr>
            @endforeach

            @else
            There are no patron categories yet. <a href="#">Add some!</a>
            @endif

          </table>
        </div>

      </div>
    </div>

  </div>
</div>
</div>

@endsection
