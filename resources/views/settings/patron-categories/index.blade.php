@extends('layouts.app')

@section('content')

<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">

      <div class="panel panel-default">

          <div class="panel-body">
              <h3>List of Patron Categories</h3>

              @if($categories->count())
              <div class="table-responsive">
                <table class="table">

                   <tr>
                      <th>#</th>
                      <th>Name</th>
                      <th>Abbreviation</th>
                      <th>Status</th>
                      <th>Actions</th>
                  </tr>

                  @foreach($categories as $category)
                  <tr>
                      <td>
                        {{ $category->id }}
                    </td>
                    <td>
                        <a href="{{ $category->settingsPath() }}">
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
                    @if($category->is_active === true)
                    <a href="#">Deactivate</a> |
                    @else
                    <a href="#">Activate</a> |
                    @endif
                    <a href="#">Edit</a>
                </td>
            </tr>
            @endforeach


        </table>

        <h4 class="text-center"><a href="#">Add a new patron category</a></h4>

        @else
        <h4>There are no patron categories yet. <a href="#">Add some!</a></h4>
        @endif

    </div>

</div>
</div>

</div>
</div>
</div>

@endsection
