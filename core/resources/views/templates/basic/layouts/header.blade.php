@php
    $socialIcons = getContent('social_icon.element', false, null, true);
@endphp

<header class="header-section">
    <div class="header">
        <div class="header-top-area">
            <div class="container">
                <div class="header-top-content-area d-flex flex-wrap align-items-center justify-content-between">
                    <div class="header-top-left">
                        <div class="social-area">
                            <ul class="header-social">
                                @foreach ($socialIcons as $socialIcon)
                                    <li>
                                        <a href="{{ $socialIcon->data_values->url }}" target="_blank">
                                            @php echo $socialIcon->data_values->icon @endphp
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="header-top-right">
                        @if ($general->multi_language)
                            <div class="language-select-area">
                                <select class="language-select langSel">
                                    @foreach ($language as $item)
                                        <option value="{{ $item->code }}" @if (session('lang') == $item->code) selected @endif>{{ __($item->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="header-bottom-area ">
            <div class="container">
                <div class="header-menu-content">
                    <nav class="navbar navbar-expand-lg p-0">
                        <a class="site-logo" href="{{ route('home') }}">
                            <img src="{{ getImage(getFilePath('logoIcon') . '/logo.png') }}" alt="@lang('logo')">
                        </a>
                        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="fas fa-bars"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            @yield('menu')
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>
