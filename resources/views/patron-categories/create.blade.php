@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                Create a new patron category
            </div>
            <div class="card-body">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>Be careful:</strong> it is not possible to delete a patron category once it is created. You can only edit the details afterwards.
                </div>

                <form action="{{ route('patron-categories.store') }}" method="post">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="name">Category name</label>
                        <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                        type="text"
                        name="name"
                        id="name"
                        placeholder="Enter the patron category name (max 25 chars)"
                        value="{{ old('name') }}">

                        @if($errors->has('name'))
                        <small class="text-danger">
                            <strong>Error:</strong> {{ $errors->first('name') }}
                        </small>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="name">Abbreviation</label>
                        <input class="form-control {{ $errors->has('abbreviation') ? ' is-invalid' : '' }}"
                        type="text"
                        name="abbreviation"
                        id="abbreviation"
                        placeholder="Enter an abbreviation for the category, e.g. Stud., Ext. Us. (max 10 chars)"
                        value="{{ old('abbreviation') }}">

                        @if($errors->has('abbreviation'))
                        <small class="text-danger">
                            <strong>Error:</strong> {{ $errors->first('abbreviation') }}
                        </small>
                        @endif
                    </div>

                    <div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input"
                            type="checkbox"
                            name="is_active"
                            id="is_active"
                            value="0">
                            Inactive
                        </label>
                        <small class="form-text text-muted">Check if you want to deactivate the category (default: active).</small>
                    </div>

                    <div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input"
                            type="checkbox"
                            name="is_primary"
                            id="is_primary"
                            value="0">
                            Secondary
                        </label>
                        <small class="form-text text-muted">Check if you want the category to be secondary (default: primary).</small>
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('patron-categories.index') }}" class="btn btn-secondary">Discard Changes</a>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
