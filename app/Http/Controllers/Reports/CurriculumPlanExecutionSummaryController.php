<?php

declare(strict_types=1);

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\CurriculumPlan;
use App\Settings\GeneralSettings;
use Illuminate\View\View;

class CurriculumPlanExecutionSummaryController extends Controller
{
    public function __invoke(CurriculumPlan $plan): View
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin', 'admin', 'teacher']), 403);

        $plan->load(['schoolClass', 'subject', 'teacher', 'sessions']);

        $sessions = $plan->sessions()
            ->with(['materials', 'assignments', 'assessments', 'cases'])
            ->orderBy('session_date')
            ->get();

        $completedSessions = $sessions->where('status', 'completed');
        $totalSessions     = $sessions->count();
        $completedCount    = $completedSessions->count();

        $avgAchievement = $completedSessions->isNotEmpty()
            ? round($completedSessions->avg('achievement_percent'), 1)
            : 0;

        $sessionsData = $sessions->map(function ($session) {
            // Get attendance summary for this session
            $attendanceSummary = \App\Models\StudentAttendance::query()
                ->where('date', $session->session_date->toDateString())
                ->whereHas('student', fn ($q) => $q->where('school_class_id', $session->school_class_id))
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();

            return [
                'session'          => $session,
                'date_label'       => $session->session_date->isoFormat('dddd, D MMMM Y'),
                'time_range'       => $session->time_range,
                'topic'            => $session->topic,
                'achievement'      => $session->achievement_percent,
                'status'           => $session->status,
                'status_label'     => $session->status_label,
                'execution_notes'  => $session->execution_notes,
                'attendance'       => $attendanceSummary,
            ];
        });

        // Aggregate all materials, assignments, assessments, cases
        $allMaterials   = $sessions->flatMap->materials->unique('id');
        $allAssignments = $sessions->flatMap->assignments->unique('id');
        $allAssessments = $sessions->flatMap->assessments->unique('id');
        $allCases       = $sessions->flatMap->cases->unique('id');

        $settings   = app(GeneralSettings::class);
        $schoolName = $settings->school_name ?? config('app.name');
        $schoolLogo = $settings->logo ?? null;

        return view('reports.curriculum-plan-execution-summary', compact(
            'plan',
            'sessionsData',
            'totalSessions',
            'completedCount',
            'avgAchievement',
            'allMaterials',
            'allAssignments',
            'allAssessments',
            'allCases',
            'schoolName',
            'schoolLogo',
        ));
    }
}
