@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                Edit patron category #{{ $category->id}} ({{ $category->name }})
            </div>
            <div class="card-body">
                <form action="#">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="name">Category name</label>
                        <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                        type="text"
                        name="name"
                        id="name"
                        placeholder="Enter the patron category name (max 25 chars)"
                        value="{{ $category->name }}">

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
                        value="{{ $category->abbreviation }}">

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
