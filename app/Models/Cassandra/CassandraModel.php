<?php

declare(strict_types=1);

namespace App\Models\Cassandra;

/**
 * Abstract model for all model that will make use of Cassandra as their
 * data storage. Not as capable as Eloquent but can get the work done for
 * now. In the future, this would be done in a way that is as capable as eloquent
 * or a proper driver will be setup with the same interface as the other drivers
 * eloquent supports.
 */
abstract class CassandraModel
{
    /**
     * @var string
     */
    protected string $table = '';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected array $fillable = [
    ];

    protected array $defaultFillable = [
        'created_at',
        'updated_at'
    ];

    /**
     * @var string
     */
    protected string $primaryKey = 'id';

    /**
     * @var string
     */
    protected string $primaryKeyType = 'uuid';


    /**
     * CassandraModel Constructor
     */
    public function __construct()
    {
         $this->mergeFillable();
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * @return string
     */
    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }

    /**
     * @return string
     */
    public function getPrimaryKeyType(): string
    {
        return $this->primaryKeyType;
    }

    /**
     * Get the attributes that are fillable
     *
     * @param array $attributes
     * @return string[]
     */
    public function getFillableAttributes(array $attributes): array
    {
        return array_intersect_key($attributes, $this->fillable);
    }

    /**
     * Merge fillable with default fillable
     */
    public function mergeFillable(): void
    {
        $this->fillable = array_merge($this->defaultFillable, $this->fillable);
    }
}
