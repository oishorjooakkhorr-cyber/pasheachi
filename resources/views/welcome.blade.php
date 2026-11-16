<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'PasheAchi') }}</title>

        @include('layouts.partials.assets')
    </head>
    <body class="bg-body-tertiary">
        <nav class="navbar bg-white border-bottom">
            <div class="container">
                <a class="navbar-brand" href="/"><x-application-logo /></a>

                @if (Route::has('login'))
                    <div class="d-flex gap-2">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-outline-primary btn-sm">{{ __('Dashboard') }}</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-link btn-sm">{{ __('Log in') }}</a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-primary btn-sm">{{ __('Register') }}</a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </nav>

        <main class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h1 class="display-5 fw-semibold mb-3">{{ __('Your hands back home') }}</h1>
                    <p class="lead text-body-secondary mb-4">
                        {{ __('Living abroad? Post an errand for your parents or relatives in Bangladesh. A verified local runner takes care of it, sends photo proof, and your family confirms the handover with a one-time code. Your payment is held safely until then.') }}
                    </p>

                    @guest
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">{{ __('Get started') }}</a>
                        @endif
                    @endguest
                </div>
            </div>
        </main>

        @include('layouts.partials.scripts')
    </body>
</html>
