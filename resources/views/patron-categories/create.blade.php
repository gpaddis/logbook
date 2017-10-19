@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                Create a new patron category
            </div>
            <div class="card-body">
                <form action="{{ route('patron-categories.store') }}" method="post">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="name">Category name</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Enter the patron category name">
                    </div>

                    <div class="form-group">
                        <label for="name">Abbreviation</label>
                        <input type="text" class="form-control" name="abbrevation" id="abbrevation" placeholder="Enter the patron category name">
                    </div>

                    <div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1">Active
                        </label>
                    </div>

                    <div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" name="is_primary" value="1">Primary
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
