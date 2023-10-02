<?php

declare(strict_types=1);

namespace App\Http\Repositories\Cassandra;

use App\Http\Repositories\Contracts\RepositoryContract;
use Ramsey\Uuid\UuidInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class BaseRepository
 *
 * @package App\Api\V1\Repositories
 * @author  Bolaji Ajani <fabulousbj@hotmail.com>
 */
abstract class BaseRepository implements RepositoryContract
{

    /**
     * BaseRepository constructor.
     *
     * For now, we can still work with the concrete implementation of ID,
     * but it's important we use the underlying model to get the primary Key
     * and then pass that instead of assuming the Primary key is "id"
     *
     * @param string $model
     */
    public function __construct(protected string $model)
    {
    }

    /**
     * Give us access to the model properties and methods
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return $this->model::$name(...$arguments);
    }

    /**
     * Create a record
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model
    {
        return $this->model::create(array_merge(['id' => $this->newId()], $attributes));
    }

    /**
     * Return first or newly created record
     *
     * @param array $matchAttributes
     * @param array $mergeAttributes
     * @return Model
     */
    public function firstOrCreate(array $matchAttributes, array $mergeAttributes): Model
    {
        return $this->model::firstOrCreate($matchAttributes, array_merge(['id' => $this->newId()], $mergeAttributes));
    }

    /**
     * Find one
     * @param $id
     * @return ?Model
     */
    public function find($id): ?Model
    {
        return (is_numeric($id)) ? $this->model::find((int)$id) : $this->findOneBy('uuid', $id);
    }

    /**
     * Find one by
     * @param string $key
     * @param string|int $value
     * @return Model|null
     */
    public function findOneBy(string $key, $value): ?Model
    {
        return $this->model::where($key, $value)->first();
    }


    /**
     * List all data
     * @param int $perPage
     * @return mixed
     */
    public function listAll(int $perPage): mixed
    {
        return $this->model::latest()->paginate($perPage);
    }

    /**
     * Cassandra doesn't auto increment, so we have to manage that part,
     * we could probably do with getting the last ID and then incrementing it
     * but seems prone to problem, and we don't even need the unnecessary DB
     * hit so let's get random that we're sure will be unique
     *
     * @return UuidInterface
     */
    public function newId(): UuidInterface
    {
        //return time() + mt_rand(0, 1230);
        return Str::uuid();
    }

}
