<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


/**
 * Class GuestRequest
 *
 * @author Bolaji Ajani <fabulousbj@hotmail.com>
 * @package \App\Http\Requests
 */
class GuestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * In the future we should do one or two thing here.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

}
