@extends($activeTemplate . "layouts.$layout")
@section('content')

    @php
        $sections = App\Models\Page::where('tempname', $activeTemplate)
            ->where('slug', 'investment-plan')
            ->first();
        
        $plan = getContent('plan.content', true);
        
        $plans = App\Models\Plan::where('status', 1)
            ->with('currency')
            ->orderBy('id', 'DESC')
            ->get();
    @endphp

    <section class="plan-section {{ $layout != 'master' ? 'ptb-80' : null }}">
        <div class="container">

            <div class="row  gy-4 wow fade-in-bottom" data-wow-duration="1s">
                <div class="col-xl-3 col-lg-12">
                    <div class="plan-content-area">
                        <h3 class="title">{{ __(@$plan->data_values->heading) }}</h3>
                        <p>{{ __(@$plan->data_values->subheading) }}</p>
                    </div>
                </div>

                <div class="col-xl-9 col-lg-12">
                    <div class="row justify-content-center gy-4">
                        @include($activeTemplate . 'partials.plan_item', $plans)
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection
