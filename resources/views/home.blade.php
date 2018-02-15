@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col">
        <div class="card">
            <div class="card-header">Dashboard</div>

            <div class="card-body">
                <h3>Welcome, {{ Auth::user()->first_name }}!</h3>

                <p>Select one option from the navigation menu above.</p>
            </div>
        </div>
    </div>
</div>

@endsection
