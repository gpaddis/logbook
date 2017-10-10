@extends('logbook.index')

@section('tab-content')
@include('logbook.info-cards.today')
@include('logbook.info-cards.this-week')
@endsection

@push('tab-sidebar')
<div class="col-md-3">
    <div class="row">
        <div class="col">
            <div class="card mb-3">
                <div class="card-header">Update the logbook</div>
                <div class="card-body">
                    <form class="form" method="GET" action="{{ route('logbook.update') }}">
                        <div class="form-group">
                            <label class="sr-only" for="date">Pick a date:</label>
                            <input type="date" class="form-control" id="date" name="date" max="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Show</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Card header</div>
                <div class="card-body">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem, quas cum aspernatur illum facere quis repudiandae maxime? Odit voluptatibus, iusto aliquid animi est consectetur, delectus distinctio enim vitae cupiditate consequatur.</p>
                </div>
            </div>

        </div>
    </div>
</div>
@endpush
