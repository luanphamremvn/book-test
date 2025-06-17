<?php

namespace App\Models;

use App\Observers\BookObserver;
use App\Services\UploadFileService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Application;

/**
 * @property array $categoryIds
 * @property string $description
 * @property string $image
 * @property int $id
 * @property BelongsToMany $categories
 */
class Book extends Model
{
    use HasFactory;
    /**
     * The service for handling file uploads.
     *
     * @var UploadFileService|Application|object
     */
    protected UploadFileService $uploadFileService;

    /**
     * The table associated with the model.
     *
     * @var string $primaryKey
     */
    protected $primaryKey = 'book_id';

    /**
     * The table associated with the model.
     *
     * @var string[] $fillable
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'author',
        'published_at',
        'image'
    ];

    public function __construct(array $attributes = [])
    {
        $this->uploadFileService = app(UploadFileService::class);
        parent::__construct($attributes);
    }

    /**
     * Define the relationship between Book and Category models.
     *
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'book_categories', 'book_id', 'category_id');
    }

    /**
     * Retrieve the image URL; if no image is available, use the default image.
     *
     * @return Attribute
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->uploadFileService->getFile($this->image) ?? $this->uploadFileService->getNoImageUrl(),
        );
    }

    /**
     * Get a shortened version of the book description, limited to 150 characters.
     *
     * @return Attribute
     */
    public function shortDescription(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->shortenString($this->description),
        );
    }

    /**
     * get all the category names of the book separated by commas

     * @return Attribute
     */
    public function categoriesName(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->categories->pluck('name')->join(', '),
        );
    }


    /**
     * Shortens the string if it is longer than 150 characters, appending '...'.
     *
     * @param string $string
     * @return string
     */
    private function shortenString(string $string): string
    {
        if (strlen($string) > 150) {

            return substr($string, 0, 150) . '...';
        }

        return $string;
    }

    /**
     * Adds search conditions to the query for name, author, or description fields.
     *
     * @param Builder $query
     * @param string $search
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $search = ''): Builder
    {
        return $query->where('name', 'LIKE', '%' . $search . '%')
            ->orWhere('author', 'LIKE', '%' . $search . '%')
            ->orWhere('description', 'LIKE', '%' . $search . '%');
    }

    /**
     * Bootstrap the model and its traits
     *
     * @return void
     */
    public static function boot(): void
    {
        parent::boot();
        Book::observe(BookObserver::class);
    }
}
