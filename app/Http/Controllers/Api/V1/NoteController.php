<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Note\StoreNoteRequest;
use App\Http\Requests\Api\V1\Note\UpdateNoteRequest;
use App\Http\Resources\Api\V1\NoteResource;
use App\Models\Note;
use App\Services\NoteService;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index()
    {
        return NoteResource::collection(auth()->user()->notes);
    }

    public function store(StoreNoteRequest $request)
    {
        try {
            $data = $request->validated();
            (new NoteService())->store($data);

            return response()->noContent()->setStatusCode(201);
        } catch (\Throwable $exception) {
            throw $exception;
        }
    }

    public function show(Note $note)
    {
        return NoteResource::make($note);
    }

    public function update(UpdateNoteRequest $request, Note $note)
    {
        try {
            $data = $request->validated();
            (new NoteService())->update($note, $data);

            return response()->noContent();
        } catch (\Throwable $exception) {
            throw $exception;
        }
    }

    public function destroy(Note $note)
    {
        try {
            (new NoteService())->destroy($note);

            return response()->noContent();
        } catch (\Throwable $exception) {
            throw $exception;
        }
    }
}
