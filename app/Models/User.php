<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    /**
     * @return HasMany<ApiToken>
     */
    public function apiTokens(): HasMany
    {
        return $this->hasMany(ApiToken::class);
    }
}
