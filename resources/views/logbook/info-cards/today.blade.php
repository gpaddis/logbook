<!-- Today's visits -->
@component('logbook.info-cards.template')
@slot('title')
Today
@endslot

@slot('value')
{{ $today->count() }}
@endslot

@slot('footer')
{{ abs($dayDifference) }} {{ $dayDifference > 0 ? 'more' : 'less' }} than yesterday (
<span class="text-{{ $dayVariation >= 0 ? 'success' : 'danger' }}">{{ $dayVariation }}%</span>)
@endslot
@endcomponent
