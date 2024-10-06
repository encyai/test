@extends($activeTemplate . 'layouts.header')

@section('menu')
    <ul class="navbar-nav main-menu ms-auto me-auto">
        <li><a href="{{ route('user.home') }}">@lang('Dashboard')</a></li>
        <li class="menu_has_children">
            <a href="javascript:void(0)">
                @lang('Investment')
            </a>
            <ul class="sub-menu">
                <li><a href="{{ route('user.invest.now') }}">@lang('Invest Now')</a></li>
                <li><a href="{{ route('user.invest.history') }}">@lang('Investment History')</a></li>
            </ul>
        </li>

        <li class="menu_has_children">
            <a href="javascript:void(0)">
                @lang('Withdraw')
            </a>
            <ul class="sub-menu">
                <li><a href="{{ route('user.withdraw.now') }}">@lang('Withdraw Now')</a></li>
                <li><a href="{{ route('user.withdraw.history') }}">@lang('Withdrawal History')</a></li>
                <li><a href="{{ route('user.withdraw.information') }}">@lang('Withdraw Information')</a></li>
            </ul>
        </li>
        <li>
            <a href="{{ route('user.transactions.history') }}">
                @lang('Transactions')
            </a>
        </li>

        @if ($general->referral)
            <li class="menu_has_children">
                <a href="javascript:void(0)">
                    @lang('Referral')
                </a>
                <ul class="sub-menu">
                    <li><a href="{{ route('user.referral.log') }}">@lang('Referred Users')</a></li>
                    <li><a href="{{ route('user.referral.commission') }}">@lang('Referral Commission')</a></li>
                    <li><a href="{{ route('user.referral.withdraw') }}">@lang('Referral Withdrawals')</a></li>
                </ul>
            </li>
        @endif

        <li><a href="{{ route('ticket.index') }}">@lang('Support')</a></li>

        @if (auth()->user()->activation == 0 || auth()->user()->activation == 2)
            <li><a href="{{ route('user.activate') }}">@lang('Account Activation')</a></li>
        @endif

    </ul>

    <div class="header-right dropdown">
        <button type="button" data-bs-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false">
            <div class="header-user-area d-flex flex-wrap align-items-center justify-content-between">
                <div class="header-user-thumb">
                    <i class="las la-user-circle"></i>
                </div>
                <div class="header-user-content">
                    <span>@lang('Account')</span>
                </div>
                <span class="header-user-icon"><i class="las la-chevron-circle-down"></i></span>
            </div>
        </button>
        <div class="dropdown-menu dropdown-menu--sm p-0 border-0 dropdown-menu-right">

            <a href="{{ route('user.profile.setting') }}" class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                <i class="dropdown-menu__icon las la-user-circle"></i>
                <span class="dropdown-menu__caption">@lang('Profile Settings')</span>
            </a>

            <a href="{{ route('user.change.password') }}" class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                <i class="dropdown-menu__icon las la-key"></i>
                <span class="dropdown-menu__caption">@lang('Change Password')</span>
            </a>

            <a href="{{ route('user.twofactor') }}" class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                <i class="dropdown-menu__icon las la-lock"></i>
                <span class="dropdown-menu__caption">@lang('2FA Security')</span>
            </a>

            <a href="{{ route('user.logout') }}" class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                <i class="dropdown-menu__icon las la-sign-out-alt"></i>
                <span class="dropdown-menu__caption">@lang('Logout')</span>
            </a>
        </div>
    </div>
@endsection
