<?php

namespace App\Clients;

use Illuminate\Support\Facades\Http;

class GithubClient {

    const API_PATH = 'https://api.github.com/repos/';

    public function getContents($path)
    {
        $url = self::API_PATH . env('GITHUB_REPO') . '/git/trees/main?recursive=true';
        $response = Http::withHeaders([
            'Authorization' => 'token ' . env('GITHUB_TOKEN'),
            'Content-Type' => 'application/vnd.github.v3+json',
        ])->get($url);

        return $response->json();
    }

    public function getNextImageId(string $path)
    {
        $list = \array_reverse($this->listFiles($path));

        $found = null;

        foreach ($list as $item) {
            if ($item['type'] === 'file') {
                $found = $item['name'];
                break;
            }
        }

        if (\is_null($found)) {
            return 1001;
        }

        $id = explode('.', $found)[0];

        return (string) (((int) $id) + 1);
    }

    public function listFiles($path)
    {
        $url = self::API_PATH . env('GITHUB_REPO') . '/contents/' . $path;
        $response = Http::withHeaders([
            'Authorization' => 'token ' . env('GITHUB_TOKEN'),
            'Content-Type' => 'application/vnd.github.v3+json',
        ])->get($url);

        return $response->json();
    }

    public function updateFile(string $sha, string $path, string $contents)
    {
        $content = \base64_encode($contents);

        $url = self::API_PATH . env('GITHUB_REPO') . '/contents/' . $path;

        $response = Http::withHeaders([
            'Authorization' => 'token ' . env('GITHUB_TOKEN'),
            'Content-Type' => 'application/vnd.github.v3+json',
        ])->put($url, [
            'message' => 'Updated file via KnightCMS',
            'content' => $content,
            'sha' => $sha,
        ]);

        return $response->json();
    }

	public function createFile(string $path, string $contents)
	{
        $content = \base64_encode($contents);

        return $this->upload($path, $content);
	}

    public function uploadImage(string $path, string $imagePath)
    {
        if (($this->listFiles($path)['status'] ?? null) === 404) {
            return null;
        }

        $data = file_get_contents($imagePath);
        $content = base64_encode($data);

        return $this->upload($path, $content);
    }

    private function upload(string $path, string $content)
    {
        $url = self::API_PATH . env('GITHUB_REPO') . '/contents/' . $path;

        $response = Http::withHeaders([
            'Authorization' => 'token ' . env('GITHUB_TOKEN'),
            'Content-Type' => 'application/vnd.github.v3+json',
        ])->put($url, [
            'message' => 'Created via KnightCMS',
            'content' => $content,
        ]);

        return $response->json();
    }
}
