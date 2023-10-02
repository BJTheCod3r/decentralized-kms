<?php

declare(strict_types=1);

namespace App\Http\Requests;

class ValidateDataRequest extends GuestRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "type" => ['required', 'string', 'in:email,phone'],
            "value" => ['required', 'string', 'min:3', 'max:50']
        ];
    }
}
