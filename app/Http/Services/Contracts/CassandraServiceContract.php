<?php

namespace App\Http\Services\Contracts;

use SplFixedArray;

/**
 * Interface CassandraServiceContract
 *
 * Any CassandraService we create must implement this interface for
 * consistency and compatibility sake.
 */
interface CassandraServiceContract
{
    /**
     * @param string $query
     * @return $this
     */
    public function selectRaw(string $query): self;

    /**
     * @return SplFixedArray
     */
    public function get(): SplFixedArray;

}
