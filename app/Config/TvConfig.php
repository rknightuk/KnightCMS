<?php

namespace App\Config;

use Carbon\Carbon;

class TvConfig extends PostConfig {

    public function makeFromData(array $data): array
    {
        $permalinkDate = (new Carbon($data['date']))->format('Y-m-d');

        $frontMatter = [
            'title' => $data['title'],
            'permalink' => '/' . $this->permalinkPrefix() . '/' . $permalinkDate . '-' . $data['permalink'] . '/index.html',
            'date' => $this->formatDate($data['date']),
            'year' => $data['year'],
            'season' => $data['season'],
            'tmdbid' => $data['tmdbid'],
        ];

        if ($data['custom_poster']) $frontMatter['customImage'] = $data['custom_poster'];
        if ($data['custom_backdrop']) $frontMatter['customBackdrop'] = $data['custom_backdrop'];

        $frontMatter = array_filter($frontMatter);

        $frontMatter = implode("\n", \array_map(function ($key, $value) {
            if (\in_array($key, ['title'])) {
                return $key . ': "' . $value . '"';
            }
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
        return 'TV';
    }

    public function permalinkPrefix(): string
    {
        return 'almanac/tv';
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

    public function hasTmdbSearch(): bool
    {
        return true;
    }

    public function isTV(): bool
    {
        return true;
    }
}
