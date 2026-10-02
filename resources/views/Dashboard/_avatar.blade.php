@if (!empty($mlAvatarUrl))<img src="{{ $mlAvatarUrl }}" alt="{{ auth()->user()->name }}">@else{{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}@endif
