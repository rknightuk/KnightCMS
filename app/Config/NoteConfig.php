<?php

namespace App\Config;

use Carbon\Carbon;

class NoteConfig extends PostConfig {

    public function makeFromData(array $data): array
    {
        $permalinkDate = (new Carbon($data['date']))->format('YmdHi');
        $frontMatter = [
            'permalink' => '/' . $this->permalinkPrefix() . '/' . $permalinkDate . '/index.html',
            'date' => $this->formatDate($data['date']),
        ];

        if ($data['tags'] ?? false) {
            $frontMatter['tags'] = sprintf('[%s]', $data['tags']);
        }

        $frontMatter =  implode("\n", \array_map(function ($key, $value) {
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
            'src/posts/' . $this->permalinkPrefix() . '/' . $year . '/' . $permalinkDate . '.md',
        ];
    }

    public function getName(): string
    {
        return 'Note';
    }

    public function permalinkPrefix(): string
    {
        return 'notes';
    }

    public function hasPermalink(): bool
    {
        return false;
    }

    public function hasTitle(): bool
    {
        return false;
    }

    public function hasSummary(): bool
    {
        return false;
    }

    public function hasTags(): bool
    {
        return true;
    }

    public function hasProject(): bool
    {
        return false;
    }
}
