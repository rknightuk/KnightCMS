<x-app-layout>
    <x-slot name="header">
        {{ $path }}
    </x-slot>

    @foreach($files as $file)
        <a
            class="block box p-3 font-mono text-sm hover:cursor-pointer hover:text-pink-500"
            href="{{ $file['type'] === 'dir' ? '?path=' . $file['path'] : '?file=true&path=' . $file['path'] }}"
        >
            @if ($file['type'] === 'dir')
                <i class="fas fa-folder text-white"></i> {{ $file['name'] }}
            @else
                <i class="fad fa-file text-white"></i> {{ $file['name'] }}
            @endif
        </a>
    @endforeach

    @if ($contents)
        <form id="form" method="post" action="/file/{{ $contents['sha'] }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="path" value="{{ $contents['path'] }}" />
            <textarea
                id="contents"
                class="border-stone-300 dark:border-stone-700 dark:bg-stone-900 dark:text-stone-300 focus:border-stone-500 dark:focus:border-stone-600 focus:ring-stone-500 dark:focus:ring-stone-600 rounded-md shadow-sm w-full font-mono"
                name="contents"
                rows="30">{{ $contents['decoded'] }}</textarea>
        </form>

        <div class="mt-3 flex justify-between">
            @if ($showPreview)
                <x-button id="preview">
                    Preview
                </x-button>
           @endif
            <x-button id="save">
                Save
            </x-button>
        </div>

        <script>
            const form = document.getElementById('form')

            document.getElementById('save').addEventListener('click', (e) => {
                e.preventDefault()

                form.submit()
            })

            document.getElementById('preview').addEventListener('click', async (e) => {
                e.preventDefault()
                const contents = document.getElementById('contents').value

                const res = await fetch('/preview', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ contents }),
                })

                const url = await res.text()

                window.open(url)
            })
        </script>
    @endif
</x-app-layout>
