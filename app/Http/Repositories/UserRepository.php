<?php

declare(strict_types=1);

namespace App\Http\Repositories;

use App\Models\User;

/**
 * Class AdminRepository
 *
 * @package \App\Http\Api\V1\Repositories
 * @author Bolaji Ajani <Bolaji Ajani>
 */
class UserRepository extends BaseRepository
{

    /**
     * UserRepository constructor.
     */
    public function __construct()
    {
        parent::__construct(User::class);
    }

}
