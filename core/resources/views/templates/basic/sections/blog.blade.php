@php
    $blogCaption = getContent('blog.content', true);
    $blogs = getContent('blog.element', false, 3);
@endphp
<section class="blog-section ptb-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 text-center">
                <div class="section-header wow fade-in-up" data-wow-duration="1s">
                    <h2 class="section-title">{{ __(@$blogCaption->data_values->heading) }}</h2>
                </div>
            </div>
        </div>
        <div class="row justify-content-center gy-4">
            @foreach ($blogs as $blog)
                <div class="col-md-4">
                    <div class="blog-item">
                        <div class="blog-thumb">
                            <a href="{{ route('blog.details', [$blog->id, slug($blog->data_values->title)]) }}">
                                <img src="{{ getImage('assets/images/frontend/blog/thumb_' . @$blog->data_values->blog_image, '384x256') }}" alt="@lang('blog')">
                            </a>
                        </div>
                        <div class="blog-content">
                            <h4 class="title">
                                <a href="{{ route('blog.details', [$blog->id, slug($blog->data_values->title)]) }}">{{ __($blog->data_values->title) }}</a>
                            </h4>
                            <div class="blog-btn mt-20">
                                <a href="{{ route('blog.details', [$blog->id, slug($blog->data_values->title)]) }}" class="custom-btn">
                                    @lang('Read More') <i class="las la-angle-double-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
