<?php

namespace App\Jobs;

use App\Http\Services\Contracts\DocumentServiceContract;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddDocument implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected array $data)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(DocumentServiceContract $documentService): void
    {
        $document = $documentService->createDocument($this->data);

        //generate keywords
        $keywords = $documentService->createKeywords($this->data['keywords']);
        $document->keywords()->sync($keywords);

        //generate tags
        if (!is_null($this->data['category'])) {
            $tags = $documentService->createTag($this->data['category']);
            $document->tags()->sync($tags);
        }

    }
}
