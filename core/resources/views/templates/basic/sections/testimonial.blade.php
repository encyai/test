@php
    $testimonial = getContent('testimonial.content', true);
    $testimonials = getContent('testimonial.element', false);
@endphp
<section class="client-section ptb-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 text-center">
                <div class="section-header wow fade-in-up" data-wow-duration="1s">
                    <h2 class="section-title">{{__($testimonial->data_values->heading)}}</h2>
                </div>
            </div>
        </div>
        <div class="row justify-content-center wow fade-in-bottom" data-wow-duration="1s">
            <div class="col-xl-12">
                <div class="client-content-slider">
                    <div class="swiper-wrapper">
                        @foreach($testimonials as $value)
                            <div class="swiper-slide">
                                <div class="client-wrapper">
                                    <img src="{{asset($activeTemplateTrue.'images/shape.svg')}}" alt="@lang('shape-image')" class="client-svg">
                                    <div class="client-content-wrapper">
                                        <div class="client-content-area">
                                            <div class="client-quote-icon">
                                                <i class="las la-quote-left"></i>
                                            </div>
                                            <div class="client-blockquote">
                                                <p>{{__($value->data_values->testimonial)}}</p>
                                            </div>
                                            <div class="client-user-area">
                                                <div class="client-user-thumb">
                                                    <img src="{{getImage('assets/images/frontend/testimonial/' . @$value->data_values->client_image, '450x475')}}" alt="@lang('user')">
                                                </div>
                                                <div class="client-user-content">
                                                    <h3 class="title">{{__($value->data_values->name)}}</h3>
                                                    <span class="sub-title">{{__($value->data_values->designation)}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
    </div>
</section>