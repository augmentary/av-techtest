<?php

declare(strict_types=1);

namespace App\Auth;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ApiTokenUserFetcher
{
    public function __invoke(Request $request): ?User
    {
        $header = $request->header('Authorization');

        if ($header !== null && str_starts_with($header, 'Bearer ')) {
            $token = substr($header, 7);

            return User::whereHas('apiTokens', static function (Builder $query) use ($token) {
                $query->where('token', $token);
            })->first();
        }

        return null;
    }
}
