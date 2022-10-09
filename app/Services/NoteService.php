<?php

namespace App\Services;

use App\Models\Note;

class NoteService
{
    public function store($data)
    {
        $note = new Note();
        $note = $note->fill($data);
        $note->user()->associate(auth()->user());
        $note->save();

        return $note;
    }

    public function update(Note $note, $data)
    {
        $note = $note->fill($data);
        $note->save();

        return $note;
    }

    public function destroy(Note $note)
    {
        $note->delete();
    }
}
