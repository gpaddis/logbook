<!-- This week's visits -->
@component('logbook.info-cards.template')
@slot('title')
This Week
@endslot

@slot('value')
{{ $thisWeek->count() }}
@endslot

@slot('footer')
{{ abs($weekDifference) }} {{ $weekDifference > 0 ? 'more' : 'less' }} than last week (
<span class="text-{{ $weekVariation >= 0 ? 'success' : 'danger' }}">{{ $weekVariation }}%</span>)
@endslot
@endcomponent
