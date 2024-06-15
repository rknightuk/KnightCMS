<x-app-layout>
    <x-slot name="header">
        Update Now and Next
    </x-slot>

    <x-label value="Currently" class="mb-3" />

    <div id="current-container" class="w-full" style="height: 200px;"></div>

    <form id="currently-form" method="post" action="/file/{{ $currently['sha'] }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="contents" />
        <input type="hidden" name="path" value="{{ $currently['path'] }}" />
    </form>

    <div class="mt-3 flex justify-end">
        <x-button id="current-save">
            Update Currently
        </x-button>
    </div>

    <x-label value="Next" class="mb-3" />

    <div id="next-container" class="w-full" style="height: 200px;"></div>

    <form id="next-form" method="post" action="/file/{{ $next['sha'] }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="contents" />
        <input type="hidden" name="path" value="{{ $next['path'] }}" />
    </form>

    <div class="mt-3 flex justify-end">
        <x-button id="next-save">
            Update Next
        </x-button>
    </div>

    <script>
        var require = { paths: { vs: '/assets/monaco/vs' } };
    </script>
    <script src="/assets/monaco/vs/loader.js"></script>trans
    <script src="/assets/monaco/vs/editor/editor.main.nls.js"></script>
    <script src="/assets/monaco/vs/editor/editor.main.js"></script>

    <script>
        const editorOptions = {
            language: 'markdown',
            theme: 'vs-dark',
            minimap: { enabled: false },
            scrollbar: { vertical: 'hidden', horizontal: 'hidden' },
            wordWrap: 'on',
            fontSize: 16,
            scrollBeyondLastLine: false,
            lineNumbers: 'off',
        }

        const currentContents = {!! json_encode($currently['decoded']) !!};
        const currentEditor = monaco.editor.create(document.getElementById('current-container'), {
            ...editorOptions,
            value: currentContents,
        });

        const nextContents = {!! json_encode($next['decoded']) !!};
        const nextEditor = monaco.editor.create(document.getElementById('next-container'), {
            ...editorOptions,
            value: nextContents,
        });

        const currentForm = document.getElementById('currently-form')

        document.getElementById('current-save').addEventListener('click', (e) => {
            e.preventDefault()
            currentForm.contents.value = currentEditor.getValue()

            currentForm.submit()
        })

        const nextForm = document.getElementById('next-form')

        document.getElementById('next-save').addEventListener('click', (e) => {
            e.preventDefault()
            nextForm.contents.value = nextEditor.getValue()

            nextForm.submit()
        })
    </script>

    <hr class="my-6 border-stone-600">

    <form id="form" method="post" action="/media">
        @csrf

        <x-label for="type" value="Type" />
        <x-select class="block mt-1 w-full" name="page" id="page">
            <option value="now">Now</option>
            <option value="next">Next</option>
        </x-select>

        <x-label for="type" value="Type" />
        <x-select class="block mt-1 w-full" name="type" id="type">
            <option value="watching-movie" data-api="tmdb?type=movie&query=">Movie</option>
            <option value="watching-tv" data-api="tmdb?type=tv&query=">TV</option>
            <option value="playing" data-api="giantbomb?query=">Playing</option>
            <option value="reading" data-api="openlib?query=">Reading</option>
        </x-select>

        <x-label for="apisearch" value="Search" />
        <div class="items-center flex">
            <x-input id="apisearch" class="block mt-1 w-full rounded-r-none border-r-0" type="text" name="apisearch" required />
            <button
                id="api-search"
                class="input-button block mt-1 p-3 rounded-l-none text-xs rounded-r border border-stone-700 border-l-0" style="width: 100px;"
            >
                Search
            </button>
        </div>

        <div style="display: none; height: 200px;overflow: auto;" id="results"></div>

        <x-label for="title" value="Title" />
        <x-input id="title" class="block mt-1 w-full" type="text" name="title" required />

        <x-label for="link" value="Link" />
        <x-input id="link" class="block mt-1 w-full" type="text" name="link" required />

        <x-label for="image" value="Image" />
        <x-input id="image" class="block mt-1 w-full" type="text" name="image" required />

        <div class="mt-3 flex justify-end">
            <x-button id="save">
                Create
            </x-button>
        </div>


        <input type="hidden" name="contents" />
    </form>

    <div class="my-6"></div>

    @foreach($data as $type => $d)
        <h1 class="font-bold my-2 uppercase">{{ $type }}</h1>
        @foreach($data[$type] as $r)
            <form
                class="block box p-3 font-mono text-sm hover:cursor-pointer hover:text-pink-500 flex items-center justify-between"
                action="/media/{{ $r->id }}"
                method="POST"
            >
                @csrf
                @method('DELETE')
                <div><i class="{{ $r->icon }}"></i> {{ $r->title }}</div>

                <input type="submit" value="Delete" class="button">
            </form>
        @endforeach
    @endforeach

    <script>
        const form = document.getElementById('form')

        const apiSearch = document.getElementById('api-search')

        apiSearch.addEventListener('click', async (e) => {
            e.preventDefault()
            apiSearch.innerHTML = '<i class="fad fa-spinner fa-pulse"></i>'
            apiSearch.setAttribute('disabled', true)

            const query = document.getElementById('apisearch').value
            const path = document.getElementById('type').selectedOptions[0].dataset.api

            const res = await fetch(`/api/${path}${query}`, {
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
                document.getElementById('title').value = e.currentTarget.dataset.title
                document.getElementById('link').value = e.currentTarget.dataset.link
                document.getElementById('image').value = e.currentTarget.dataset.image
            }

            data.forEach(d => {
                const div = document.createElement('div')
                div.innerHTML = `${d.title} (${d.year}) ${d.author ? `by ${d.author}` : ''}`
                div.classList.add('py-1', 'px-2', 'hover:cursor-pointer', 'hover:bg-pink-200', 'hover:text-black', 'border-b', 'border-stone-700', 'py-2')
                div.dataset.title = d.now_title
                div.dataset.link = d.now_link
                div.dataset.image = d.poster
                div.addEventListener('click', handleSelected)

                results.appendChild(div)
            })

            apiSearch.innerHTML = 'Search'
            apiSearch.setAttribute('disabled', false)
        })
    </script>


</x-app-layout>
