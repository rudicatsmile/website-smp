<?php

declare(strict_types=1);

namespace App\Filament\Tahfidz\Resources\RekapNilaiResource\Pages;

use App\Filament\Tahfidz\Resources\RekapNilaiResource;
use App\Models\TahfidzParticipant;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;

class ViewRekapNilai extends ViewRecord
{
    protected static string $resource = RekapNilaiResource::class;

    protected string $view = 'filament.tahfidz.pages.view-rekap-nilai';

    protected static ?string $title = 'Detail Nilai Tahfidz';

    public function resolveRecord(int|string $key): Model
    {
        return TahfidzParticipant::with(['student.schoolClass', 'tahfidzClass', 'grades.teacher'])->findOrFail($key);
    }

    public function getGrades()
    {
        return $this->record->grades()->with('teacher')->orderBy('created_at', 'desc')->get();
    }
}
