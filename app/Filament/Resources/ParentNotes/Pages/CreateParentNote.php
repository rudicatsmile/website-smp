<?php

declare(strict_types=1);

namespace App\Filament\Resources\ParentNotes\Pages;

use App\Filament\Resources\ParentNotes\ParentNoteResource;
use App\Models\ParentNote;
use App\Models\ParentNoteMessage;
use App\Models\Student;
use App\Services\Notifications\ParentNoteNotifier;
use Filament\Resources\Pages\CreateRecord;

class CreateParentNote extends CreateRecord
{
    protected static string $resource = ParentNoteResource::class;

    public ?string $initialBody = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->initialBody = $data['initial_body'] ?? null;
        unset($data['initial_body']);

        $student = Student::with('schoolClass')->find($data['student_id'] ?? null);
        if ($student) {
            $data['school_class_id'] = $data['school_class_id'] ?? $student->school_class_id;
            $data['homeroom_teacher_id'] = $data['homeroom_teacher_id'] ?? $student->schoolClass?->homeroom_teacher_id;
        }

        $data['initiator_user_id'] = auth()->id();
        $data['initiator_type'] = 'teacher';
        $data['last_activity_at'] = now();

        return $data;
    }

    protected function afterCreate(): void
    {
        /** @var ParentNote $note */
        $note = $this->record;
        if (! $this->initialBody) {
            return;
        }

        $message = ParentNoteMessage::create([
            'parent_note_id' => $note->id,
            'sender_type' => 'teacher',
            'user_id' => auth()->id(),
            'staff_member_id' => auth()->user()?->staffMember?->id,
            'body' => $this->initialBody,
        ]);

        $note->update(['last_activity_at' => now()]);

        app(ParentNoteNotifier::class)->dispatchForMessage($message);
    }
}
