<x-app-layout>
    <x-slot name="header">
        Dashboard
    </x-slot>

    <div class="dashboard-buttons">
        <a class="dbutton" href="/new?type=1">
            <div>
                <i class="fad fa-rss"></i>
            </div>
            <div>
                Blog
            </div>
        </a>
        <a class="dbutton" href="/new?type=2">
            <div>
                <i class="fad fa-sticky-note"></i>
            </div>
            <div>
                Note
            </div>
        </a>
        <a class="dbutton" href="/new?type=3">
            <div>
                <i class="fad fa-link"></i>
            </div>
            <div>
                Link
            </div>
        </a>
    </div>
    <div class="dashboard-buttons mt-3">
        <a class="dbutton" href="/new?type=5">
            <div>
                <i class="fad fa-popcorn"></i>
            </div>
            <div>
                Movie
            </div>
        </a>
        <a class="dbutton" href="/new?type=6">
            <div>
                <i class="fad fa-tv-retro"></i>
            </div>
            <div>
                TV
            </div>
        </a>
        <a class="dbutton" href="/new?type=7">
            <div>
                <i class="fad fa-gamepad"></i>
            </div>
            <div>
                Game
            </div>
        </a>
        <a class="dbutton" href="/new?type=8">
            <div>
                <i class="fad fa-books"></i>
            </div>
            <div>
                Book
            </div>
        </a>
    </div>

</x-app-layout>
