@extends('admin.layouts.app')

@section('panel')
    @if(@json_decode($general->system_info)->version > systemDetails()['version'])
    <div class="row">
        <div class="col-md-12">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">
                    <h3 class="card-title"> @lang('New Version Available') <button class="btn btn--dark float-end">@lang('Version') {{json_decode($general->system_info)->version}}</button> </h3>
                </div>
                <div class="card-body">
                    <h5 class="card-title text-dark">@lang('What is the Update ?')</h5>
                    <p><pre  class="f-size--24">{{json_decode($general->system_info)->details}}</pre></p>
                </div>
            </div>
        </div>
    </div>
    @endif
    @if(@json_decode($general->system_info)->message)
    <div class="row">
        @foreach(json_decode($general->system_info)->message as $msg)
            <div class="col-md-12">
                <div class="alert border border--primary" role="alert">
                    <div class="alert__icon bg--primary"><i class="far fa-bell"></i></div>
                    <p class="alert__message">@php echo $msg; @endphp</p>
                    <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            </div>
        @endforeach
    </div>
    @endif

    <div class="row gy-4">
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                link="{{route('admin.users.all')}}"
                icon="las la-users f-size--56"
                title="Total Users"
                value="{{$widget['total_users']}}"
                bg="primary"
            />
        </div><!-- dashboard-w1 end -->
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                link="{{route('admin.users.active')}}"
                icon="las la-user-check f-size--56"
                title="Active Users"
                value="{{$widget['verified_users']}}"
                bg="success"
            />
        </div><!-- dashboard-w1 end -->
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                link="{{route('admin.users.email.unverified')}}"
                icon="lar la-envelope f-size--56"
                title="Email Unverified Users"
                value="{{$widget['email_unverified_users']}}"
                bg="danger"
            />
        </div><!-- dashboard-w1 end -->
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                link="{{route('admin.users.mobile.unverified')}}"
                icon="las la-comment-slash f-size--56"
                title="Mobile Unverified Users"
                value="{{$widget['mobile_unverified_users']}}"
                bg="red"
            />
        </div><!-- dashboard-w1 end -->
    </div><!-- row end-->

    <div class="row gy-4 mt-2">
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                style="3"
                link="{{route('admin.currency.index')}}"
                icon="las la-wallet"
                title="Total Currency"
                value="{{ $total['currency'] }}"
                bg="1"
            />
        </div><!-- dashboard-w1 end -->
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                style="3"
                link="{{route('admin.plan.index')}}"
                icon="fa fa-hand-holding-usd"
                title="Total Investment Plan"
                value="{{ $total['plan'] }}"
                bg="primary"
            />
        </div><!-- dashboard-w1 end -->
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                style="3"
                link="{{route('admin.investment.index')}}"
                icon="fa fa-money-bill-alt"
                title="Total Investment"
                value="{{ $total['invest'] }}"
                bg="14"
            />
        </div><!-- dashboard-w1 end -->
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                style="3"
                link="{{route('admin.withdraw.index')}}"
                icon="las la-wallet"
                title="Total Withdraw"
                value="{{ $total['withdrawal'] }}"
                bg="19"
            />
        </div><!-- dashboard-w1 end -->
    </div><!-- row end-->

    <div class="row gy-4 mt-2">
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                style="1"
                link="{{route('admin.payment.index')}}"
                icon="las la-wallet"
                title="Total Payments"
                value="{{ $payment['total'] }}"
                bg="primary"
            />
        </div><!-- dashboard-w1 end -->
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                style="1"
                link="{{route('admin.payment.waiting')}}"
                icon="la la-pause-circle"
                title="Waiting Payments"
                value="{{ $payment['waiting'] }}"
                bg="warning"
            />
        </div><!-- dashboard-w1 end -->
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                style="1"
                link="{{route('admin.payment.reported')}}"
                icon="la la-info-circle"
                title="Reported Payments"
                value="{{ $payment['reported'] }}"
                bg="danger"
            />
        </div><!-- dashboard-w1 end -->
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                style="1"
                link="{{route('admin.payment.rejected')}}"
                icon="la la-close"
                title="Rejected Payments"
                value="{{ $payment['rejected'] }}"
                bg="info"
            />
        </div><!-- dashboard-w1 end -->
    </div><!-- row end-->

    <div class="row gy-4 mt-2">
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                style="3"
                link="{{route('admin.withdraw.index')}}"
                icon="las la-money-bill-wave-alt"
                title="Total Withdraw"
                value="{{ $withdraw['total'] }}"
                bg="19"
            />
        </div><!-- dashboard-w1 end -->
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                style="3"
                link="{{route('admin.withdraw.pending')}}"
                icon="las la-pause"
                title="Pending Withdraw"
                value="{{ $withdraw['pending'] }}"
                bg="warning"
            />
        </div><!-- dashboard-w1 end -->
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                style="3"
                link="{{route('admin.withdraw.upcoming')}}"
                icon="las la-spinner"
                title="Upcoming Withdraw"
                value="{{ $withdraw['upcoming'] }}"
                bg="1"
            />
        </div><!-- dashboard-w1 end -->
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                style="3"
                link="{{route('admin.withdraw.completed')}}"
                icon="las la-check"
                title="Complete Withdraw"
                value="{{ $withdraw['complete'] }}"
                bg="primary"
            />
        </div><!-- dashboard-w1 end -->
    </div><!-- row end-->

    <div class="row mb-none-30 mt-5 gy-4">
        <div class="col-xl-4 col-lg-6">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <h5 class="card-title">@lang('Login By Browser') (@lang('Last 30 days'))</h5>
                    <canvas id="userBrowserChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">@lang('Login By OS') (@lang('Last 30 days'))</h5>
                    <canvas id="userOsChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">@lang('Login By Country') (@lang('Last 30 days'))</h5>
                    <canvas id="userCountryChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    @include('admin.partials.cron')

