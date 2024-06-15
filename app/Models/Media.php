<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $table = 'media';

    protected $fillable = [
        'title',
        'link',
        'image',
        'type',
        'page',
    ];

    public function getIconAttribute()
    {
        if (\str_contains($this->link, 'themoviedb.org/movie')) {
            return 'fad fa-popcorn';
        }
        return match ($this->type) {
            'watching' => 'fad fa-tv-retro',
            'playing' => 'fad fa-gamepad',
            'reading' => 'fad fa-books',
        };
    }
}
