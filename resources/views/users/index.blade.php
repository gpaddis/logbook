@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
  <div class="col">
    <div class="card">
      <div class="card-header">List of Users</div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead class="thead-default">
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email Address</th>
                <th>Roles</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($users as $user)
              <tr>
                <th scope="row">
                  {{ $user->id }}
                </th>
                <td>
                  {{ $user->first_name . " " . $user->last_name }}
                </td>
                <td>
                  {{ $user->email }}
                </td>
                <td>
                  @foreach($user->roles as $role)
                  <p>{{ $role->name }}</p>
                  @endforeach
                </td>
                <td>
                  <a href="#" class="btn btn-outline-secondary btn-sm">Edit</a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        @can('manage patron categories')
        <div class="text-center">
          <a href="#" class="btn btn-primary">Add a new user</a>
        </div>
        @endcan
      </div>
    </div>
  </div>
</div>

@endsection
