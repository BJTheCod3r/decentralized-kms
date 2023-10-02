<?php

declare(strict_types=1);

namespace App\Http\Repositories;

use App\Http\Repositories\Contracts\RepositoryContract;
use Illuminate\Database\Eloquent\Model;

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
        return $this->model::create($attributes);
    }

    /**
     * Find one
     * @param $id
     * @return Model
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

}
