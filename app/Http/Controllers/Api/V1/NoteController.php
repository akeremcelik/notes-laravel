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
use Illuminate\Support\Facades\DB;

class NoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.note.belongsTo.user')->only('show', 'update', 'destroy');
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return NoteResource::collection(auth()->user()->notes);
    }

    /**
     * @param StoreNoteRequest $request
     * @return NoteResource
     * @throws \Throwable
     */
    public function store(StoreNoteRequest $request): NoteResource
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $note = (new NoteService())->store($data);
            DB::commit();

            return NoteResource::make($note);
        } catch (\Throwable $exception) {
            DB::rollBack();
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
            DB::beginTransaction();
            $data = $request->validated();
            (new NoteService())->update($note, $data);
            DB::commit();

            return response()->noContent();
        } catch (\Throwable $exception) {
            DB::rollBack();
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
            DB::beginTransaction();
            (new NoteService())->destroy($note);
            DB::commit();

            return response()->noContent();
        } catch (\Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
