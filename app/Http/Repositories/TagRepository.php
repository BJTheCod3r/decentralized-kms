<?php

declare(strict_types=1);

namespace App\Http\Repositories;

use App\Models\Tag;

/**
 * Class TagRepository
 *
 * @package \App\Http\Api\V1\Repositories
 * @author Bolaji Ajani <Bolaji Ajani>
 */
class TagRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(Tag::class);
    }
}
