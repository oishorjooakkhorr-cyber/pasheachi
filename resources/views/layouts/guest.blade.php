<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PasheAchi') }}</title>

        @include('layouts.partials.assets')
    </head>
    <body class="bg-body-tertiary">
        <div class="min-vh-100 d-flex flex-column justify-content-center align-items-center px-3 py-5">
            <a href="/" class="text-decoration-none mb-4">
                <x-application-logo class="fs-3" />
            </a>

            <div class="card shadow-sm w-100 auth-card">
                <div class="card-body p-4">
                    {{ $slot }}
                </div>
            </div>
        </div>

        @include('layouts.partials.scripts')
    </body>
</html>
