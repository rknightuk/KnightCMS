<?php

namespace App\Clients;

use Carbon\Carbon;
use GuzzleHttp\Client;

class GiantBombClient {

    private Client $client;

    private array $params = [];

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://www.giantbomb.com/api/search',
        ]);

        $this->params = [
            'api_key' => $this->getKey(),
        ];
    }

    private function getKey()
    {
        return env('GIANTBOMB_API_KEY');
    }

    public function find(string $query)
    {
        if (!$this->getKey()) return [];

        $results = $this->get($query);

        return array_map(function ($r) {
            $platforms = array_map(function ($p) {
                return $p->abbreviation;
            }, $r->platforms ?? []);

            return [
                'id' => $r->id,
                'title' => $r->name,
                'meta' => implode(',', $platforms),
                'year' => isset($r->original_release_date) ? (Carbon::createFromFormat('Y-m-d', $r->original_release_date))->year : null,
                'poster' => $r->image->original_url,
                'backdrop' => $r->image->screen_large_url,
                'link' => $r->site_detail_url,
                'now_title' => $r->name,
                'now_link' => $r->site_detail_url,
            ];
        }, $results);
    }

    private function get(string $query)
    {
        $response = $this->client->request('GET', '', [
            'query' => array_merge($this->params, [
                'query' => $query,
                'resources' => 'game',
                'format' => 'json',
                'limit' => 50,
            ])
        ]);

        return json_decode($response->getBody()->getContents())->results;
    }

}
