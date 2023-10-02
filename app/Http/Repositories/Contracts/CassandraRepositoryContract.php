<?php

declare(strict_types=1);

namespace App\Http\Repositories\Contracts;

use Cassandra\Response\Event;
use Cassandra\Response\Response;

/**
 * Interface CassandraRepositoryContract
 *
 * @package App\Api\V1\Repositories\Contracts
 * @author Bolaji Ajani <fabulousbj@hotmail.com>
 */
interface CassandraRepositoryContract
{
    public function create(array $attributes): Response|Event;
}
