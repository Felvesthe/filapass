<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\CredentialFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'name',
    'login',
    'email',
    'password',
    'url',
    'category_id',
    'user_id',
    'deleted_at',
])]
class Credential extends Model
{
    /** @use HasFactory<CredentialFactory> */
    use HasFactory;

    use SoftDeletes;

    /** @return BelongsTo<Category, $this> */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'login' => 'encrypted',
            'email' => 'encrypted',
            'password' => 'encrypted',
        ];
    }
}
