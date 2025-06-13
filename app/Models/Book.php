<?php

namespace App\Models;

use App\Observers\BookObserver;
use App\Services\UploadFileService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property mixed $categoryIds
 * @property mixed $categories
 * @property mixed $description
 * @property mixed $image
 * @property mixed $id
 */
class Book extends Model
{
    protected mixed $uploadFileService;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->uploadFileService = app(UploadFileService::class);
    }

    protected $fillable = [
        'name',
        'slug',
        'description',
        'author',
        'published_at',
        'image'
    ];

    public function Categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'book_categories');
    }

    /**
     * Get image
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->uploadFileService->getFile($this->image) ?? $this->uploadFileService->getNoImageUrl(),
        );
    }

    public function shortDescription(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->shortenString($this->description),
        );
    }

    public function categoriesName(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->categories->pluck('name')->join(', '),
        );
    }

    private function shortenString($string): string
    {
        if (strlen($string) > 150) {
            return substr($string, 0, 150) . '...';
        }
        return $string;
    }

    public function scopeSearch($query, $search = '')
    {
        return $query->where('name', 'LIKE', '%' . $search . '%')
            ->orWhere('author', 'LIKE', '%' . $search . '%')
            ->orWhere('description', 'LIKE', '%' . $search . '%');
    }

    public static function boot(): void
    {
        parent::boot();
        Book::observe(BookObserver::class);
    }
}
