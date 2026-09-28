@if(session('success') || session('status'))
<div class="ml-alert ml-alert-success mb-4">
    <i class="fa-solid fa-circle-check"></i>
    <span>{{ session('success') ?? session('status') }}</span>
</div>
@endif

@if(session('warning'))
<div class="ml-alert ml-alert-warning mb-4">
    <i class="fa-solid fa-triangle-exclamation"></i>
    <span>{{ session('warning') }}</span>
</div>
@endif

@if($errors->any())
<div class="ml-alert ml-alert-danger mb-4">
    <i class="fa-solid fa-circle-exclamation"></i>
    <div>
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
</div>
@endif
