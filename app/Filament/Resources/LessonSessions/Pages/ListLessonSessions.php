<?php

declare(strict_types=1);

namespace App\Filament\Resources\LessonSessions\Pages;

use App\Filament\Resources\LessonSessions\LessonSessionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLessonSessions extends ListRecords
{
    protected static string $resource = LessonSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('printJournal')
                ->label('Cetak Jurnal Bulanan')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->form([
                    \Filament\Forms\Components\Select::make('month')->label('Bulan')
                        ->options([
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                        ])->required()->default(now()->month),
                    \Filament\Forms\Components\TextInput::make('year')->label('Tahun')
                        ->numeric()->required()->default(now()->year),
                ])
                ->action(function (array $data) {
                    $month = $data['month'];
                    $year = $data['year'];
                    $staffId = auth()->user()?->staffMember?->id;
                    
                    if (!$staffId) {
                        \Filament\Notifications\Notification::make()->title('Akun Anda belum ditautkan dengan data Guru.')->danger()->send();
                        return;
                    }
                    
                    $sessions = \App\Models\LessonSession::with(['schoolClass', 'subject'])
                        ->where('staff_member_id', $staffId)
                        ->whereMonth('session_date', $month)
                        ->whereYear('session_date', $year)
                        ->where('status', 'completed')
                        ->orderBy('session_date')
                        ->get();
                        
                    if($sessions->isEmpty()) {
                        \Filament\Notifications\Notification::make()->title('Tidak ada data jurnal yang sudah selesai di bulan ini.')->warning()->send();
                        return;
                    }

                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.teaching-journal', [
                        'sessions' => $sessions,
                        'month' => $month,
                        'year' => $year,
                        'teacher' => auth()->user()->staffMember,
                    ])->setPaper('a4', 'landscape');

                    return response()->streamDownload(fn () => print($pdf->output()), "Jurnal_Mengajar_{$year}_{$month}.pdf");
                }),
            CreateAction::make(),
        ];
    }
}
