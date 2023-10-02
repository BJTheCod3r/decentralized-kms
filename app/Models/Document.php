<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Scout\Searchable;

class Document extends Model
{
    use HasFactory;
    use Searchable;

    const TYPE_TEXT = 'text';
    const TYPE_PDF = 'pdf';

    const TYPE_DOC = 'doc';

    const DOCUMENT_ADDITION_IN_PROGRESS = 'Document addition in progress';

    /**
     * Content types that are allowed to be added into the system
     */
    const TYPES = [
        self::TYPE_TEXT => 'text/plain',
        self::TYPE_PDF => 'application/pdf',
        self::TYPE_DOC => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];

    protected $fillable = [
        'type',
        'content',
        'url',
        'description',
        'url',
        'user_id'
    ];

    /**
     * Get keywords that the document Belongs to
     *
     * @return BelongsToMany
     */
    public function keywords(): BelongsToMany
    {
        return $this->belongsToMany(Keyword::class);
    }

    /**
     * Get tags that the document Belongs to
     *
     * @return BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
}
