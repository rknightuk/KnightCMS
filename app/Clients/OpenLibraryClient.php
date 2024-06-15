<?php

namespace App\Clients;

use Illuminate\Support\Facades\Http;

class OpenLibraryClient
{
    const BASE_PATH = 'https://openlibrary.org/search.json';

    public function find(string $query)
    {
        $response = Http::get(self::BASE_PATH, [
            'q' => $query,
        ]);

        $results = $response->json()['docs'] ?? [];

        return array_map(function ($r) {
            $authors = implode(', ', ($r['author_name'] ?? []));
            return [
                'title' => $r['title'],
                'author' => $authors,
                'poster' => isset($r['cover_i']) ? \sprintf('https://covers.openlibrary.org/b/id/%s-L.jpg', $r['cover_i']) : null,
                'isbn' => collect($r['isbn'] ?? [])->first(function($isbn) {
                    return \strlen($isbn) === 13;
                }),
                'year' => $r['first_publish_year'] ?? null,
                'now_title' => \sprintf('%s - %s', $r['title'], $authors),
                'now_link' => \sprintf('https://openlibrary.org%s', $r['key']),
            ];
        }, $results);
    }
}
