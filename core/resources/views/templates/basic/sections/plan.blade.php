@php
    $plan = getContent('plan.content', true);
    $plans = App\Models\Plan::where('status', 1)
        ->with('currency')
        ->orderBy('id', 'DESC')
        ->limit(3)
        ->get();
@endphp

<section class="plan-section ptb-80">
    <div class="container">
        <div class="row  gy-4 wow fade-in-bottom" data-wow-duration="1s">
            <div class="col-xl-3 col-lg-12">
                <div class="plan-content-area">
                    <h3 class="title">{{ __(@$plan->data_values->heading) }}</h3>
                    <p>{{ __(@$plan->data_values->subheading) }}</p>
                    <div class="plan-content-btn mt-40">
                        <a href="{{ url(@$plan->data_values->btn_url) }}" class="btn--base">{{ __(@$plan->data_values->btn_name) }}</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-9 col-lg-12">
                <div class="row gy-4">
                    @include($activeTemplate . 'partials.plan_item', $plans)
                </div>
            </div>
        </div>
    </div>
</section>
