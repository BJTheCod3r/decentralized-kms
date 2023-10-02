<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Services\AuthService;
use App\Http\Traits\AuthenticationTraits;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

/**
 * Class AuthController
 *
 * @package \App\Api\V2\Controllers\Authentication
 * @author Bolaji Ajani <fabulousbj@hotmail.com>
 */
class AuthController extends Controller
{
    use AuthenticationTraits;

    /**
     * @var AuthService $authService
     */
    public function __construct(private AuthService $authService)
    {
    }

    /**
     * Login user
     *
     * @param LoginRequest $request
     * @throws ValidationException
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        return $this->authService->signin($request);
    }
}


