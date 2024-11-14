<?php

namespace App\Config;

use Carbon\Carbon;
use function Laravel\Prompts\form;

abstract class PostConfig {

    const BLOG = 1;
    const NOTE = 2;
    const LINK = 3;
    const CHANGELOG = 4;
    const MOVIE = 5;
    const TV = 6;
    const GAME = 7;
    const BOOK = 8;

    const IMAGE_PATH = [
        self::MOVIE => 'movie',
        self::TV => 'tv',
        self::GAME => 'game',
        self::BOOK => 'book',
    ];

    public static function makeFromType(int $type): PostConfig
    {
        switch ($type) {
            case self::BLOG:
                return new BlogConfig();
            case self::NOTE:
                return new NoteConfig();
            case self::LINK:
                return new LinkConfig();
            case self::MOVIE:
                return new MovieConfig();
            case self::TV:
                return new TvConfig();
            case self::GAME:
                return new GameConfig();
            case self::BOOK:
                return new BookConfig();
            default:
                throw new \Exception('Invalid type');
        }
    }

    public abstract function getName(): string;

    public abstract function permalinkPrefix(): string;

    public function hasTitle(): bool
    {
        return true;
    }

    public function hasPermalink(): bool
    {
        return true;
    }

    public function hasLink(): bool
    {
        return false;
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

    public function hasTmdbSearch(): bool
    {
        return false;
    }

    public function hasCinema(): bool
    {
        return false;
    }

    public function isTV(): bool
    {
        return false;
    }

    public function isGame(): bool
    {
        return false;
    }

    public abstract function makeFromData(array $data): array;

    public function formatDate(string $date): string
    {
        return (new Carbon($date))->format('Y-m-d\TH:i:s.000\Z');
    }

    public function formatDateForPermalink(string $date): string
    {
        return (new Carbon($date))->format('Y-m-d');
    }

    public function isBook(): bool
    {
        return false;
    }

}
