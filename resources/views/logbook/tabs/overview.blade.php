@extends('logbook.index')

@section('tab-content')
<overview-view inline-template v-cloak>
<div class="col">
    <div class="row">
        <box-generic-lg value="{{ $today }}">
            <div slot="title">Today</div>
            <div slot="subtitle">Visits</div>
            <div slot="footer">
                <span class="text-{{ $today - $lastAvailableDay >= 0 ? 'success' : 'danger' }}">
                    {{ abs($today - $lastAvailableDay) }} {{ $today - $lastAvailableDay > 0 ? 'more' : 'less' }}
                </span> than last time
            </div>
        </box-generic-lg>

        <box-generic-lg value="{{ number_format($thisWeeksAverage, 1) }}">
            <div slot="title">This Week</div>
            <div slot="subtitle">Average visits / day</div>
            <div slot="footer">Last week was {{ number_format($lastWeeksAverage, 1) }}</div>
        </box-generic-lg>
    </div>
</div>
</overview-view>
@endsection

@section('tab-sidebar')
<div class="col-md-3">
    <div class="row">
        <div class="col">
            @can('edit logbook')
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
            @endcan

            <div class="card">
                <div class="card-header">Export to CSV</div>
                <div class="card-body">
                    <form class="form" method="GET" action="{{ route('logbook.export') }}">
                        <p>Select a a time range:</p>
                        <div class="form-group">
                            <input type="date" class="form-control mb-1" id="from" name="from" max="{{ date('Y-m-d') }}" value="{{ date('Y') }}-01-01">
                            <input type="date" class="form-control" id="to" name="to" max="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Export</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
