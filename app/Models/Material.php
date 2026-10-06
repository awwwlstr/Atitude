<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'creator_id',
        'category_id',
        'title',
        'slug',
        'description',
        'content',
        'cover_image',
        'status',
        'revision_note',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($material) {
            if (empty($material->slug)) {
                $material->slug = Str::slug($material->title) . '-' . Str::random(6);
            }
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function contents(): HasMany
    {
        return $this->hasMany(MaterialContent::class)->orderBy('order', 'asc');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)->orderBy('order', 'asc');
    }

    public function progress(): HasMany
    {
        return $this->hasMany(Progress::class);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'bg-secondary',
            'pending' => 'bg-warning text-dark',
            'approved' => 'bg-info text-dark',
            'revision' => 'bg-danger',
            'published' => 'bg-success',
            default => 'bg-secondary',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'pending' => 'Menunggu Review',
            'approved' => 'Disetujui',
            'revision' => 'Perlu Revisi',
            'published' => 'Dipublikasikan',
            default => ucfirst($this->status),
        };
    }

    public function getCoverImageUrlAttribute(): string
    {
        if ($this->cover_image && file_exists(public_path('storage/' . $this->cover_image))) {
            return asset('storage/' . $this->cover_image);
        }
        return 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?auto=format&fit=crop&w=600&q=80';
    }
}
