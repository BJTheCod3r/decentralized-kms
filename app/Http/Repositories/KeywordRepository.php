<?php

declare(strict_types=1);

namespace App\Http\Repositories;

use App\Models\Keyword;

/**
 * Class KeywordRepository
 *
 * @package \App\Http\Api\V1\Repositories
 * @author Bolaji Ajani <Bolaji Ajani>
 */
class KeywordRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(Keyword::class);
    }
}
