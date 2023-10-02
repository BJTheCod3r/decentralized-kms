<?php

declare(strict_types=1);

namespace App\Http\Traits;


/**
 * Trait AuthenticationTraits
 *
 * @package App\Http\Api\V1\Traits
 * @author  Bolaji Ajani <fabulousbj@hotmail.com>
 */
trait AuthenticationTraits
{
    /**
     * get auth data
     *
     * @param string $token
     * @return array
     */
    private function getAuthData(string $token): array
    {
        return [
            "token_type" => "Bearer",
            "access_token" => $token,
            "expires_in" => config('sanctum.expiration')
        ];
    }
}
