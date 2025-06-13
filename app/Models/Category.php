<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

    public function Books(): BelongsToMany {
        return $this->belongsToMany(Book::class, 'book_categories');
    }
}
