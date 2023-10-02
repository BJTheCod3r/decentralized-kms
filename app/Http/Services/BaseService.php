<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Http\Traits\ResponseTraits;
/**
 * Class BaseService
 *
 * @package \App\Http\Api\V1\Services
 * @author Bolaji Ajani <Bolaji Ajani>
 */
abstract class BaseService
{
    use ResponseTraits;
}
