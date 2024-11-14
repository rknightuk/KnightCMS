<?php

namespace App\Config;

use Carbon\Carbon;

class GameConfig extends PostConfig {

    const PLATFORMS = [
        'PS5',
        'PS4',
        'Xbox One',
        'Switch',
        'PS1',
        'PS2',
        'PS3',
        'PSP',
        'PSVita',
        'Xbox',
        'Xbox 360',
        'NES',
        'SNES',
        'N64',
        'GameCube',
        'Wii',
        'Wii U',
        'Game Boy',
        'Game Boy Color',
        'Game Boy Advance',
        'DS',
        '3DS',
        'Master System',
        'Mega Drive',
        'Saturn',
        'Dreamcast',
        'Game Gear',
        'iOS',
        'Android',
        'PC',
        'macOS',
        'Linux',
        'Other',
    ];

    public function makeFromData(array $data): array
    {
        $permalinkDate = $this->formatDateForPermalink($data['date']);

        $frontMatter = [
            'title' => $data['title'],
            'permalink' => '/' . $this->permalinkPrefix() . '/' . $permalinkDate . '-' . $data['permalink'] . '/index.html',
            'date' => $this->formatDate($data['date']),
            'year' => $data['year'],
            'platform' => $data['platform'],
            'giantbombid' => $data['giantbombid'],
            'giantbomburl' => $data['giantbomburl'],
        ];

        if ($data['custom_poster']) $frontMatter['customImage'] = $data['custom_poster'];

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
        return 'Game';
    }

    public function permalinkPrefix(): string
    {
        return 'almanac/games';
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

    public function isGame(): bool
    {
        return true;
    }
}
