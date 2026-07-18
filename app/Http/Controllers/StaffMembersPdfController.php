<?php

namespace App\Http\Controllers;

use App\Models\StaffMember;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class StaffMembersPdfController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, \App\Settings\GeneralSettings $settings)
    {
        $staffMembers = StaffMember::with('teachingSubjects')
            ->whereHas('category', function ($query) {
                $query->where('name', 'Guru Pelajaran');
            })
            ->where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();

        $pdf = Pdf::loadView('reports.staff-members-pdf', [
            'staffMembers' => $staffMembers,
            'settings' => $settings,
        ]);

        return $pdf->stream('Laporan-Daftar-Guru-Mata-Pelajaran.pdf');
    }
}
