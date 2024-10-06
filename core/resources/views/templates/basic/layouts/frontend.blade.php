@extends($activeTemplate . 'layouts.app')
@section('panel')
    @include($activeTemplate . 'partials.guest_header')
    @if (!request()->routeIs('home'))
        @include($activeTemplate . 'partials.breadcrumb')
    @endif

    @yield('content')
    @include($activeTemplate . 'partials.footer')
@endsection
