@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col">
        <div class="card">
            <div class="card-header">Live Counter Index</div>

            <div class="card-body">

                <div class="row justify-content-center">
                    @foreach($active_patron_categories as $category)
                    <div class="col-lg-3 col-md-6 col-sm-6 isolated">
                        <div class="card">
                            <h4 class="card-header">
                                {{ $category->abbreviation }}
                            </h4>
                            <div class="card-body text-center">
                                <h2 class="display-2 text-center">
                                    @if($category->logbookEntries()->current()->count())
                                    {{ $category->logbookEntries()->current()->first()->count }}
                                    @else
                                    0
                                    @endif
                                </h3>

                                <div class="card-footer">
                                    <p>
                                        <a href="/logbook/livecounter/store?id={{ $category->id }}&operation=add" class="btn btn-success btn-xl" aria-label="Add">+ Add User +</a>
                                    </p>

                                    <a href="/logbook/livecounter/store?id={{ $category->id }}&operation=subtract">- Subtract -</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</div>

{{-- <div class="col">
<p>{{ $category->name }}</p>
<strong>
@if($category->logbookEntries()->current()->count())
{{ $category->logbookEntries()->current()->first()->count }}
@else
0
@endif
</strong>
<div>
<a href="/logbook/livecounter/store?id={{ $category->id }}&operation=add" class="btn btn-success btn-lg" aria-label="Add">+</a>
<a href="/logbook/livecounter/store?id={{ $category->id }}&operation=subtract" class="btn btn-danger btn-lg" aria-label="Subtract">-</a>
</div>
</div> --}}

{{-- <div class="d-flex justify-content-end">
<div class="mr-auto p-2">
<p>{{ $category->name }}: <strong>
@if($category->logbookEntries()->current()->count())
{{ $category->logbookEntries()->current()->first()->count }}
@else
0
@endif
</strong></p>
</div>
<div class="p-2">
<a href="/logbook/livecounter/store?id={{ $category->id }}&operation=add" class="btn btn-success btn-sm" aria-label="Add">Add user</a>
</div>
<div class="p-2">
<a href="/logbook/livecounter/store?id={{ $category->id }}&operation=subtract" class="btn btn-outline-danger btn-sm" aria-label="Add">Remove user</a>
</div>
</div> --}}

@endsection
