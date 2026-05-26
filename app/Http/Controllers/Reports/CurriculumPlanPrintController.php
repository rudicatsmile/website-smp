<?php

declare(strict_types=1);

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\CurriculumPlan;
use App\Models\KkoLevel;
use App\Models\LearningMedia;
use App\Models\LearningMethod;
use App\Models\LearningModel;
use App\Models\LearningObjective;
use App\Settings\GeneralSettings;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CurriculumPlanPrintController extends Controller
{
    public function __invoke(Request $request, CurriculumPlan $plan): View
    {
        abort_unless(auth()->user()?->hasAnyRole(['super_admin', 'admin', 'teacher']), 403);

        $plan->load(['schoolClass', 'subject', 'teacher', 'topics']);

        $objectives = ! empty($plan->learning_objective_ids)
            ? LearningObjective::whereIn('id', $plan->learning_objective_ids)->active()->ordered()->get()
            : collect();

        $models = ! empty($plan->learning_model_ids)
            ? LearningModel::whereIn('id', $plan->learning_model_ids)->active()->ordered()->get()
            : collect();

        $methods = ! empty($plan->default_methods)
            ? LearningMethod::whereIn('id', $plan->default_methods)->active()->ordered()->get()
            : collect();

        $mediaItems = ! empty($plan->default_media)
            ? LearningMedia::whereIn('id', array_filter($plan->default_media, fn ($id) => $id !== 'lainnya'))
                ->active()->ordered()->get()
            : collect();

        $allMedia     = $mediaItems->pluck('name')->toArray();
        if (in_array('lainnya', $plan->default_media ?? []) && $plan->default_media_other) {
            $allMedia[] = $plan->default_media_other;
        }

        $kkoLevels = KkoLevel::active()->ordered()->pluck('name', 'id');

        $topicsData = $plan->topics->map(function ($topic) use ($objectives, $methods, $mediaItems, $kkoLevels) {
            $topicObjectives = ! empty($topic->learning_objectives)
                ? $objectives->whereIn('id', $topic->learning_objectives)->pluck('name')->toArray()
                : $objectives->pluck('name')->toArray();

            $topicMethods = ! empty($topic->methods)
                ? LearningMethod::whereIn('id', $topic->methods)->ordered()->pluck('name')->toArray()
                : $methods->pluck('name')->toArray();

            $topicMedia = ! empty($topic->media)
                ? LearningMedia::whereIn('id', array_filter($topic->media, fn ($id) => $id !== 'lainnya'))
                    ->ordered()->pluck('name')->toArray()
                : $mediaItems->pluck('name')->toArray();

            $learningPaths = collect($topic->learning_paths ?? [])->map(function ($path) use ($kkoLevels) {
                return [
                    'description' => $path['description'] ?? '',
                    'kko_level'   => $kkoLevels[$path['kko_level_id'] ?? ''] ?? '-',
                ];
            })->toArray();

            return [
                'topic'          => $topic,
                'objectives'     => $topicObjectives,
                'methods'        => $topicMethods,
                'media'          => $topicMedia,
                'learning_paths' => $learningPaths,
            ];
        });

        $settings   = app(GeneralSettings::class);
        $schoolName = $settings->school_name ?? config('app.name');
        $schoolLogo = $settings->logo ?? null;

        return view('reports.curriculum-plan-print', compact(
            'plan',
            'objectives',
            'models',
            'methods',
            'allMedia',
            'topicsData',
            'schoolName',
            'schoolLogo',
        ));
    }
}
