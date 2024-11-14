<?php

namespace App\Config;

use Carbon\Carbon;

class BlogConfig extends PostConfig {

    public function makeFromData(array $data): array
    {
        $frontMatter = [
            'title' => $data['title'],
            'permalink' => '/' . $this->permalinkPrefix() . '/' . $data['permalink'] . '/index.html',
            'date' => $this->formatDate($data['date']),
            'excerpt' => $data['summary'],
        ];

        if ($data['project'] ?? false) {
            $frontMatter['project'] = $data['project'];
        }

        if ($data['tags'] ?? false) {
            $frontMatter['tags'] = sprintf('[%s]', $data['tags']);
        }

        $frontMatter =  implode("\n", \array_map(function ($key, $value) {
            if (\in_array($key, ['title', 'excerpt'])) {
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
            sprintf(
                'src/posts/%s/%s/%s-%s.md',
                $this->permalinkPrefix(),
                $year,
                $this->formatDateForPermalink($data['date']),
                $data['permalink']
            )
        ];
    }

    public function getName(): string
    {
        return 'Blog';
    }

    public function permalinkPrefix(): string
    {
        return 'blog';
    }

    public function hasTitle(): bool
    {
        return true;
    }

    public function hasSummary(): bool
    {
        return true;
    }

    public function hasTags(): bool
    {
        return true;
    }

    public function hasProject(): bool
    {
        return true;
    }

    public function hasAuthorDetails(): bool
    {
        return false;
    }
}
