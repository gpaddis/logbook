<!-- This week's visits -->
@component('logbook.info-cards.template')
@slot('title')
This Week
@endslot

@slot('value')
{{ number_format($thisWeeksAverage, 1) }}
@endslot

@slot('subtitle')
Average visits / day
@endslot

@slot('footer')
Last week was {{ number_format($lastWeeksAverage, 1) }}
@endslot
@endcomponent
