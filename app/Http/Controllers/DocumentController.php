<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddDocumentRequest;
use App\Http\Requests\ListDocumentRequest;
use App\Http\Resources\DocumentResource;
use App\Http\Services\Contracts\DocumentServiceContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DocumentController extends Controller
{
    public function __construct(protected DocumentServiceContract $documentService)
    {

    }

    /**
     * @param AddDocumentRequest $request
     * @return JsonResponse
     */
    public function addDocument(AddDocumentRequest $request): JsonResponse
    {
        return $this->successResponse([], $this->documentService->addDocument($request->user()->id, $request->validated()));
    }

    /**
     * @param ListDocumentRequest $request
     * @return ResourceCollection
     */
    public function listDocuments(ListDocumentRequest $request): ResourceCollection
    {
        return $this->collectionResponse($this->documentService->listDocuments($request->get('per_page', 10), $request->get('q')));
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function fetchDocument(int $id): JsonResponse
    {
         return $this->successResponse(new DocumentResource($this->documentService->fetchDocument($id)));
    }
}
