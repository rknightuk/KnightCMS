<x-app-layout>
    <x-slot name="header">
        cdn.rknight.me/{{ $path }}
    </x-slot>

    <div class="mb-6 flex gap-2">

        <a href="/uploads" class="button">
            /site
        </a>

        <a href="/uploads?path=files" class="button">
            /files
        </a>

    </div>

    <details class="mb-3 hover:cursor-pointer">
        <summary>Upload File</summary>
        <form method="post" action="/api/file" enctype="multipart/form-data" class="mb-6 border border-stone-700 p-3">
            @csrf
            <x-label for="filename" value="Filename" />
            <x-input id="filename" class="block mt-1 w-full" type="text" name="filename" />

            <x-label for="file" value="File" />
            <x-input type="file" name="file" class="text-sm" />
            <br><br>

            <input type="hidden" name="path" value="{{ $path }}">
            <input type="submit" class="button" value="upload" />
        </form>
    </details>

    <div class="grid grid-cols-4 gap-2 mb-2">
        @foreach($data->slice(0, 12) as $file)
            <div
                class="flex justify-center items-center aspect-square border border-stone-700 overflow-hidden cursor-pointer hover:border-pink-700"
            >
                @if (str_contains($file['ObjectName'], '.jpg') || str_contains($file['ObjectName'], '.jpeg') || str_contains($file['ObjectName'], '.png'))
                    <img src="{{ $cdnUrl }}{{ $file['ObjectName'] }}" data-copy="{{ $cdnUrl }}{{ $file['ObjectName'] }}">
                @else
                    <span
                        class="block font-mono text-sm hover:cursor-pointer hover:text-pink-500 text-wrap"
                        href="{{ $cdnUrl }}{{ $file['ObjectName'] }}"
                    >
                        {{ $file['ObjectName'] }}
                    </span>
                @endif
            </div>
        @endforeach
    </div>

    @foreach($data as $file)
            <span
                class="flex items-center justify-between box p-3 font-mono text-sm hover:cursor-pointer hover:text-pink-500"
                href="#"
                data-copy="{{ $cdnUrl }}{{ $file['ObjectName'] }}"
            >
                <div>
                    {{ $file['ObjectName'] }}
                </div>
                <div>
                    <a href="{{ $cdnUrl }}{{ $file['ObjectName'] }}" target="_blank" class="button">
                        View
                    </a>
                </div>
            </span>
    @endforeach

    <script>
        document.querySelectorAll('[data-copy]').forEach((element) => {
            element.addEventListener('click', (event) => {
                navigator.clipboard.writeText(event.target.getAttribute('data-copy'));
            });
        });
    </script>

</x-app-layout>
