<?php

declare(strict_types=1);

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\LessonSession;
use App\Models\LearningMedia;
use App\Models\LearningMethod;
use App\Models\LearningObjective;
use App\Settings\GeneralSettings;
use Illuminate\View\View;

class LessonSessionPrintController extends Controller
{
    public function __invoke(LessonSession $lessonSession): View
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin', 'admin', 'teacher']), 403);

        $lessonSession->load([
            'schoolClass', 'subject', 'teacher', 'plan', 'planTopic',
            'materials', 'assignments', 'assessments', 'cases',
        ]);

        $objectives = ! empty($lessonSession->learning_objectives)
            ? LearningObjective::whereIn('id', $lessonSession->learning_objectives)->active()->ordered()->get()
            : collect();

        $methods = ! empty($lessonSession->methods)
            ? LearningMethod::whereIn('id', $lessonSession->methods)->active()->ordered()->get()
            : collect();

        $mediaItems = ! empty($lessonSession->media)
            ? LearningMedia::whereIn('id', array_filter($lessonSession->media, fn ($id) => $id !== 'lainnya'))
                ->active()->ordered()->get()
            : collect();

        // Get attendance summary for this session date
        $attendanceSummary = \App\Models\StudentAttendance::query()
            ->where('date', $lessonSession->session_date->toDateString())
            ->whereHas('student', fn ($q) => $q->where('school_class_id', $lessonSession->school_class_id))
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $settings   = app(GeneralSettings::class);
        $schoolName = $settings->school_name ?? config('app.name');
        $schoolLogo = $settings->logo ?? null;

        return view('reports.lesson-session-print', compact(
            'lessonSession',
            'objectives',
            'methods',
            'mediaItems',
            'attendanceSummary',
            'schoolName',
            'schoolLogo',
        ));
    }
}
