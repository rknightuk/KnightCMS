<?php

namespace App\Clients;

use Carbon\Carbon;
use GuzzleHttp\Client;

class TheMovieDbClient {

    const POSTER_PATH = 'https://image.tmdb.org/t/p/w300';
    const BACKDROP_PATH = 'https://image.tmdb.org/t/p/w780';

    private Client $client;
    private array $params = [];

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.themoviedb.org/3/search/',
        ]);

        $this->params = [
            'api_key' => $this->getKey(),
            'language' => 'en-US',
        ];
    }

    private function getKey()
    {
        return env('TMDB_API_KEY');
    }

    public function findMovie(string $query)
    {
        if (!$this->getKey()) return [];

        $results = $this->get('movie', $query);

        return array_map(function($r) {
            $year = (isset($r->release_date) && $r->release_date) ? (Carbon::createFromFormat('Y-m-d', $r->release_date))->year : null;
            return [
                'id' => $r->id,
                'link' => 'https://themoviedb.org/movie/' . $r->id,
                'title' => $r->title,
                'year' => $year,
                'poster' => $r->poster_path ? self::POSTER_PATH . $r->poster_path : null,
                'backdrop' => $r->backdrop_path ? self::BACKDROP_PATH . $r->backdrop_path : null,
                'now_title' => sprintf('%s (%s)', $r->title, $year),
                'now_link' => sprintf('https://www.themoviedb.org/movie/%s', $r->id),
            ];
        }, $results);
    }

    public function findTV(string $query)
    {
        if (!$this->getKey()) return [];

        $results = $this->get('tv', $query);

        return array_map(function ($r) {
            $year = isset($r->first_air_date) && !empty($r->first_air_date) ? (Carbon::createFromFormat('Y-m-d', $r->first_air_date))->year : null;
            return [
                'id' => $r->id,
                'link' => 'https://themoviedb.org/tv/' . $r->id,
                'title' => $r->name,
                'year' => $year,
                'poster' => $r->poster_path ? self::POSTER_PATH . $r->poster_path : null,
                'backdrop' => $r->backdrop_path ? self::BACKDROP_PATH . $r->backdrop_path : null,
                'now_title' => sprintf('%s (%s)', $r->name, $year),
                'now_link' => sprintf('https://www.themoviedb.org/tv/%s', $r->id)
            ];
        }, $results);
    }

    private function get(string $type, string $query)
    {
        $response = $this->client->request('GET', $type, [
            'query' => array_merge($this->params, [
                'query' => $query
            ])
        ]);

        return json_decode($response->getBody()->getContents())->results;
    }

}
