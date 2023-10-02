<?php

namespace App\Http\Services\Contracts;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\UploadedFile;

/**
 * @author Bolaji Ajani <fabulousbj@hotmail.com>
 * All methods any DocumentService we decide to switch to
 * must implement
 */
interface DocumentServiceContract
{
    /**
     * Add document to the KMS
     *
     * @param int $user_id
     * @param array $data
     * @return string
     * @throws Exception
     */
    public function addDocument(int $user_id, array $data): string;

    /**
     * @param array $data
     * @param int $user_id
     * @return array
     * @throws Exception
     */
    public function getData(array $data, int $user_id): array;

    /**
     * @param UploadedFile $file
     * @param string $type
     * @return string
     * @throws Exception
     */
    public function getText(UploadedFile $file, string $type): string;

    /**
     * @param UploadedFile $file
     * @return string
     * @throws Exception
     */
    public function pdfToText(UploadedFile $file): string;

    /**
     * Get text from docx file or related file
     *
     * @param UploadedFile $file
     * @return string
     */
    public function docToText(UploadedFile $file): string;

    /**
     * Get snippet from file this is important, so we can get some snippet
     * to help with search
     *
     * @param string $text
     * @return string
     */
    public function getSnippet(string $text): string;

    /**
     * Get possible category
     *
     * @param string $text
     * @return ?string
     */
    public function getPossibleCategory(string $text): ?string;

    /**
     * @param array $keywords
     * @param int $length
     * @return int[]|string[]
     */
    public function pickKeywords(array $keywords, int $length): array;

    /**
     * @param string $text
     * @return array
     */
    public function getKeywords(string $text): array;

    /**
     * Store the document to Database
     *
     * @param array $data
     * @return Model
     */
    public function createDocument(array $data): Model;

    /**
     * @param array $keywords
     * @return array
     */
    public function createKeywords(array $keywords): array;

    /**
     * @param string $tag
     * @return array
     */
    public function createTag(string $tag): array;

    /**
     * List document or search through document
     *
     * @param string|null $q
     * @param int $per_page
     * @return ResourceCollection
     */
    public function listDocuments(int $per_page, string $q = null): ResourceCollection;

    /**
     * @param int $id
     * @return Model
     */
    public function fetchDocument(int $id): Model;
}
