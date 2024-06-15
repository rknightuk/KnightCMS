<?php

namespace App\Http\Controllers;

use App\Clients\GithubClient;
use App\Models\Media;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    const CURRENTLY_PATH = 'src/_includes/currently.md';
    const NEXT_PATH = 'src/_includes/next.md';

    public function __construct(private GithubClient $client) {}

    public function index()
    {
        $data = [
            'now' => Media::all()->where('page', 'now'),
            'next' => Media::all()->where('page', 'next'),
        ];

        $currently = $this->client->listFiles(self::CURRENTLY_PATH);
        $currently['decoded'] = \base64_decode($currently['content']);

        $next = $this->client->listFiles(self::NEXT_PATH);
        $next['decoded'] = \base64_decode($next['content']);

        return view('cms.media', \compact('data', 'currently', 'next'));
    }

    public function create()
    {
        $data = \request()->all();

        Media::create([
            'title' => $data['title'],
            'link' => $data['link'],
            'image' => $data['image'],
            'type' => \str_contains($data['type'], 'watching') ? 'watching' : $data['type'],
            'page' => $data['page'],
        ]);

        return \redirect()->route('media.index');
    }

    public function delete(int $id)
    {
        Media::destroy($id);

        return \redirect()->route('media.index');
    }
}
