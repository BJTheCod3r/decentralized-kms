<?php

declare(strict_types=1);

namespace App\Models\Cassandra;

/**
 * Class Document
 *
 * @package App\Models\Cassandra
 */
class Document extends CassandraModel
{
    /**
     * We should define a method later in the parent class
     * that can infer table name from class names but can
     * then be override by child class if the table name
     * changes from the norms.
     *
     * @var string
     */
    protected string $table = 'documents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected array $fillable = [
        'content_text',
        'content_type',
        'url'
    ];
}
