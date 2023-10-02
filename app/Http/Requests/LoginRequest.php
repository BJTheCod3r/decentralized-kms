<?php

namespace App\Http\Requests;

/**
 * Class LoginRequest
 *
 * @author Bolaji Ajani <fabulousbj@hotmail.com>
 * @package \App\Http\Api\V1\Requests\Users
 */
class LoginRequest extends GuestRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|exists:users,email',
            'password' => 'required',
            'device_name' => "string|nullable",
        ];
    }
}
