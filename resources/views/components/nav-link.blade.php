@props(['active' => false])

<a {{ $attributes->class(['nav-link', 'active' => $active]) }} @if ($active) aria-current="page" @endif>
    {{ $slot }}
</a>
