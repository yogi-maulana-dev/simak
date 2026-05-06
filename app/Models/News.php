<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class News extends Model
{
    protected $table = 'news';

    protected $fillable = [
        'title',
        'slug',
        'category',
        'thumbnail_path',
        'excerpt',
        'body',
        'is_published',
        'is_featured',
        'views',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_featured'  => 'boolean',
        'published_at' => 'datetime',
        'views'        => 'integer',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (News $model) {
            if (empty($model->slug)) {
                $model->slug = static::uniqueSlug($model->title);
            }
            if (empty($model->excerpt) && ! empty($model->body)) {
                $model->excerpt = Str::limit(strip_tags($model->body), 200);
            }
        });

        static::updating(function (News $model) {
            if ($model->isDirty('title') && ! $model->isDirty('slug')) {
                $model->slug = static::uniqueSlug($model->title, $model->id);
            }
            if ($model->isDirty('body') && ! $model->isDirty('excerpt')) {
                $model->excerpt = Str::limit(strip_tags($model->body), 200);
            }
        });
    }

    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $base = $slug;
        $i    = 1;

        while (
            static::where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    public function thumbnailUrl(): string
    {
        if ($this->thumbnail_path && Storage::disk('public')->exists($this->thumbnail_path)) {
            return Storage::disk('public')->url($this->thumbnail_path);
        }
        return asset('images/placeholder.jpg');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function incrementViews(): void
    {
        $this->increment('views');
    }
}