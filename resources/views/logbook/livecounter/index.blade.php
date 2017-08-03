@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3>Live Counter Index</h3>

                    <table class="table">
                        <tr>
                            @foreach($active_patron_categories as $category)
                            <td>
                                <p>{{ $category->name }}</p>
                                <h1>
                                    @if($category->logbookEntries()->current()->count())
                                    {{ $category->logbookEntries()->current()->first()->count }}
                                    @else
                                    0
                                    @endif
                                </h1>
                            </td>
                            @endforeach
                        </tr>    
                    </table>

                </div>
            </div>

        </div>
    </div>
</div>

@endsection
