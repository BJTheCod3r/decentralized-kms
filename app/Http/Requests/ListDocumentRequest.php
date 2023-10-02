<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Document;

class ListDocumentRequest extends GuestRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'q' => 'nullable|string',
            'per_page' => 'nullable|int'
        ];
    }
}
