<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Document;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\RequiredIf;

class AddDocumentRequest extends GuestRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "type" => ['required', 'string', Rule::in(array_keys(Document::TYPES))],
            "content" => ['string', Rule::RequiredIf($this->contentIsRequired())],
            "file" => ['file', 'mimetypes:'. implode(',', array_values(Document::TYPES)),
                Rule::RequiredIf($this->type !== Document::TYPE_TEXT)]
        ];
    }

    /**
     * Check if content is required
     *
     * @return bool
     */
    private function contentIsRequired(): bool
    {
        return $this->type === Document::TYPE_TEXT && empty($this->file);
    }
}
