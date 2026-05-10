<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentAttendance;
use BackedEnum;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class AbsensiHariIni extends Page
{
    protected string $view = 'filament.pages.absensi-hari-ini';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-finger-print';

    protected static ?string $navigationLabel = 'Absensi Hari Ini';

    protected static ?string $title = 'Absensi Hari Ini';

    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    protected static ?int $navigationSort = 5;

    public ?int $school_class_id = null;

    public string $date;

    public string $tab = 'scan'; // scan | manual

    public ?string $lastScanMessage = null;
    public ?string $lastScanType = null; // success | warning | danger

    public function mount(): void
    {
        abort_unless(static::canAccess(), 403);

        $this->date = today()->toDateString();

        // default kelas: kalau guru, ambil kelas wali; selain itu kelas pertama
        $user = auth()->user();
        $staff = $user->staffMember;
        $homeroom = $staff
            ? SchoolClass::where('homeroom_teacher_id', $staff->id)->where('is_active', true)->orderByDesc('id')->first()
            : null;

        $this->school_class_id = $homeroom?->id ?? SchoolClass::where('is_active', true)->orderBy('grade')->orderBy('section')->value('id');
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'admin', 'teacher', 'piket']) ?? false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public function setTab(string $tab): void
    {
        $this->tab = in_array($tab, ['scan', 'manual'], true) ? $tab : 'scan';
    }

    public function setClass(?int $id): void
    {
        $this->school_class_id = $id;
    }

    public function setDate(string $date): void
    {
        if (! auth()->user()->hasAnyRole(['super_admin', 'admin'])) {
            return;
        }
        try {
            $this->date = Carbon::parse($date)->toDateString();
        } catch (\Throwable) {
            //
        }
    }

    public function getStudentsProperty()
    {
        if (! $this->school_class_id) return collect();
        return Student::active()
            ->where('school_class_id', $this->school_class_id)
            ->orderBy('name')
            ->get();
    }

    public function getAttendanceMapProperty()
    {
        if (! $this->school_class_id) return collect();
        return StudentAttendance::query()
            ->whereDate('date', $this->date)
            ->whereIn('student_id', $this->students->pluck('id'))
            ->get()
            ->keyBy('student_id');
    }

    public function getStatsProperty(): array
    {
        $map = $this->attendanceMap;
        $total = $this->students->count();
        $counts = [
            'hadir' => 0, 'terlambat' => 0, 'izin' => 0, 'sakit' => 0, 'alpa' => 0,
        ];
        foreach ($map as $rec) {
            if (isset($counts[$rec->status])) $counts[$rec->status]++;
        }
        $counts['belum'] = $total - array_sum($counts);
        $counts['total'] = $total;
        return $counts;
    }

    public function markByToken(string $token): void
    {
        $token = trim($token);
        if (strlen($token) < 4) {
            $this->flashScan('Token tidak valid.', 'danger');
            return;
        }

        $student = Student::findByQrToken($token);
        if (! $student) {
            $this->flashScan('Token tidak dikenal.', 'danger');
            return;
        }
        if (! $student->is_active) {
            $this->flashScan("Siswa {$student->name} non-aktif.", 'warning');
            return;
        }

        $existing = StudentAttendance::where('student_id', $student->id)
            ->whereDate('date', $this->date)->first();

        if ($existing) {
            $label = StudentAttendance::STATUSES[$existing->status] ?? $existing->status;
            $this->flashScan("{$student->name} sudah tercatat: {$label}.", 'warning');
            return;
        }

        StudentAttendance::create([
            'student_id' => $student->id,
            'staff_member_id' => auth()->user()->staffMember?->id,
            'date' => $this->date,
            'status' => 'hadir',
        ]);

        $klass = $student->schoolClass?->name ? " ({$student->schoolClass->name})" : '';
        $this->flashScan("HADIR — {$student->name}{$klass}", 'success');
    }

    public function setStatus(int $studentId, string $status, ?string $note = null): void
    {
        if (! in_array($status, array_keys(StudentAttendance::STATUSES), true)) {
            return;
        }

        $student = Student::find($studentId);
        if (! $student) return;

        // restrict: kelas terpilih harus = kelas siswa (bila tab manual)
        if ($this->school_class_id && $student->school_class_id !== $this->school_class_id) {
            return;
        }

        StudentAttendance::updateOrCreate(
            ['student_id' => $studentId, 'date' => $this->date],
            [
                'status' => $status,
                'staff_member_id' => auth()->user()->staffMember?->id,
                'note' => $note,
            ],
        );

        Notification::make()
            ->title("{$student->name} → " . (StudentAttendance::STATUSES[$status] ?? $status))
            ->success()
            ->send();
    }

    public function bulkMarkRemaining(string $status): void
    {
        if (! in_array($status, ['hadir', 'alpa'], true)) return;

        $missing = $this->students->reject(fn ($s) => $this->attendanceMap->has($s->id));
        $now = now();
        $staffId = auth()->user()->staffMember?->id;

        foreach ($missing as $s) {
            StudentAttendance::create([
                'student_id' => $s->id,
                'staff_member_id' => $staffId,
                'date' => $this->date,
                'status' => $status,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        Notification::make()
            ->title("{$missing->count()} siswa ditandai " . StudentAttendance::STATUSES[$status])
            ->success()
            ->send();
    }

    protected function flashScan(string $msg, string $type): void
    {
        $this->lastScanMessage = $msg;
        $this->lastScanType = $type;

        $notif = Notification::make()->title($msg);
        match ($type) {
            'success' => $notif->success(),
            'warning' => $notif->warning(),
            'danger' => $notif->danger(),
            default => $notif,
        };
        $notif->send();
    }
}
