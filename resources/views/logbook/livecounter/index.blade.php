@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <h1>Live Counter Index</h1>
                <div class="row">
                    @foreach($active_patron_categories as $category)
                    <div class="col">
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
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
