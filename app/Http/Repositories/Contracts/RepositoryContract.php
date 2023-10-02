<?php

declare(strict_types=1);

namespace App\Http\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\UuidInterface;

/**
 * Interface RepositoryContract
 *
 * @package App\Api\V1\Repositories\Contracts
 * @author Bolaji Ajani <fabulousbj@hotmail.com>
 */
interface RepositoryContract
{
    /**
     * Find By ID or UUID
     * @param $id
     * @return Model|null
     */
    public function find($id): ?Model;

    /**
     * Find One By
     * @param string $key
     * @param $value
     * @return Model|null
     */
    public function findOneBy(string $key, $value): ?Model;

    /**
     * Create record
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model;
}
