@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="blog-section ptb-80 wow fade-in-bottom" data-wow-duration="1s">
        <div class="container">
            <div class="row justify-content-center gy-4">
                @foreach ($blogs as $blog)
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
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
            <nav>
                {{ $blogs->links() }}
            </nav>
        </div>
    </section>

    @if ($sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection
