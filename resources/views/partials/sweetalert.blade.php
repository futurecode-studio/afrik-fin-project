{{-- Flash session → SweetAlert2 (chargement page / redirect) --}}
@php
    $adfFlashes = collect([
        'success' => session('success'),
        'error' => session('error'),
        'info' => session('info'),
        'warning' => session('warning'),
        'message' => session('message'),
    ])->filter(fn ($v) => filled($v));
@endphp

@if ($adfFlashes->isNotEmpty())
    <script type="application/json" id="adf-flash-data">{!! $adfFlashes->toJson() !!}</script>
@endif
