<?php

namespace App\Http\Services;


use App\Http\Repositories\DocumentRepository;
use App\Http\Repositories\KeywordRepository;
use App\Http\Repositories\TagRepository;
use App\Http\Resources\DocumentResource;
use App\Http\Services\Contracts\DocumentServiceContract;
use App\Http\Traits\FileUploadTraits;
use App\Jobs\AddDocument;
use App\Models\Document;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use TextAnalysis\Analysis\Keywords\Rake;
use TextAnalysis\Documents\TokensDocument;
use TextAnalysis\Filters\CharFilter;
use TextAnalysis\Filters\LowerCaseFilter;
use TextAnalysis\Filters\PunctuationFilter;
use TextAnalysis\Filters\SpacePunctuationFilter;
use TextAnalysis\Filters\StopWordsFilter;
use TextAnalysis\Tokenizers\GeneralTokenizer;
use Smalot\PdfParser\Parser;

/**
 * Class DocumentService
 *
 * This class does a lot of heavy lifting as such most of its work should be
 * deferred to a job
 */
class DocumentService implements DocumentServiceContract
{
    use FileUploadTraits;

    public function __construct(protected DocumentRepository $documentRepository, protected KeywordRepository $keywordRepository,
                                protected TagRepository      $tagRepository)
    {

    }

    /**
     * Add document to the KMS
     *
     * @param int $user_id
     * @param array $data
     * @return string
     * @throws Exception
     */
    public function addDocument(int $user_id, array $data): string
    {
        //we don't want to waste time here so let's defer to a job
        AddDocument::dispatch($this->getData($data, $user_id));
        return Document::DOCUMENT_ADDITION_IN_PROGRESS;
    }

    /**
     * @param array $data
     * @param int $user_id
     * @return array
     * @throws Exception
     */
    public function getData(array $data, int $user_id): array
    {
        $result = $data;
        $result['user_id'] = $user_id;

        if (empty($data['file'])) {
            $result['content'] = $data['content'];
            $text = $data['content'];
        } else {
            $text = $this->getText($data['file'], $data['type']);
            $result['content'] = $this->getSnippet($text);
            $directory = "kms/" . date('d');
            $result['url'] = $this->uploadFile($data['file'], $directory);
        }
        $result['category'] = $this->getPossibleCategory($text);
        $result['keywords'] = $this->pickKeywords($this->getKeywords($text), 5);
        return $this->excludeData($result, ['file']);
    }

    /**
     * @param UploadedFile $file
     * @param string $type
     * @return string
     * @throws Exception
     */
    public function getText(UploadedFile $file, string $type): string
    {
        return match ($type) {
            Document::TYPE_PDF => $this->pdfToText($file),
            Document::TYPE_DOC => $this->docToText($file),
            default => file_get_contents($file) ?? "" //assumption is default is a text file
        };
    }

    /**
     * @param UploadedFile $file
     * @return string
     * @throws Exception
     */
    public function pdfToText(UploadedFile $file): string
    {
        $parser = new Parser();

        try {
            $pdf = $parser->parseFile($file);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

        return $pdf->getText();
    }

    /**
     * Get text from docx file or related file
     *
     * @param UploadedFile $file
     * @return string
     */
    public function docToText(UploadedFile $file): string
    {
        //TODO: Implement doc to text
        return "";
    }

    /**
     * Get snippet from file this is important, so we can get some snippet
     * to help with search
     *
     * @param string $text
     * @return string
     */
    public function getSnippet(string $text): string
    {
        $textLength = strlen($text);
        $cutLength = min(config('nlp.content_length'), $textLength);

        return substr($text, 0, $cutLength);
    }

    /**
     * Get possible category
     *
     * @param string $text
     * @return ?string
     */
    public function getPossibleCategory(string $text): ?string
    {
        $nb = naive_bayes();
        $categories = config('nlp.categories');
        foreach ($categories as $category => $value) {
            $nb->train($category, tokenize($value));
        }
        return $this->getHighestPossibleCategory($nb->predict(tokenize($text)));
    }

    /**
     * Get the highest possible category
     *
     * @param array $data
     * @return string|null
     */
    private function getHighestPossibleCategory(array $data): ?string
    {
        $maxValue = null;
        $maxKey = null;

        foreach ($data as $key => $value) {
            if ($maxValue === null || $value > $maxValue) {
                $maxValue = $value;
                $maxKey = $key;
            }
        }

        if ($maxValue == 0) {
            return null; // All values are zero
        }

        return $maxKey;
    }

    /**
     * @param array $keywords
     * @param int $length
     * @return int[]|string[]
     */
    public function pickKeywords(array $keywords, int $length): array
    {
        $filteredKeywords = array_filter(array_keys($keywords), function ($key) {
            return !preg_match('/\d/', $key);
        });

        return array_slice($filteredKeywords, 0, $length);
    }

    /**
     * @param string $text
     * @return array
     */
    public function getKeywords(string $text): array
    {
        $stopWords = config('nlp.stop_words');

        $testData = (new SpacePunctuationFilter([':', '\/']))->transform($text);

        $tokens = (new GeneralTokenizer(" \n\t\r"))->tokenize($testData);
        $tokenDoc = new TokensDocument($tokens);
        $tokenDoc->applyTransformation(new LowerCaseFilter())
            ->applyTransformation(new StopWordsFilter($stopWords), true)
            ->applyTransformation(new PunctuationFilter(['@', ':', '\/']), true)
            ->applyTransformation(new CharFilter(), true);

        $rake = new Rake($tokenDoc, 3);
        return $rake->getKeywordScores();
    }

    /**
     * @param array $data
     * @param array $excludedKeys
     * @return array
     */
    private function excludeData(array $data, array $excludedKeys): array
    {
        foreach ($excludedKeys as $key) {
            if (array_key_exists($key, $data)) {
                unset($data[$key]);
            }
        }

        return $data;
    }

    /**
     * Store the document to Database
     *
     * @param array $data
     * @return Model
     */
    public function createDocument(array $data): Model
    {
        return $this->documentRepository->create($data);
    }

    /**
     * @param array $keywords
     * @return array
     */
    public function createKeywords(array $keywords): array
    {
        $data = [];
        foreach ($keywords as $keyword) {
            $createdKeyword = $this->keywordRepository->firstOrCreate([
                'word' => $keyword
            ], []);
            $data[] = $createdKeyword->id;
        }
        return $data;
    }

    /**
     * @param string $tag
     * @return array
     */
    public function createTag(string $tag): array
    {
        $tag = $this->tagRepository->firstOrCreate(['tag' => $tag], []);

        return [$tag->id];
    }

    /**
     * List document or search through document
     *
     * @param string|null $q
     * @param int $per_page
     * @return ResourceCollection
     */
    public function listDocuments(int $per_page, string $q = null): ResourceCollection
    {
        $collection = is_null($q) ? $this->documentRepository->latest()->paginate($per_page)
            : $this->documentRepository->search($q)->paginate($per_page);

        return DocumentResource::collection($collection);
    }

    /**
     * @param int $id
     * @return Model
     */
    public function fetchDocument(int $id): Model
    {
        return $this->documentRepository->findOrFail($id);
    }
}
