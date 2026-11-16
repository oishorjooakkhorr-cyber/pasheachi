@props([
    'name',
    'show' => false,
    'maxWidth' => null,
])

@php
$dialogSize = match ($maxWidth) {
    'sm' => 'modal-sm',
    'lg' => 'modal-lg',
    'xl' => 'modal-xl',
    default => '',
};
@endphp

{{-- Bootstrap modal. Open it with data-bs-toggle="modal" data-bs-target="#{name}". --}}
<div class="modal fade" id="{{ $name }}" tabindex="-1" aria-labelledby="{{ $name }}-title" aria-hidden="true"
     @if ($show) data-show-on-load @endif>
    <div class="modal-dialog modal-dialog-centered {{ $dialogSize }}">
        <div class="modal-content">
            {{ $slot }}
        </div>
    </div>
</div>
