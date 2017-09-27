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
<span class="text-{{ $today - $yesterday >= 0 ? 'success' : 'danger' }}">
    {{ abs($today - $yesterday) }} {{ $today - $yesterday > 0 ? 'more' : 'less' }}</span> than yesterday
@endslot
@endcomponent
