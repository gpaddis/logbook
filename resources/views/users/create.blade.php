@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                Create a new user account
            </div>
            <div class="card-body">

                <form action="#" method="post">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <h5 for="first_name">First Name</h5>
                        <input class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}"
                        type="text"
                        name="first_name"
                        id="first_name"
                        placeholder="Enter the first name"
                        value="{{ old('first_name') }}">

                        @if($errors->has('first_name'))
                        <small class="text-danger">
                            <strong>Error:</strong> {{ $errors->first('first_name') }}
                        </small>
                        @endif
                    </div>

                    <div class="form-group">
                        <h5 for="last_name">Last Name</h5>
                        <input class="form-control {{ $errors->has('last_name') ? ' is-invalid' : '' }}"
                        type="text"
                        name="last_name"
                        id="last_name"
                        placeholder="Enter the last name"
                        value="{{ old('last_name') }}">

                        @if($errors->has('last_name'))
                        <small class="text-danger">
                            <strong>Error:</strong> {{ $errors->first('last_name') }}
                        </small>
                        @endif
                    </div>

                    <div class="form-group">
                        <h5 for="email">Email</h5>
                        <input class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}"
                        type="email"
                        name="email"
                        id="email"
                        placeholder="john@doe.com"
                        value="{{ old('email') }}">

                        @if($errors->has('email'))
                        <small class="text-danger">
                            <strong>Error:</strong> {{ $errors->first('email') }}
                        </small>
                        @endif
                    </div>

                    <div class="form-group">
                        <h5 for="password">Password</h5>
                        <input class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}"
                        type="password"
                        name="password"
                        id="password"
                        value="{{ old('password') }}">

                        @if($errors->has('password'))
                        <small class="text-danger">
                            <strong>Error:</strong> {{ $errors->first('password') }}
                        </small>
                        @endif
                    </div>

                    <div class="form-group">
                        <h5 for="custom-select">Role</h5>
                        <select class="custom-select">
                            @foreach($roles as $role)
                            <option value="{{ $role }}"{{ $role == 'standard' ? ' selected' : '' }}>{{ ucfirst($role) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Discard Changes</a>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
