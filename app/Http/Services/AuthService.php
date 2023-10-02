<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Http\Repositories\UserRepository;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Http\Traits\AuthenticationTraits;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AuthService
 *
 * @package \App\Http\Api\V1\Services
 * @author Bolaji Ajani <Bolaji Ajani>
 */
class AuthService extends BaseService
{
    use AuthenticationTraits;

    /**
     * AuthService class
     * @param UserRepository $userRepository
     */
    public function __construct(
        private UserRepository $userRepository
    )
    {
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function signin(LoginRequest $request): JsonResponse
    {
        return $this->performSignin($request);
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    private function performSignin(LoginRequest $request): JsonResponse
    {
        $user = $this->userRepository->findOneBy('email', $request->email);

        if (!$user) {
            return $this->errorResponse(User::USER_NOT_FOUND, Response::HTTP_NOT_FOUND);
        }

        $this->validatePassword($request->password, $user->password);


        return $this->createSigninResponse($user, $request->device_name ?? 'unknown');
    }

    /**
     * validate password
     *
     * @param string $password
     * @param string $hash
     *
     * @return void
     * @throws ValidationException
     */
    private function validatePassword(string $password, string $hash): void
    {
        if (!Hash::check($password, $hash)) {
            throw ValidationException::withMessages([
                'password' => [User::INCORRECT_PASSWORD],
            ]);
        }
    }

    /**
     * Create sign in response
     *
     * @param User $user
     * @param string $deviceName
     * @param int $statusCode
     * @return JsonResponse
     */
    private function createSigninResponse(User $user, string $deviceName = 'unknown', int $statusCode = Response::HTTP_OK): JsonResponse
    {
        return $this->successResponse([
            'user' => new UserResource($user),
            'token' => $this->getAuthData($this->createToken($user, $deviceName))
        ], User::SIGNIN_SUCCESSFUL, $statusCode);
    }

    /**
     * @param User $user
     * @param string $device_name
     * @return string
     */
    public function createToken(User $user, string $device_name = 'unknown'): string
    {
        //we are deleting this to maintain a single token for every user
        $user->tokens()->delete();
        return $user->createToken($device_name)->plainTextToken;
    }

    /**
     * @param string $email
     * @return Model|User
     */
    private function getUserByEmail(string $email): Model|User
    {
        return $this->userRepository->findOneBy('email', $email);
    }
}
