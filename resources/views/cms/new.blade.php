<x-app-layout>
    <x-slot name="header">
        New {{ $config->getName() }} Post
    </x-slot>

    <form id="form" method="post" action="/new">
        @csrf

        @if ($config->hasTmdbSearch())
            <x-label for="tmdbsearch" value="Search" />
            <div class="items-center flex">
                <x-input id="tmdbsearch" class="block mt-1 w-full rounded-r-none border-r-0" type="text" name="tmdbsearch" required />
                <button
                    id="tmdb-search"
                    class="input-button block mt-1 p-3 rounded-l-none text-xs rounded-r border border-stone-700 border-l-0" style="width: 100px;"
                >
                    Search
                </button>
            </div>
        @endif

        @if ($config->isGame())
            <x-label for="gbsearch" value="Search" />
            <div class="items-center flex">
                <x-input id="gbsearch" class="block mt-1 w-full rounded-r-none border-r-0" type="text" name="gbsearch" required />
                <button
                    id="gb-search"
                    class="input-button block mt-1 p-3 rounded-l-none text-xs rounded-r border border-stone-700 border-l-0" style="width: 100px;"
                >
                    Search
                </button>
            </div>
        @endif

        @if ($config->isBook())
            <x-label for="openlibsearch" value="Search" />
            <div class="items-center flex">
                <x-input id="openlibsearch" class="block mt-1 w-full rounded-r-none border-r-0" type="text" name="openlibsearch" required />
                <button
                    id="openlib-search"
                    class="input-button block mt-1 p-3 rounded-l-none text-xs rounded-r border border-stone-700 border-l-0" style="width: 100px;"
                >
                    Search
                </button>
            </div>

            <input type="hidden" name="isbn13" id="isbn13" />
        @endif

        @if ($config->hasTmdbSearch() || $config->isGame() || $config->isBook())
            <div style="display: none; height: 200px;overflow: auto;" id="results"></div>
        @endif

        @if ($config->hasLink())
            <x-label for="link" value="Link" />
            <div class="items-center flex">
                <x-input id="link" class="block mt-1 w-full rounded-r-none border-r-0" type="text" name="link" required />
                <button
                    id="link-fetch"
                    class="input-button block mt-1 p-3 rounded-l-none text-xs rounded-r border border-stone-700 border-l-0" style="width: 100px;"
                >
                    Fetch
                </button>
            </div>
        @endif

        @if ($config->hasTitle())
            <x-label for="title" value="Title" />
            <x-input id="title" class="block mt-1 w-full" type="text" name="title" required />
        @endif

        @if ($config->hasTitle())
            <x-label for="permalink" value="Permalink" />
            <div class="items-center flex">
                <x-input class="block mt-1 rounded-r-none border-r-0 dark:bg-stone-700 text-center" style="width: 150px;" type="text" value="/{{ $config->permalinkPrefix() }}/" disabled />
                <x-input id="permalink" class="block mt-1 w-full rounded-l-none" type="text" name="permalink" required />
            </div>
        @endif

        <x-label for="date" value="Date" />
        <x-input id="date" class="block mt-1 w-full" type="datetime-local" name="date" value="{{ $now->format('Y-m-d H:i') }}" required />

        @if ($config->isBook())
            <x-label for="author" value="Author" />
            <x-input id="author" class="block mt-1 w-full" type="text" name="author" required />

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <x-label for="year" value="Year" />
                    <x-input id="year" class="block mt-1 w-full" type="text" name="year" required />
                </div>

                <div>
                    <x-label for="poster" value="Cover" />
                    <x-input id="poster" class="block mt-1 w-full" type="text" name="poster" />
                </div>
            </div>
        @endif

        @if ($config->isGame())
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <x-label for="year" value="Year" />
                    <x-input id="year" class="block mt-1 w-full" type="text" name="year" required />
                </div>

                <div>
                    <x-label for="platform" value="Platform" />
                    <x-select class="block mt-1 w-full" name="platform">
                        <option value="">-</option>
                        @foreach($platforms as $platform)
                            <option value="{{ $platform }}">{{ $platform }}</option>
                        @endforeach
                    </x-select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <x-label for="poster" value="Poster" />
                    <x-input id="poster" class="block mt-1 w-full" type="text" name="poster" />
                </div>

                <div>
                    <x-label for="custom_poster" value="Custom Poster" />
                    <x-input id="custom_poster" class="block mt-1 w-full" type="text" name="custom_poster" />
                </div>
            </div>

            <input type="hidden" name="giantbombid" id="giantbombid" />
            <input type="hidden" name="giantbomburl" id="giantbomburl" />
        @endif
        @if ($config->hasTmdbSearch())
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <x-label for="year" value="Year" />
                    <x-input id="year" class="block mt-1 w-full" type="text" name="year" required />
                </div>

                @if ($config->isTV())
                        <div>
                            <x-label for="season" value="Season" />
                            <x-input id="season" class="block mt-1 w-full" type="text" name="season" required />
                        </div>
                @endif
            </div>


            <div class="grid grid-cols-2 gap-2">
                <div>
                    <x-label for="poster" value="Poster" />
                    <x-input id="poster" class="block mt-1 w-full" type="text" name="poster" />
                </div>

                <div>
                    <x-label for="backdrop" value="Backdrop" />
                    <x-input id="backdrop" class="block mt-1 w-full" type="text" name="backdrop" />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <x-label for="custom_poster" value="Custom Poster" />
                    <x-input id="custom_poster" class="block mt-1 w-full" type="text" name="custom_poster" />
                </div>

                <div>
                    <x-label for="custom_backdrop" value="Custom Backdrop" />
                    <x-input id="custom_backdrop" class="block mt-1 w-full" type="text" name="custom_backdrop" />
                </div>
            </div>

            <input type="hidden" name="tmdbid" id="tmdbid" />
        @endif

        @if ($config->hasCinema())
            <x-label for="cinema" value="Cinema?" />
            <x-checkbox name="cinema" id="cinema" />
        @endif

        @if ($config->hasSummary())
            <x-label for="summary" value="Summary" />
            <x-input id="summary" class="block mt-1 w-full" type="text" name="summary" required />
        @endif

        @if ($config->hasTags())
            <x-label for="tags" value="Tags" />
            <div class="flex flex-wrap gap-0.5 mt-1">
                @foreach($apiData['tags'] as $tag)
                    <div
                        class="tag bg-pink-100 text-stone-900 py-1 px-2 hover:cursor-pointer"
                        data-selected="false"
                        data-value="{{ $tag }}"
                    >
                        {{ $tag }}
                    </div>
                @endforeach
            </div>

            <input type="hidden" name="tags" />
        @endif

        @if ($config->hasProject())
            <x-label for="project" value="Project" />
            <x-select class="block mt-1 w-full" name="project">
                <option value="">-</option>
                @foreach($apiData['projects'] as $project)
                    <option value="{{ $project['link'] }}">{{ $project['title'] }}</option>
                @endforeach
            </x-select>
        @endif

        @if ($config->hasAuthorDetails())
            <x-label for="author_name" value="Author Name" />
            <x-input id="author_name" class="block mt-1 w-full" type="text" name="author_name" />

            <x-label for="author_web" value="Author Website" />
            <x-input id="author_web" class="block mt-1 w-full" type="url" name="author_web" />

            <x-label for="author_feed" value="Author Feed" />
            <x-input id="author_feed" class="block mt-1 w-full" type="url" name="author_feed" />

            <x-label for="author_mastodon" value="Author Mastodon" />
            <x-input id="author_mastodon" class="block mt-1 w-full" type="url" name="author_mastodon" />
        @endif

        <x-label for="date" value="Content" />

        <textarea id="contents" class="border-stone-300 dark:border-stone-700 dark:bg-stone-900 dark:text-stone-300 focus:border-stone-500 dark:focus:border-stone-600 focus:ring-stone-500 dark:focus:ring-stone-600 rounded-md shadow-sm w-full" name="contents" rows="20"></textarea>

        <div class="mt-3 flex justify-between">
            <x-button id="preview">
                Preview
            </x-button>
            <x-button id="save">
                Create
            </x-button>
        </div>

        <input type="hidden" name="type" value="{{ $type }}" />
    </form>

    <script>
        const fetchLinkData = async () => {
            linkFetch.innerHTML = '<i class="fad fa-spinner fa-pulse"></i>'
            linkFetch.setAttribute('disabled', true)
            const link = document.getElementById('link').value

            const res = await fetch('/api/link?link=' + link, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
            })

            const data = await res.json()

            document.getElementById('title').value = data.title
            document.getElementById('author_name').value = data.name
            document.getElementById('author_web').value = data.website
            document.getElementById('author_feed').value = data.feed
            document.getElementById('author_mastodon').value = data.mastodon

            linkFetch.innerHTML = 'Fetch'
            linkFetch.setAttribute('disabled', false)
        }

        const form = document.getElementById('form')

        document.querySelectorAll('.tag').forEach(tag => {
            tag.addEventListener('click', () => {
                tag.dataset.selected = tag.dataset.selected === 'true' ? 'false' : 'true'
                tag.classList.toggle('bg-pink-100')
                tag.classList.toggle('bg-pink-500')

                const tags = Array.from(document.querySelectorAll('.tag[data-selected=true]')).map(t => t.innerText)

                form.tags.value = tags.join(',')
            })
        })

        document.getElementById('preview').addEventListener('click', async (e) => {
            e.preventDefault()
            const contents = editor.getValue()

            const formToObject = f => Object.fromEntries(new FormData(f))
            const formData = formToObject(form)

            const res = await fetch('/preview', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ contents, formData }),
            })

            const url = await res.text()

            window.open(url)
        })

        const linkFetch = document.getElementById('link-fetch')

        if (linkFetch) {
            linkFetch.addEventListener('click', async (e) => {
                e.preventDefault()
                fetchLinkData()
            })
        }

        const tmdbSearch = document.getElementById('tmdb-search')

        if (tmdbSearch) {
            tmdbSearch.addEventListener('click', async (e) => {
                e.preventDefault()
                tmdbSearch.innerHTML = '<i class="fad fa-spinner fa-pulse"></i>'
                tmdbSearch.setAttribute('disabled', true)

                const query = document.getElementById('tmdbsearch').value

                const res = await fetch('/api/tmdb?type={{ $config->isTv() ? 'tv' : 'movie' }}&query=' + query, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                })

                const data = await res.json()

                const results = document.getElementById('results')

                results.style.display = 'block'

                const handleSelected = (e) => {
                    results.innerHTML = ''
                    results.style.display = 'none'
                    document.getElementById('tmdbid').value = e.currentTarget.dataset.id
                    document.getElementById('title').value = e.currentTarget.dataset.title
                    document.getElementById('poster').value = e.currentTarget.dataset.poster
                    document.getElementById('backdrop').value = e.currentTarget.dataset.backdrop
                    document.getElementById('year').value = e.currentTarget.dataset.year

                    document.getElementById('title').dispatchEvent(new Event('change'));
                }

                data.forEach(d => {
                    const div = document.createElement('div')
                    div.innerHTML = `${d.title} (${d.year})`
                    div.classList.add('py-1', 'px-2', 'hover:cursor-pointer', 'hover:bg-pink-200', 'hover:text-black', 'border-b', 'border-stone-700', 'py-2')
                    div.dataset.id = d.id
                    div.dataset.title = d.title
                    div.dataset.year = d.year
                    // div.dataset.summary = d.summary
                    div.dataset.poster = d.poster
                    div.dataset.backdrop = d.backdrop
                    div.addEventListener('click', handleSelected)

                    results.appendChild(div)
                })

                tmdbSearch.innerHTML = 'Search'
                tmdbSearch.setAttribute('disabled', false)
            })
        }

        const giantbombSearch = document.getElementById('gb-search')

        if (giantbombSearch) {
            giantbombSearch.addEventListener('click', async (e) => {
                e.preventDefault()
                giantbombSearch.innerHTML = '<i class="fad fa-spinner fa-pulse"></i>'
                giantbombSearch.setAttribute('disabled', true)

                const query = document.getElementById('gbsearch').value

                const res = await fetch('/api/giantbomb?query=' + query, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                })

                const data = await res.json()

                const results = document.getElementById('results')

                results.style.display = 'block'

                const handleSelected = (e) => {
                    results.innerHTML = ''
                    results.style.display = 'none'
                    document.getElementById('giantbombid').value = e.currentTarget.dataset.id
                    document.getElementById('giantbomburl').value = e.currentTarget.dataset.link
                    document.getElementById('title').value = e.currentTarget.dataset.title
                    document.getElementById('poster').value = e.currentTarget.dataset.poster
                    document.getElementById('year').value = e.currentTarget.dataset.year

                    document.getElementById('title').dispatchEvent(new Event('change'));
                }

                data.forEach(d => {
                    const div = document.createElement('div')
                    div.innerHTML = `${d.title} ${d.year ? `(${d.year})` : ''} ${d.meta ? `[${d.meta}]` : ''}`
                    div.classList.add('py-1', 'px-2', 'hover:cursor-pointer', 'hover:bg-pink-200', 'hover:text-black', 'border-b', 'border-stone-700', 'py-2')
                    div.dataset.id = d.id
                    div.dataset.title = d.title
                    div.dataset.year = d.year
                    div.dataset.poster = d.poster
                    div.dataset.link = d.link
                    div.addEventListener('click', handleSelected)

                    results.appendChild(div)
                })

                giantbombSearch.innerHTML = 'Search'
                giantbombSearch.setAttribute('disabled', false)
            })
        }

        const openlibsearch = document.getElementById('openlib-search')

        if (openlibsearch) {
            openlibsearch.addEventListener('click', async (e) => {
                e.preventDefault()
                openlibsearch.innerHTML = '<i class="fad fa-spinner fa-pulse"></i>'
                openlibsearch.setAttribute('disabled', true)

                const query = document.getElementById('openlibsearch').value

                const res = await fetch('/api/openlib?query=' + query, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                })

                const data = await res.json()

                const results = document.getElementById('results')

                results.style.display = 'block'

                const handleSelected = (e) => {
                    results.innerHTML = ''
                    results.style.display = 'none'
                    document.getElementById('isbn13').value = e.currentTarget.dataset.id
                    document.getElementById('title').value = e.currentTarget.dataset.title
                    document.getElementById('author').value = e.currentTarget.dataset.author
                    document.getElementById('poster').value = e.currentTarget.dataset.poster
                    document.getElementById('year').value = e.currentTarget.dataset.year

                    document.getElementById('title').dispatchEvent(new Event('change'));
                }

                data.forEach(d => {
                    const div = document.createElement('div')
                    div.innerHTML = `${d.title} ${d.author ? ` - ${d.author}` : ''}`
                    div.classList.add('py-1', 'px-2', 'hover:cursor-pointer', 'hover:bg-pink-200', 'hover:text-black', 'border-b', 'border-stone-700', 'py-2')
                    div.dataset.id = d.isbn
                    div.dataset.title = d.title
                    div.dataset.author = d.author
                    div.dataset.year = d.year
                    div.dataset.poster = d.poster ? d.poster : ''
                    div.addEventListener('click', handleSelected)

                    results.appendChild(div)
                })

                openlibsearch.innerHTML = 'Search'
                openlibsearch.setAttribute('disabled', false)
            })
        }

        (function() {
            const urlParams = new URLSearchParams(window.location.search);
            const link = urlParams.get('link')

            if (!link) return

            if (document.getElementById('link')) {
                document.getElementById('link').value = link
                fetchLinkData()
            }
        })()
    </script>


</x-app-layout>
