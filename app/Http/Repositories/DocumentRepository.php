<?php

declare(strict_types=1);

namespace App\Http\Repositories;

use App\Models\Document;

/**
 * Class DocumentRepository
 *
 * @package \App\Http\Api\V1\Repositories
 * @author Bolaji Ajani <Bolaji Ajani>
 */
class DocumentRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(Document::class);
    }
}
