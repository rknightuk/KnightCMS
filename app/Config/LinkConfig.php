<?php

namespace App\Config;

use Carbon\Carbon;
use Symfony\Component\Yaml\Yaml;

class LinkConfig extends PostConfig {

    public function makeFromData(array $data): array
    {
        $frontMatter = [
            'title' => $data['title'],
            'permalink' => '/' . $this->permalinkPrefix() . '/' . $data['permalink'] . '/index.html',
            'date' => $this->formatDate($data['date']),
            'link' => $data['link'],
        ];

        $authorData = [
            'author' => [
                'name' => $data['author_name'],
                'web' => $data['author_web'],
                'feed' => $data['author_feed'],
                'mastodon' => $data['author_mastodon'],
            ]
        ];
        $authorData['author'] = array_filter($authorData['author']);
        $authorData = Yaml::dump($authorData);

        $frontMatter =  implode("\n", \array_map(function ($key, $value) {
            return $key . ': ' . $value;
        }, \array_keys($frontMatter), \array_values($frontMatter)));

        $content = \sprintf('---
%s
%s
---

%s',
            $frontMatter,
            $authorData,
            $data['contents'],
        );

        $year = (new Carbon($data['date']))->format('Y');

        return [
            $content,
            'src/posts/' . $this->permalinkPrefix() . '/' . $year . '/' . $data['permalink'] . '.md',
        ];
    }

    public function getName(): string
    {
        return 'Link';
    }
    public function permalinkPrefix(): string
    {
        return 'links';
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

    public function hasAuthorDetails(): bool
    {
        return true;
    }

    public function hasLink(): bool
    {
        return true;
    }

}
