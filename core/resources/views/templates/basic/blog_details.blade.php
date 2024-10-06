@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="blog-details-section blog-section ptb-80 wow fade-in-bottom" data-wow-duration="1s">
        <div class="container">
            <div class="row justify-content-center gy-4">
                <div class="col-xl-9 col-lg-8">
                    <div class="blog-item">
                        <div class="blog-thumb">
                            <img src="{{ getImage('assets/images/frontend/blog/' . @$blog->data_values->blog_image, '960x640') }}" alt="@lang('Blog Image')">
                        </div>
                        <div class="blog-content">
                            <h2 class="title">{{ __($blog->data_values->title) }}</h2>
                            <div class="blog-date mb-3">{{ showDateTime($blog->created_at, 'd M Y') }}</div>
                            @php echo $blog->data_values->description_nic @endphp
                        </div>
                    </div>
                    <div class="blog-social-area d-flex flex-wrap justify-content-between align-items-center">
                        <h3 class="title">@lang('Share This Post')</h3>
                        <ul class="blog-social">
                            <li>
                                <a href="https://www.facebook.com/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                            </li>
                            <li>
                                <a href="https://twitter.com/share?url={{ urlencode(url()->current()) }}&text=Simple Share Buttons&hashtags=simplesharebuttons" target="_blank"><i class="fab fa-twitter"></i></a>
                            </li>
                            <li>
                                <a href="http://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div class="fb-comments" data-href="{{ route('blog.details', [$blog->id, slug($blog->data_values->title)]) }}" data-numposts="5"></div>
                </div>
                <div class="col-xl-3 col-lg-4">
                    <div class="sidebar">
                        <div class="blog-widget-box">
                            <h3 class="widget-title">@lang('Latest Posts')</h3>
                            <div class="popular-widget-box">
                                @foreach ($latestBlog as $value)
                                    <div class="single-popular-item d-flex flex-wrap">
                                        <div class="popular-item-thumb">
                                            <a href="{{ route('blog.details', [$value->id, slug($value->data_values->title)]) }}">
                                                <img src="{{ getImage('assets/images/frontend/blog/thumb_' . @$value->data_values->blog_image, '384x256') }}" alt="@lang('blog image')">
                                            </a>
                                        </div>
                                        <div class="popular-item-content">
                                            <h5 class="title">
                                                <a href="{{ route('blog.details', [$value->id, slug($value->data_values->title)]) }}">
                                                    {{ __($value->data_values->title) }}
                                                </a>
                                            </h5>
                                            <span class="blog-date">{{ showDateTime($value->created_at, 'd M Y') }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('fbComment')
    @php echo loadExtension('fb-comment') @endphp
@endpush