@endsection

@push('breadcrumb-plugins')
    <button type="button" class="btn @if(Carbon\Carbon::parse($general->last_cron_run)->diffInSeconds()<600)
        btn--success @elseif(Carbon\Carbon::parse($general->last_cron_run)->diffInSeconds()<1200) btn--warning @else
        btn--danger @endif "><i class="fa fa-fw fa-clock"></i>@lang('Last Cron Run') : {{Carbon\Carbon::parse($general->last_cron_run)->difFforHumans()}}
    </button>
@endpush

@push('script')

    <script src="{{asset('assets/admin/js/vendor/chart.js.2.8.0.js')}}"></script>
    <script>
        "use strict";
        var ctx = document.getElementById('userBrowserChart');
        var myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: @json($chart['user_browser_counter']->keys()),
                datasets: [{
                    data: {{ $chart['user_browser_counter']->flatten() }},
                    backgroundColor: [
                        '#ff7675',
                        '#6c5ce7',
                        '#ffa62b',
                        '#ffeaa7',
                        '#D980FA',
                        '#fccbcb',
                        '#45aaf2',
                        '#05dfd7',
                        '#FF00F6',
                        '#1e90ff',
                        '#2ed573',
                        '#eccc68',
                        '#ff5200',
                        '#cd84f1',
                        '#7efff5',
                        '#7158e2',
                        '#fff200',
                        '#ff9ff3',
                        '#08ffc8',
                        '#3742fa',
                        '#1089ff',
                        '#70FF61',
                        '#bf9fee',
                        '#574b90'
                    ],
                    borderColor: [
                        'rgba(231, 80, 90, 0.75)'
                    ],
                    borderWidth: 0,

                }]
            },
            options: {
                aspectRatio: 1,
                responsive: true,
                maintainAspectRatio: true,
                elements: {
                    line: {
                        tension: 0 // disables bezier curves
                    }
                },
                scales: {
                    xAxes: [{
                        display: false
                    }],
                    yAxes: [{
                        display: false
                    }]
                },
                legend: {
                    display: false,
                }
            }
        });



        var ctx = document.getElementById('userOsChart');
        var myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: @json($chart['user_os_counter']->keys()),
                datasets: [{
                    data: {{ $chart['user_os_counter']->flatten() }},
                    backgroundColor: [
                        '#ff7675',
                        '#6c5ce7',
                        '#ffa62b',
                        '#ffeaa7',
                        '#D980FA',
                        '#fccbcb',
                        '#45aaf2',
                        '#05dfd7',
                        '#FF00F6',
                        '#1e90ff',
                        '#2ed573',
                        '#eccc68',
                        '#ff5200',
                        '#cd84f1',
                        '#7efff5',
                        '#7158e2',
                        '#fff200',
                        '#ff9ff3',
                        '#08ffc8',
                        '#3742fa',
                        '#1089ff',
                        '#70FF61',
                        '#bf9fee',
                        '#574b90'
                    ],
                    borderColor: [
                        'rgba(0, 0, 0, 0.05)'
                    ],
                    borderWidth: 0,

                }]
            },
            options: {
                aspectRatio: 1,
                responsive: true,
                elements: {
                    line: {
                        tension: 0 // disables bezier curves
                    }
                },
                scales: {
                    xAxes: [{
                        display: false
                    }],
                    yAxes: [{
                        display: false
                    }]
                },
                legend: {
                    display: false,
                }
            },
        });


        // Donut chart
        var ctx = document.getElementById('userCountryChart');
        var myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: @json($chart['user_country_counter']->keys()),
                datasets: [{
                    data: {{ $chart['user_country_counter']->flatten() }},
                    backgroundColor: [
                        '#ff7675',
                        '#6c5ce7',
                        '#ffa62b',
                        '#ffeaa7',
                        '#D980FA',
                        '#fccbcb',
                        '#45aaf2',
                        '#05dfd7',
                        '#FF00F6',
                        '#1e90ff',
                        '#2ed573',
                        '#eccc68',
                        '#ff5200',
                        '#cd84f1',
                        '#7efff5',
                        '#7158e2',
                        '#fff200',
                        '#ff9ff3',
                        '#08ffc8',
                        '#3742fa',
                        '#1089ff',
                        '#70FF61',
                        '#bf9fee',
                        '#574b90'
                    ],
                    borderColor: [
                        'rgba(231, 80, 90, 0.75)'
                    ],
                    borderWidth: 0,

                }]
            },
            options: {
                aspectRatio: 1,
                responsive: true,
                elements: {
                    line: {
                        tension: 0 // disables bezier curves
                    }
                },
                scales: {
                    xAxes: [{
                        display: false
                    }],
                    yAxes: [{
                        display: false
                    }]
                },
                legend: {
                    display: false,
                }
            }
        });

        


    </script>
@endpush
