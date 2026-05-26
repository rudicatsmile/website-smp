<?php

declare(strict_types=1);

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\TahfidzParticipant;
use App\Settings\GeneralSettings;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TahfidzReportController extends Controller
{
    public function __invoke(Request $request, TahfidzParticipant $participant): View
    {
        abort_unless(auth()->check(), 403);

        $participant->load(['student.schoolClass', 'tahfidzClass', 'grades.teacher']);

        $grades = $participant->grades()->with('teacher')->orderBy('created_at')->get();

        $settings   = app(GeneralSettings::class);
        $schoolName = $settings->school_name ?? config('app.name');
        $schoolLogo = $settings->logo ?? null;

        return view('reports.tahfidz-report', compact(
            'participant',
            'grades',
            'schoolName',
            'schoolLogo',
        ));
    }
}
