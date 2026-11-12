<?php

namespace App\Models;

use Database\Factories\BandFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Band extends Model
{
    /** @use HasFactory<BandFactory> */
    use HasFactory;

    // velden om een band aan te maken
    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'biography',
        'photo_path',
        'bg_color',
        'text_color',
    ];

    // als de owner lui is geweest maak ik er zelf een aan
    protected static function booted(): void
    {
        static::creating(function (Band $band) {
            if (empty($band->slug)) {
                $band->slug = static::uniqueSlug($band->name);
            }
        });
    }

    public static function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'band';
        $slug = $base;
        $i = 2;

        while (static::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    // admin group
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
    // relatie tot videos
    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }
}
