<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=atkinson-hyperlegible:400,400i,700,700i" rel="stylesheet" />

        <link href="/assets/fontawesome/css/all.css" rel="stylesheet" />
        <link
            rel="stylesheet"
            data-name="vs/editor/editor.main"
            href="/assets/monaco/vs/editor/editor.main.css"
        />

        @include('icons')

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body>
    <div id="layout">
        <a href="#menu" id="menuLink" class="menu-link">
            ☰
        </a>

        <div id="menu">
            <div class="kcms-menu">
                <a href="/dashboard" class="flex justify-center border-b mb-3 pb-3 border-stone-600">
                    <x-application-logo style="width: 50px; height: 50px;" />
                </a>

                <h1>Posts</h1>

                <ul>
                    <li><i class="fad fa-rss"></i> <a href="/files?path=src/posts/blog">Blog</a></li>
                    <li><i class="fad fa-sticky-note"></i> <a href="/files?path=src/posts/notes">Notes</a></li>
                    <li><i class="fad fa-link"></i> <a href="/files?path=src/posts/links">Links</a></li>
                </ul>

                <h1>Almanac</h1>

                <ul>
                    <li><i class="fad fa-popcorn"></i> <a href="/files?path=src/posts/almanac/movies">Movie</a></li>
                    <li><i class="fad fa-tv-retro"></i> <a href="/files?path=src/posts/almanac/tv">TV</a></li>
                    <li><i class="fad fa-gamepad"></i> <a href="/files?path=src/posts/almanac/games">Game</a></li>
                    <li><i class="fad fa-books"></i> <a href="/files?path=src/posts/almanac/books">Book</a></li>
                </ul>

                <h1>Misc</h1>

                <ul>
                    <li><i class="fad fa-burger-soda"></i> <a href="/media">Media</a></li>
                    <li><i class="fad fa-brain"></i> <a href="/files?path=src/pages/intersect/entries">Intersect</a></li>
                    <li><i class="fad fa-cloud-upload"></i> <a href="/uploads">Uploads</a></li>
                    <li><i class="fad fa-folders"></i> <a href="/files">Files</a></li>
                </ul>

                <hr class="my-10 border-stone-600">
                <ul>
                    <li><i class="fad fa-sign-out"></i> <a href="/logout">Logout</a></li>
                </ul>

            </div>
        </div>

        <div id="main">
            @if (isset($header))
                <div class="header">
                    <h1>{{ $header }}</h1>
                </div>
            @endif
            <div class="content">
                <div class="mx-3 p-5">
                    @if (session('success'))
                        <article class="article-success">
                            {!! session('success') !!}
                        </article>
                    @endif

                    @if (session('error'))
                        <article class="article-error">
                            {!! session('error') !!}
                        </article>
                    @endif

                    @if (session('errors'))
                        <article class="article-error">
                            <ul>
                                @foreach (session('errors')->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </article>
                    @endif
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>

        @stack('modals')

        @livewireScripts
    </body>
</html>
