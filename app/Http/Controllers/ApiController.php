<?php

namespace App\Http\Controllers;

use App\Clients\GiantBombClient;
use App\Clients\OpenLibraryClient;
use App\Clients\TheMovieDbClient;
use App\Models\Media;
use Carbon\Carbon;
use DOMDocument;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use League\Flysystem\Filesystem;
use PlatformCommunity\Flysystem\BunnyCDN\BunnyCDNAdapter;
use PlatformCommunity\Flysystem\BunnyCDN\BunnyCDNClient;
use PlatformCommunity\Flysystem\BunnyCDN\BunnyCDNRegion;

class ApiController extends Controller
{
    public function __construct(
        private TheMovieDbClient $movieDbClient,
        private GiantBombClient $giantBombClient,
        private OpenLibraryClient $openLibraryClient,
    ) {}

    const FEED_SELECTORS = [
        'application/rss+xml',
        'application/atom+xml',
        'application/json',
    ];

    public function tmdb()
    {
        $query = request('query');
        $type = request('type', 'movie');

        if ($type === 'tv') {
            $results = $this->movieDbClient->findTV($query);
        } else {
            $results = $this->movieDbClient->findMovie($query);
        }

        return $results;
    }

    public function giantbomb()
    {
        $query = request('query');

        return $this->giantBombClient->find($query);
    }

    public function openLib()
    {
        $query = request('query');

        return $this->openLibraryClient->find($query);
    }

    public function link()
    {
        $link = request('link');
        $domain = \parse_url($link, \PHP_URL_SCHEME) . '://' . \parse_url($link, PHP_URL_HOST);

        $response = Http::get($link);

        $data = [
            'title' => null,
            'name' => null,
            'website' => $domain,
            'feed' => null,
            'mastodon' => [],
        ];

        $document = new DOMDocument();
        @$document->loadHTML($response->body());

        $titles = $document->getElementsByTagName('title');

        foreach ($titles as $title) {
            $data['title'] = $title->textContent;
            break;
        }

        $headLinks = $document->getElementsByTagName('link');

        foreach ($headLinks as $headLink) {
            $type = $headLink->getAttribute('type');

            if (\in_array($type, self::FEED_SELECTORS)) {
                $feedLink = $headLink->getAttribute('href');
                if (\str_starts_with($feedLink, '/')) {
                    $feedLink = $domain . $feedLink;
                }
                $data['feed'] = $feedLink;
                break;
            }
        }

        $links = $document->getElementsByTagName('a');

        foreach ($links as $link) {
            $rel = $link->getAttribute('rel');
            $value = $link->getAttribute('href');

            if ($rel && $rel === 'me' && \str_contains($value, '@')) {
                $data['mastodon'][] = $value;
            }

            if ($rel && $rel === 'author') {
                $data['name'] = $link->textContent;
            }
        }

        $data['mastodon'] = implode(',', array_unique($data['mastodon']));

        return $data;
    }

    public function uploadFile()
    {
        $path = \request('path', 'site/2026');

        $adapter = new BunnyCDNAdapter(
            new BunnyCDNClient(
                env('BUNNY_STORAGE_ZONE'),
                env('BUNNY_API_KEY'),
                BunnyCDNRegion::FALKENSTEIN
            ),
            env('BUNNY_PULLZONE')
        );
        $filesystem = new Filesystem($adapter);

        $file = \request()->file('file');

        $filename = $file->getClientOriginalName();

        if (\request('filename')) {
            $filename = \request('filename') . '.' . $file->extension();
        }

        $filesystem->writeStream($path . '/' . $filename, fopen($file->getRealPath(), 'r+'));

        Cache::forget('files-' . $path);

        return \redirect()->route('dashboard.uploads', ['path' => $path]);
    }

    public function listFiles()
    {
        $path = \request('path', 'site/2026');

        $cacheKey = 'files-' . $path;

        $data = Cache::remember($cacheKey, Carbon::now()->addMinutes(5), function () use ($path) {
            $files = Http::withHeaders([
                'AccessKey' => env('BUNNY_API_KEY'),
                'Accept' => 'application/json',
            ])->get('https://storage.bunnycdn.com/rknightuk/' . $path . '/');

            return collect($files->json())
                ->sortByDesc('DateCreated')
                ->values();
        });

        $cdnUrl = 'https://cdn.rknight.me/' . $path . '/';

        return view('cms.uploads', compact('data', 'cdnUrl', 'path'));
    }

    public function media()
    {
        return [
            'now' => Media::all()->where('page', 'now')->groupBy('type'),
            'next' => Media::all()->where('page', 'next')->groupBy('type'),
        ];
    }
}
