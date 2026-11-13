<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Agenda extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'event_date' => 'date',
        'published_at' => 'datetime',
        'gallery_images' => 'array',
    ];

    public function getYoutubeEmbedUrlAttribute(): ?string
    {
        $youtubeUrl = $this->youtube_url;

        if (! $youtubeUrl) {
            return null;
        }

        $host = strtolower((string) parse_url($youtubeUrl, PHP_URL_HOST));
        $path = (string) parse_url($youtubeUrl, PHP_URL_PATH);
        $videoId = null;

        if (in_array($host, ['youtu.be', 'www.youtu.be'], true)) {
            $videoId = trim($path, '/');
        } elseif (in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com'], true)) {
            parse_str((string) parse_url($youtubeUrl, PHP_URL_QUERY), $query);
            $videoId = $query['v'] ?? null;

            if (! $videoId && preg_match('~^/(?:embed|shorts|live)/([^/]+)~', $path, $matches)) {
                $videoId = $matches[1];
            }
        }

        if (! is_string($videoId) || ! preg_match('/^[A-Za-z0-9_-]{11}$/', $videoId)) {
            return null;
        }

        return 'https://www.youtube-nocookie.com/embed/'.$videoId;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
