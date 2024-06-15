<?php

namespace App\Http\Controllers;

use App\Clients\GithubClient;
use App\Config\PostConfig;
use Illuminate\Support\Facades\Http;
use Spatie\TemporaryDirectory\TemporaryDirectory;

class FileController extends Controller
{
    public function __construct(private GithubClient $client) {}

    public function create()
    {
        $data = \request()->all();

        $imageData = $this->handleImages($data);

        $data = \array_merge($data, $imageData);

        $config = PostConfig::makeFromType(\request('type'));

        [$contents, $path] = $config->makeFromData($data);

        $response = $this->client->createFile($path, $contents);

        $message = \sprintf('File created! <a href="%s" target="_blank">View commit</a>', $response['commit']['html_url']);

        return redirect()->route('dashboard')->with('success', $message);
    }

    public function update(string $sha)
    {
        $contents = \request('contents');
        $path = \request('path');

        $response = $this->client->updateFile($sha, $path, $contents);

        $message = \sprintf('File updated successfully! <a href="%s" target="_blank">View commit</a>', $response['commit']['html_url']);

        return redirect()->route('dashboard')->with('success', $message);
    }

    private function handleImages(array $data): array
    {
        $idKey = 'tmdbid';
        switch ($data['type']) {
            case PostConfig::GAME:
                $idKey = 'giantbombid';
                break;
            case PostConfig::MOVIE:
            case PostConfig::TV:
                $idKey = 'tmdbid';
                break;
            case PostConfig::BOOK:
                $idKey = 'isbn13';
                break;
        }

        $imageKeys = ['poster', 'backdrop', 'custom_poster', 'custom_backdrop'];
        $images = \array_filter(array_filter($data, function ($v) use ($imageKeys) {
            return in_array($v, $imageKeys);
        }, ARRAY_FILTER_USE_KEY));

        if (count($images) === 0) {
            return [];
        }

        $rootPath = 'src/assets/catalog/almanac/' . PostConfig::IMAGE_PATH[\request('type')] . '/';

        $uploaded = [];

        foreach ($images as $key => $image) {
            $extension = \pathinfo(str($image), PATHINFO_EXTENSION);
            $url = str($image)->replace(' ', '%20');
            $tmpFile = time() . \md5($url->afterLast('/')) . '.' . $extension;

            $tmpDir = TemporaryDirectory::make()->deleteWhenDestroyed();
            $tmpPath = $tmpDir->path($tmpFile);
            Http::sink($tmpPath)->throw()->get($url->toString());

            if (\in_array($key, ['poster', 'backdrop'])) {
                $path = $rootPath . (\str_contains($key, 'backdrop') ? 'bd/' : '') . $data[$idKey] . '.' . $extension;

                $this->client->uploadImage($path, $tmpPath);
            } else if (\in_array($key, ['custom_poster', 'custom_backdrop'])) {
                $customId = $this->client->getNextImageId($rootPath . 'custom/');

                $path = $rootPath . 'custom/' . $customId . '.' . $extension;

                $this->client->uploadImage($path, $tmpPath);

                $uploaded[$key] = $customId;
            }
        }

        return $uploaded;
    }
}
