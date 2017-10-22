@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                Edit patron category #{{ $category->id}} ({{ $category->name }})
            </div>
            <div class="card-body">
                <form action="{{ $category->path() }}" method="post">
                    {{ csrf_field() }}
                    {{ method_field('PATCH') }}

                    <div class="form-group">
                        <h5 for="name">Category name</h5>
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
                        <h5 for="name">Abbreviation</h5>
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

                    <h5 for="is_active">Status</h5>
                    <div class="form-group">
                        <label class="custom-control custom-radio">
                            <input
                            id="is_active_1"
                            name="is_active"
                            type="radio"
                            class="custom-control-input"
                            value="1"
                            {{ $category->is_active ? 'checked' : '' }}>
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Active</span>
                        </label>
                        <label class="custom-control custom-radio">
                            <input
                            id="is_active_0"
                            name="is_active"
                            type="radio"
                            class="custom-control-input"
                            value="0"
                            {{ $category->is_active ? '' : 'checked' }}>
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Not active</span>
                        </label>
                    </div>

                    <h5 for="is_primary">Level</h5>
                    <div class="form-group">
                        <label class="custom-control custom-radio">
                            <input
                            id="is_primary_1"
                            name="is_primary"
                            type="radio"
                            class="custom-control-input"
                            value="1"
                            {{ $category->is_primary ? 'checked' : '' }}>
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Primary</span>
                        </label>
                        <label class="custom-control custom-radio">
                            <input
                            id="is_primary_0"
                            name="is_primary"
                            type="radio"
                            class="custom-control-input"
                            value="0"
                            {{ $category->is_primary ? '' : 'checked' }}>
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Secondary</span>
                        </label>
                    </div>

                    <div class="form-group">
                        <h5 for="notes">Notes</h5>
                        <textarea class="form-control"
                        id="notes"
                        name="notes"
                        rows="3"
                        placeholder="Enter your comments..."
                        value="{{ $category->notes }}"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('patron-categories.index') }}" class="btn btn-secondary">Discard Changes</a>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
