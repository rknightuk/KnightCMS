<?php

namespace App\Http\Controllers;

use App\Clients\GithubClient;
use App\Config\GameConfig;
use App\Config\PostConfig;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\Autolink\AutolinkExtension;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\Footnote\FootnoteExtension;
use League\CommonMark\MarkdownConverter;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class PostController extends Controller
{

    public function __construct(private GithubClient $client) {}

    public function newPost()
    {
        $apiData = Http::get('https://rknight.me/api/cms.json')->json();

        $config = PostConfig::makeFromType(\request('type'));

        $type = \request('type');

        $now = Carbon::now();
        $platforms = GameConfig::PLATFORMS;

        return view('cms.new', \compact(
            'type',
            'now',
            'apiData',
            'config',
            'platforms'
        ));
    }

    public function generatePreview()
    {
        $contents = base64_encode(\request('contents'));

        $path = '/preview?contents=' . \urlencode($contents);

        $formData = \request('formData');
        if ($formData) {
            unset($formData['type']);
            unset($formData['_token']);
            unset($formData['contents']);

            $formData = \base64_encode(\json_encode($formData));
            $path = $path . '&data=' . $formData;
        }

        return $path;
    }

    public function showPreview()
    {
        $contents = base64_decode(\request('contents'));

        $parsed = YamlFrontMatter::parse($contents);

        $fm = \request('data') ? (array) \json_decode(base64_decode(\request('data'))) : $parsed->matter();
        $environment = new Environment([]);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new FootnoteExtension());
        $environment->addExtension(new AutolinkExtension());
        $contents = (new MarkdownConverter($environment))->convert($parsed->body())->getContent();

        $images = [];

        foreach ($fm as $key => $value) {
            if (\is_string($value) && (\str_contains($value, '.jpg') || \str_contains($value, '.png'))) {
                $images[] = [
                    'key' => $key,
                    'value' => $value,
                ];
            }
        }

        return view('cms.preview', \compact('contents', 'fm', 'images'));
    }

    public function files()
    {
        $path = '/' . request('path');

        $files = [];
        $contents = null;
        $language = 'markdown';

        if (\request('file')) {
            $contents = $this->client->listFiles($path);
            $contents['decoded'] = \base64_decode($contents['content']);
            $extension = \pathinfo($path, PATHINFO_EXTENSION);

            switch ($extension) {
                case 'md':
                    $language = 'markdown';
                    break;
                case 'html':
                    $language = 'html';
                    break;
                case 'css':
                    $language = 'css';
                    break;
                case 'js':
                    $language = 'javascript';
                    break;
                case 'json':
                    $language = 'json';
                    break;
                case 'njk':
                    $language = 'handlebars';
                    break;
                default:
                    $language = 'plaintext';
                    break;
            }
        } else {
            $files = \array_reverse($this->client->listFiles($path));
        }

        $showPreview = isset($extension) && \in_array($extension, ['md', 'njk']);

        return view('cms.posts', \compact('path', 'files', 'contents', 'language', 'showPreview'));
    }

}
