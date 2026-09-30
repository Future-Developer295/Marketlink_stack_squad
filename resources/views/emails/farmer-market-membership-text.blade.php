@if ($forAdmin)
Farmer {{ $farmer->name }} ({{ $farmer->email }}) has {{ $joined ? 'joined' : 'left' }} the market "{{ $market->name }}".
@else
Hi {{ $farmer->name }},

@if ($joined)
You have been added to the market "{{ $market->name }}". Customers can now find your stall there.
@else
You have left the market "{{ $market->name }}". Your stall will no longer be listed there. You can join again at any time.
@endif
@endif

Date: {{ now()->format('d M Y, h:i A') }}
Open: {{ $actionUrl }}

- MarketLink
