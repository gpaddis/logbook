@extends('logbook.index')

@section('tab-content')
<box-generic-lg value="{{ $today }}" v-cloak>
    <div slot="title">Today</div>
    <div slot="subtitle">Visits</div>
    <div slot="footer">
        <span class="text-{{ $today - $lastAvailableDay >= 0 ? 'success' : 'danger' }}">
            {{ abs($today - $lastAvailableDay) }} {{ $today - $lastAvailableDay > 0 ? 'more' : 'less' }}
        </span> than last time
    </div>
</box-generic-lg>

<box-generic-lg value="{{ number_format($thisWeeksAverage, 1) }}" v-cloak>
    <div slot="title">This Week</div>
    <div slot="subtitle">Average visits / day</div>
    <div slot="footer">Last week was {{ number_format($lastWeeksAverage, 1) }}</div>
</box-generic-lg>
@endsection

@section('tab-sidebar')
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
@endsection
