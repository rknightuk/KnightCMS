<x-guest-layout>
    <div id="home">
        <div>
            <x-application-logo style="width: 100px; height: 100px;" />
        </div>

        <h1 class="text-4xl font-bold text-center mt-3 mb-6">KnightCMS<span class="text-pink-500">.</span></h1>

        <div class="flex justify-center gap-2">
            <a href="{{ route('login') }}" class="button">Login</a>
            <a href="https://github.com/rknightuk/knightcms" class="button" target="_blank">GitHub</a>
        </div>
    </div>
</x-guest-layout>
