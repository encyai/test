@extends($activeTemplate . 'layouts.frontend')
@section('content')
    @php
        $contact = getContent('contact_us.content', true);
    @endphp
    <section class="contact-item-section pt-80">
        <div class="container">
            <div class="row justify-content-center gy-4">
                <div class="col-lg-4 col-md-6">
                    <div class="contact-item section--bg text-center">
                        <div class="contact-icon">
                            <i class="las la-map-marked-alt"></i>
                        </div>
                        <div class="contact-content">
                            <h4 class="title">@lang('Address')</h4>
                            <ul class="contact-list">
                                <li>{{ $contact->data_values->contact_details }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="contact-item section--bg text-center">
                        <div class="contact-icon">
                            <i class="las la-phone-volume"></i>
                        </div>
                        <div class="contact-content">
                            <h4 class="title">@lang('Phone')</h4>
                            <ul class="contact-list">
                                <li><a
                                        href="tel:{{ $contact->data_values->contact_number }}">{{ $contact->data_values->contact_number }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="contact-item section--bg text-center">
                        <div class="contact-icon">
                            <i class="las la-envelope"></i>
                        </div>
                        <div class="contact-content">
                            <h4 class="title">@lang('Email')</h4>
                            <ul class="contact-list">
                                <li><a
                                        href="mailto:{{ $contact->data_values->email_address }}">{{ $contact->data_values->email_address }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="contact-section ptb-80 wow fade-in-bottom" data-wow-duration="1s">
        <div class="container">
            <div class="row justify-content-center gy-4">
                <div class="col-lg-7">
                    <div class="contact-form-area">
                        <h3 class="title text-white mb-20">{{ __($contact->data_values->title) }}</h3>
                        <form class="contact-form" action="{{ route('contact') }}" method="POST">
                            @csrf
                            <div class="row justify-content-center">
                                @php
                                    $user = auth()->user();
                                @endphp
                                <div class="col-lg-6 form-group">
                                    <input type="text" name="name" class="form--control"
                                        placeholder="@lang('Name')" value="{{ old('name', @$user->fullname) }}"
                                        @if (auth()->user()) readonly @endif required>
                                </div>

                                <div class="col-lg-6 form-group">
                                    <input type="email" name="email" class="form--control"
                                        placeholder="@lang('Email')" value="{{ old('email', @$user->email) }}"
                                        @if (auth()->user()) readonly @endif required="">
                                </div>
                                <div class="col-lg-12 form-group">
                                    <input type="text" name="subject" class="form--control"
                                        placeholder="@lang('Subject')" value="{{ old('subject') }}" required="">
                                </div>
                                <div class="col-lg-12 form-group">
                                    <textarea class="form--control" name="message" placeholder="@lang('Write your message')" required="">{{ old('message') }}</textarea>
                                </div>

                                <div class="col-lg-12 form-group">
                                    <button type="submit" class="submit-btn w-100 mt-10">@lang('Send Message')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="map-area">
                        <iframe src="{{ $contact->data_values->map_embed_link }}" width="600" height="450"
                            style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
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
