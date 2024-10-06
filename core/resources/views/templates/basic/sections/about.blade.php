@php
    $about = getContent('about.content', true);
@endphp
<section class="about-section ptb-80">
    <div class="container">
        <div class="row justify-content-center gy-4">
            <div class="col-xl-5 col-lg-6">
                <div class="about-thumb-area wow fade-in-left" data-wow-duration="1s">
                    <img src="{{ getImage('assets/images/frontend/about/' . @$about->data_values->about_image, '460x415') }}" alt="@lang('about')">
                </div>
            </div>
            <div class="col-xl-6 offset-xl-1 col-lg-6">
                <div class="about-content-area wow fade-in-right" data-wow-duration="1s">
                    <span class="sub-title">@lang('About Us')</span>
                    <h2 class="title">{{ __($about->data_values->heading) }}</h2>
                    <p>{{ __($about->data_values->description) }}</p>
                </div>
            </div>
        </div>
    </div>
</section>
