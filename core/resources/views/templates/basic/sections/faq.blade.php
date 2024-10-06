@php
    $faq = getContent('faq.content', true);
    $faqs = getContent('faq.element', false);
@endphp
<section class="faq-section bg--gray ptb-80">
    <div class="container">
        <div class="row gy-4 flex-row-reverse justify-content-center align-items-center wow fade-in-bottom" data-wow-duration="1s">
            <div class="col-xl-6 col-lg-6 order-1">
                <div class="faq-content-area">
                    <div class="faq-header-area">
                        <span class="sub-title">@lang('FAQ')</span>
                        <h2 class="title">{{ __($faq->data_values->heading) }}</h2>
                    </div>
                    <div class="faq-wrapper">
                        @foreach ($faqs as $value)
                            <div class="faq-item">
                                <h3 class="faq-title">
                                    <span class="right-icon"></span>
                                    <span class="title">{{ __($value->data_values->question) }}</span>
                                </h3>
                                <div class="faq-content">
                                    <p>{{ __($value->data_values->answer) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-xl-5 col-lg-6 col-sm-6 col-5">
                <div class="faq-thumb">
                    <img src="{{ getImage('assets/images/frontend/faq/' . @$faq->data_values->faq_image, '990x780') }}" alt="@lang('faq')">
                </div>
            </div>
        </div>
    </div>
</section>
