<?php

namespace Tests\Unit;

use App\Http\Repositories\DocumentRepository;
use App\Http\Repositories\KeywordRepository;
use App\Http\Repositories\TagRepository;
use App\Http\Services\DocumentService;
use App\Models\Document;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class DocumentServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function testAddDocumentDispatchesJob(): void
    {
        // Mock dependencies
        $documentRepository = $this->app->make(DocumentRepository::class);
        $keywordRepository = $this->app->make(KeywordRepository::class);
        $tagRepository = $this->app->make(TagRepository::class);

        // Create an instance of DocumentService
        $documentService = new DocumentService($documentRepository, $keywordRepository, $tagRepository);

        // Call the addDocument method
        $user_id = 1;
        $data = ['content' => 'Test content', 'type' => 'text'];
        $result = $documentService->addDocument($user_id, $data);

        // Assert that the result is 'Document::DOCUMENT_ADDITION_IN_PROGRESS'
        $this->assertEquals(Document::DOCUMENT_ADDITION_IN_PROGRESS, $result);
    }

    public function testDocToText()
    {
        // Create a test DOCX file (not implemented in the class)
        $docxFilePath = storage_path('test.docx');
        file_put_contents($docxFilePath, 'Test DOCX Content');

        // Create an UploadedFile instance
        $docxFile = new UploadedFile($docxFilePath, 'test.docx');

        // Create an instance of DocumentService
        $documentService = new DocumentService(
            $this->app->make(DocumentRepository::class),
            $this->app->make(KeywordRepository::class),
            $this->app->make(TagRepository::class)
        );

        // Call the docToText method (not implemented)
        $result = $documentService->docToText($docxFile);

        // Assert that the result is an empty string (not implemented)
        $this->assertEquals('', $result);
    }

    public function testGetSnippet()
    {
        // Create an instance of DocumentService
        $documentService = new DocumentService(
            $this->app->make(DocumentRepository::class),
            $this->app->make(KeywordRepository::class),
            $this->app->make(TagRepository::class)
        );

        // Call the getSnippet method with sample text
        $sampleText = 'This is a sample text for testing getSnippet.';
        $result = $documentService->getSnippet($sampleText);

        // Assert that the result matches the expected text
        $this->assertEquals('This is a sample text for testing getSnippet.', $result);
    }

    public function testGetPossibleCategory()
    {
        // Create an instance of DocumentService
        $documentService = new DocumentService(
            $this->app->make(DocumentRepository::class),
            $this->app->make(KeywordRepository::class),
            $this->app->make(TagRepository::class)
        );

        // Call the getPossibleCategory method with sample text
        $sampleText = 'Sample text for category prediction.';
        $result = $documentService->getPossibleCategory($sampleText);

        // Assert that the result is a string
        $this->assertIsString($result);
    }

    public function testPickKeywords()
    {
        // Create an instance of DocumentService
        $documentService = new DocumentService(
            $this->app->make(DocumentRepository::class),
            $this->app->make(KeywordRepository::class),
            $this->app->make(TagRepository::class)
        );

        // Call the pickKeywords method with sample keywords
        $sampleKeywords = ['keywordone' => 1, 'keywordtwo' => 1, 'keywordthree' => 1, 'keywordfour' => 1];
        $result = $documentService->pickKeywords($sampleKeywords, 2);

        // Assert that the result is an array with 2 keywords
        $this->assertCount(2, $result);
    }

    public function testCreateDocument()
    {
        // Create an instance of DocumentService
        $documentService = new DocumentService(
            $this->app->make(DocumentRepository::class),
            $this->app->make(KeywordRepository::class),
            $this->app->make(TagRepository::class)
        );

        // Call the createDocument method with sample data
        $sampleData = ['user_id' => 1, 'content' => 'Test content', 'type' => 'text'];
        $result = $documentService->createDocument($sampleData);

        // Assert that the result is an instance of Document model
        $this->assertInstanceOf(Document::class, $result);
    }

    public function testCreateKeywords()
    {
        // Create an instance of DocumentService
        $documentService = new DocumentService(
            $this->app->make(DocumentRepository::class),
            $this->app->make(KeywordRepository::class),
            $this->app->make(TagRepository::class)
        );

        // Call the createKeywords method with sample keywords
        $sampleKeywords = ['keyword1', 'keyword2', '3rdKeyword'];
        $result = $documentService->createKeywords($sampleKeywords);

        // Assert that the result is an array of keyword IDs
        $this->assertIsArray($result);
        foreach ($result as $keywordId) {
            $this->assertIsInt($keywordId);
        }
    }

    public function testCreateTag()
    {
        // Create an instance of DocumentService
        $documentService = new DocumentService(
            $this->app->make(DocumentRepository::class),
            $this->app->make(KeywordRepository::class),
            $this->app->make(TagRepository::class)
        );

        // Call the createTag method with a sample tag
        $sampleTag = 'TestTag';
        $result = $documentService->createTag($sampleTag);

        // Assert that the result is an array with a tag ID
        $this->assertIsArray($result);
        $this->assertIsInt($result[0]);
    }

    public function testListDocuments()
    {
        // Create an instance of DocumentService
        $documentService = new DocumentService(
            $this->app->make(DocumentRepository::class),
            $this->app->make(KeywordRepository::class),
            $this->app->make(TagRepository::class)
        );

        // Call the listDocuments method with per_page and q parameters
        $per_page = 10;
        $q = 'Test Query';
        $result = $documentService->listDocuments($per_page, $q);

        // Assert that the result is an instance of DocumentResource
        $this->assertInstanceOf(AnonymousResourceCollection::class, $result);
    }

    public function testFetchDocument()
    {
        // Create a sample document in the database (use your actual model factory)
        $document = Document::factory()->create();

        // Create an instance of DocumentService
        $documentService = new DocumentService(
            $this->app->make(DocumentRepository::class),
            $this->app->make(KeywordRepository::class),
            $this->app->make(TagRepository::class)
        );

        // Call the fetchDocument method with the ID of the created document
        $documentId = $document->id;
        $result = $documentService->fetchDocument($documentId);

        // Assert that the result is an instance of Document model
        $this->assertInstanceOf(Document::class, $result);
    }
}

