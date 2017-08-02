@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8">

            <div class="panel panel-default">

                <div class="panel-body">
                    <h3>{{ $patronCategory->name }}</h3>
                    
                    <p>Back to the <a href="{{ route('patron-categories.index') }}">list of patron categories</a>.</p>
                </div>

            </div>
        </div>

        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-body">
                    <p>Created: {{ $patronCategory->created_at->diffForHumans() }}</p>
                    <p>Last updated {{ $patronCategory->updated_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>

    </div>
</div>
</div>

@endsection
