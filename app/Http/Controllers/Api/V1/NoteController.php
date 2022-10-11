<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Note\StoreNoteRequest;
use App\Http\Requests\Api\V1\Note\UpdateNoteRequest;
use App\Http\Resources\Api\V1\NoteResource;
use App\Models\Note;
use App\Services\NoteService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class NoteController extends Controller
{
    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return NoteResource::collection(auth()->user()->notes);
    }

    /**
     * @param StoreNoteRequest $request
     * @return Response
     * @throws \Throwable
     */
    public function store(StoreNoteRequest $request): Response
    {
        try {
            $data = $request->validated();
            (new NoteService())->store($data);

            return response()->noContent()->setStatusCode(201);
        } catch (\Throwable $exception) {
            throw $exception;
        }
    }

    /**
     * @param Note $note
     * @return NoteResource
     */
    public function show(Note $note): NoteResource
    {
        return NoteResource::make($note);
    }

    /**
     * @param UpdateNoteRequest $request
     * @param Note $note
     * @return Response
     * @throws \Throwable
     */
    public function update(UpdateNoteRequest $request, Note $note): Response
    {
        try {
            $data = $request->validated();
            (new NoteService())->update($note, $data);

            return response()->noContent();
        } catch (\Throwable $exception) {
            throw $exception;
        }
    }

    /**
     * @param Note $note
     * @return Response
     * @throws \Throwable
     */
    public function destroy(Note $note): Response
    {
        try {
            (new NoteService())->destroy($note);

            return response()->noContent();
        } catch (\Throwable $exception) {
            throw $exception;
        }
    }
}
