@php
    $service = getContent('service.content', true);
    $services = getContent('service.element', false, null, true);
@endphp
<section class="service-section bg--gray ptb-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 text-center">
                <div class="section-header wow fade-in-up" data-wow-duration="1s">
                    <h2 class="section-title">{{ __(@$service->data_values->heading) }}</h2>
                </div>
            </div>
        </div>
        <div class="row justify-content-center gy-4 wow fade-in-bottom" data-wow-duration="1s">
            @foreach ($services as $value)
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-xs-6 col-xxs-10 col-12">
                    <div class="service-item text-center h-100">
                        <div class="service-icon">
                            @php echo $value->data_values->icon @endphp
                        </div>

                        <div class="service-content">
                            <h3 class="title">{{ __($value->data_values->title) }}</h3>
                            <p>{{ __($value->data_values->description) }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
