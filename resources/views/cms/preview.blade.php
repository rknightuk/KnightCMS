<html>
<head>

    <link rel="stylesheet" href="/assets/github.css">

    <style>
        body {
            background: #1c1918!important;
        }
        .markdown-body {
            margin: 0 auto;
            max-width: 800px;
            padding-top: 40px;
            padding-bottom: 40px;
        }

        .markdown-body table {
            width: 100%!important;
            display: table;
        }

        .images {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-gap: 20px;
            margin-bottom: 20px;
        }

        .images div {
            border: 1px solid #30363d;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .images div img {
            max-height: 200px;
        }

        .images p {
            font-family: monospace;
            font-size: 0.9em;
            margin-top: 4px;
        }
    </style>
</head>
<body class="markdown-body">
    <table>
        @foreach($fm as $key => $value)
            <tr>
                <td>{{ $key }}</td>
                <td>{{ is_array($value) ? implode(', ', $value) : $value }}</td>
            </tr>
        @endforeach
    </table>

    <div class="images">
        @foreach($images as $image)
            <div>
                <img src="{{ $image['value'] }}" style="max-width: 100%; margin-top: 20px;">
                <p>{{ $image['key'] }}</p>
            </div>
        @endforeach
    </div>

    <div style="border: 1px solid #30363d; padding: 10px;">
        {!! $contents !!}
    </div>
</body>
</html>
