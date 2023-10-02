<?php

namespace App\Console\Commands;

use App\Http\Repositories\DocumentRepository;
use Illuminate\Console\Command;

class GenerateDataSet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-data-set';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(DocumentRepository $documentRepository)
    {
        $documents = $documentRepository->all();
        foreach (range(1, 5000) as $number) {
            foreach ($documents as $document) {
                $newDocument = $documentRepository->create($document->toArray());
                $newDocument->keywords()->sync($document->keywords()->pluck('keywords.id')->toArray());
                $newDocument->tags()->sync($document->tags()->pluck('tags.id')->toArray());

            }
        }
    }
}
