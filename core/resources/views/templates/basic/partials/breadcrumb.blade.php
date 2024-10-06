@php
    $breadcrumb = getContent('breadcrumb.content', true);
@endphp
<section class="inner-banner-section banner-section bg_img banner-breadcrumb" data-background="{{getImage('assets/images/frontend/breadcrumb/' . @$breadcrumb->data_values->background_image, '1920x200')}}">
    <div class="container">
        <div class="row justify-content-center align-items-center gy-4">
            <div class="col-xl-12 text-center">
                <div class="banner-content wow fade-in-bottom" data-wow-duration="1s">
                    <h2 class="title">{{__($customTitle ?? $pageTitle)}}</h2>
                </div>
            </div>
        </div>
    </div>
</section>