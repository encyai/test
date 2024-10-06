@extends($activeTemplate . 'layouts.header')

@section('menu')
    <ul class="navbar-nav main-menu ms-auto me-auto">
        <li><a href="{{ route('home') }}">@lang('Home')</a></li>
        @foreach ($pages as $k => $data)
            <li><a href="{{ route('pages', [$data->slug]) }}">{{ __($data->name) }}</a></li>
        @endforeach
        <li><a href="{{ route('plan') }}">@lang('Investment Plan')</a></li>
        <li><a href="{{ route('blog') }}">@lang('Blog')</a></li>
        <li><a href="{{ route('contact') }}">@lang('Contact')</a></li>
    </ul>

    <div class="header-action">

        @auth
            <a href="{{ route('user.home') }}" class="btn--base">
                <i class="las la-tachometer-alt"></i> @lang('Dashboard')
            </a>

            <a href="{{ route('user.logout') }}" class="bg-white rounded">
                <i class="la la-sign-out"></i> @lang('Logout')
            </a>
        @else
            <a href="{{ route('user.login') }}" class="btn--base">
                <i class="las la-user-circle"></i> @lang('Login')
            </a>
        @endauth
    </div>
@endsection
