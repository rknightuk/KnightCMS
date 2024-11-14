<?php

namespace App\Config;

use Carbon\Carbon;

class BookConfig extends PostConfig {

    public function makeFromData(array $data): array
    {
        $permalinkDate = $this->formatDateForPermalink($data['date']);

        $frontMatter = [
            'title' => $data['title'],
            'permalink' => '/' . $this->permalinkPrefix() . '/' . $permalinkDate . '-' . $data['permalink'] . '/index.html',
            'date' => $this->formatDate($data['date']),
            'year' => $data['year'],
            'author' => $data['author'],
            'isbn13' => $data['isbn13'],
        ];

        $frontMatter = array_filter($frontMatter);

        $frontMatter = implode("\n", \array_map(function ($key, $value) {
            return $key . ': ' . $value;
        }, \array_keys($frontMatter), \array_values($frontMatter)));

        $content = \sprintf('---
%s
---

%s',
            $frontMatter,
            $data['contents'],
        );

        $year = (new Carbon($data['date']))->format('Y');

        return [
            $content,
            'src/posts/' . $this->permalinkPrefix() . '/' . $year . '/' . $permalinkDate . '-' . $data['permalink'] . '.md',
        ];
    }

    public function getName(): string
    {
        return 'Book';
    }

    public function permalinkPrefix(): string
    {
        return 'almanac/books';
    }

    public function hasSummary(): bool
    {
        return false;
    }

    public function hasTags(): bool
    {
        return false;
    }

    public function hasProject(): bool
    {
        return false;
    }

    public function isBook(): bool
    {
        return true;
    }
}
