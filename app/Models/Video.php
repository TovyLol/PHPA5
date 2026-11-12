<?php

namespace App\Models;

use Database\Factories\VideoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Video extends Model
{
    /** @use HasFactory<VideoFactory> */
    use HasFactory;

    protected $fillable = [
        'youtube_url',
        'youtube_id',
        'title',
    ];

    // relatie tot band
    public function band(): BelongsTo
    {
        return $this->belongsTo(Band::class);
    }

    // jstum ik haar youtube
    public function embedUrl(): string
    {
        return 'https://www.youtube-nocookie.com/embed/'.$this->youtube_id;
    }
}
