@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col">

            <div class="panel panel-default">
                <div class="panel-body">
                    <h3>Live Counter Index</h3>

                    <div class="container">
                        <div class="row">

                            @foreach($active_patron_categories as $category)
                            <div class="col-md-2 col-xs-3">
                                <p>{{ $category->name }}</p>

                                <strong>
                                    @if($category->logbookEntries()->current()->count())
                                    {{ $category->logbookEntries()->current()->first()->count }}
                                    @else
                                    0
                                    @endif
                                </strong>
                                <div>
                                    <a href="/logbook/livecounter/store?id={{ $category->id }}&operation=add" class="btn btn-success btn-lg" aria-label="Add">
                                        <span class="glyphicon glyphicon-plus"></span>
                                    </a>

                                    <a href="/logbook/livecounter/store?id={{ $category->id }}&operation=subtract" class="btn btn-danger btn-lg" aria-label="Subtract">
                                        <span class="glyphicon glyphicon-minus"></span>
                                    </a>
                                </div>

                            </div>
                            @endforeach

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

{{-- Second experiment --}}



@endsection
