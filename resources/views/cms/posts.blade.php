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
        <div id="container" class="w-full" style="height: 500px;"></div>

        <form id="form" method="post" action="/file/{{ $contents['sha'] }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="path" value="{{ $contents['path'] }}" />
            <input type="hidden" name="contents" />
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
            var require = { paths: { vs: '/assets/monaco/vs' } };
        </script>
        <script src="/assets/monaco/vs/loader.js"></script>
        <script src="/assets/monaco/vs/editor/editor.main.nls.js"></script>
        <script src="/assets/monaco/vs/editor/editor.main.js"></script>

        <script>
            const contents = {!! json_encode($contents['decoded']) !!};
            const editor = monaco.editor.create(document.getElementById('container'), {
                value: contents,
                language: '{{ $language }}',
                theme: 'vs-dark',
                minimap: { enabled: false },
                scrollbar: { vertical: 'hidden', horizontal: 'hidden' },
                wordWrap: 'on',
                fontSize: 16,
                scrollBeyondLastLine: false,
                lineNumbers: 'off',
            });

            const form = document.getElementById('form')

            document.getElementById('save').addEventListener('click', (e) => {
                e.preventDefault()
                form.contents.value = editor.getValue()

                form.submit()
            })

            document.getElementById('preview').addEventListener('click', async (e) => {
                e.preventDefault()
                const contents = editor.getValue()

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
