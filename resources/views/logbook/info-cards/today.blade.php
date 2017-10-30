<!-- Today's visits -->
@component('logbook.info-cards.template')
@slot('title')
Today
@endslot

@slot('value')
{{ $today }}
@endslot

@slot('subtitle')
Visits
@endslot

@slot('footer')
<span class="text-{{ $today - $lastAvailableDay >= 0 ? 'success' : 'danger' }}">
    {{ abs($today - $lastAvailableDay) }} {{ $today - $lastAvailableDay > 0 ? 'more' : 'less' }}</span> than last time
@endslot
@endcomponent
