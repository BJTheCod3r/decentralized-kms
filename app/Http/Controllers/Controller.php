<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTraits;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests, ResponseTraits;

    const SOMETHING_WENT_WRONG = 'Something went wrong';
}
