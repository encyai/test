@extends($activeTemplate . 'layouts.app')
@section('panel')
    @include($activeTemplate . 'partials.user_header')
    @if (!request()->routeIs('home'))
        @include($activeTemplate . 'partials.breadcrumb')
    @endif

    <section class="dashboard-section ptb-80">
        <div class="container">
            @if (auth()->user()->activation != 1)
                @php
                    $notice = getContent('activation.content', true);
                @endphp
                <div class="alert alert-warning text-center mb-5">
                    <h4 class="text--danger">@lang('Activation Required')</h4>
                    <p class="lead"> @php echo auth()->user()->activation == 0 ? @$notice->data_values->activation_notice : @$notice->data_values->pending_notice @endphp</p>
                </div>
            @endif
            @yield('content')
        </div>
    </section>

    @include($activeTemplate . 'partials.footer')
@endsection
