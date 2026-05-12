<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\CurriculumPlan;
use App\Models\CurriculumPlanTopic;
use App\Models\LessonSession;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CurriculumPlanService
{
    /**
     * Bulk-apply curriculum plan topics to actual lesson sessions over a date range.
     *
     * @param array<int> $weekdays  [1,3,5] = Mon/Wed/Fri
     * @return array{created: int, skipped: int, sessions: Collection}
     */
    public function applyToDateRange(
        CurriculumPlan $plan,
        Carbon $startDate,
        Carbon $endDate,
        array $weekdays,
        string $startTime,
        string $endTime,
        ?string $period = null,
        bool $skipHolidays = true,
        bool $publishImmediately = false,
    ): array {
        $topics = $plan->topics()->orderBy('week_number')->orderBy('order')->get();
        if ($topics->isEmpty()) {
            return ['created' => 0, 'skipped' => 0, 'sessions' => collect()];
        }

        $period = CarbonPeriod::create($startDate, $endDate);
        $sessions = collect();
        $created = 0;
        $skipped = 0;

        // Group dates by ISO week number, then map topics sequentially
        $datesByWeek = [];
        foreach ($period as $date) {
            if (! in_array((int) $date->dayOfWeek ?: 7, $weekdays, true)) {
                continue;
            }
            if ($skipHolidays && $this->isHoliday($date)) {
                $skipped++;
                continue;
            }
            $weekNum = (int) $date->weekOfYear;
            $datesByWeek[$weekNum][] = $date;
        }

        if (empty($datesByWeek)) {
            return ['created' => 0, 'skipped' => $skipped, 'sessions' => collect()];
        }

        // Sort weeks and assign topics round-robin
        ksort($datesByWeek);
        $weekIndex = 0;
        $rows = [];

        foreach ($datesByWeek as $weekNum => $dates) {
            // Determine which topic(s) apply this week
            // Use modulo to cycle through topics if more weeks than topics
            $topicIndex = $weekIndex % $topics->count();
            $topic = $topics[$topicIndex];

            foreach ($dates as $date) {
                $rows[] = [
                    'school_class_id' => $plan->school_class_id,
                    'material_category_id' => $plan->material_category_id,
                    'staff_member_id' => $plan->staff_member_id,
                    'curriculum_plan_id' => $plan->id,
                    'curriculum_plan_topic_id' => $topic->id,
                    'session_date' => $date->format('Y-m-d'),
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'period' => $period,
                    'topic' => $topic->topic,
                    'learning_objectives' => $topic->learning_objectives,
                    'methods' => $topic->methods ?? $plan->default_methods,
                    'media' => $topic->media ?? $plan->default_media,
                    'assessment_plan' => $topic->assessment_plan,
                    'status' => $publishImmediately ? 'published' : 'draft',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $created++;
            }
            $weekIndex++;
        }

        if (! empty($rows)) {
            LessonSession::insert($rows);
            $sessions = LessonSession::where('curriculum_plan_id', $plan->id)
                ->whereBetween('session_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                ->orderBy('session_date')
                ->get();
        }

        Log::info('[CurriculumPlanService] Bulk-apply selesai', [
            'plan_id' => $plan->id,
            'created' => $created,
            'skipped' => $skipped,
            'range' => $startDate->toDateString() . ' – ' . $endDate->toDateString(),
        ]);

        return ['created' => $created, 'skipped' => $skipped, 'sessions' => $sessions];
    }

    /**
     * Distribute topics across already-generated sessions in sequence.
     */
    public function distributeTopics(CurriculumPlan $plan, Collection $sessions): void
    {
        $topics = $plan->topics()->orderBy('week_number')->orderBy('order')->get();
        if ($topics->isEmpty() || $sessions->isEmpty()) {
            return;
        }

        $sorted = $sessions->sortBy('session_date')->values();
        $topicIndex = 0;

        foreach ($sorted as $i => $session) {
            $topic = $topics[$topicIndex % $topics->count()];
            $session->update([
                'curriculum_plan_topic_id' => $topic->id,
                'topic' => $topic->topic,
                'learning_objectives' => $topic->learning_objectives,
                'methods' => $topic->methods ?? $plan->default_methods,
                'media' => $topic->media ?? $plan->default_media,
                'assessment_plan' => $topic->assessment_plan,
            ]);
            $topicIndex++;
        }
    }

    public function publishPlan(CurriculumPlan $plan): void
    {
        $plan->sessions()->where('status', 'draft')->update(['status' => 'published']);
    }

    public function unpublishSession(LessonSession $session, string $reason): void
    {
        $session->update([
            'status' => 'cancelled',
            'cancelled_reason' => $reason,
        ]);
    }

    protected function isHoliday(Carbon $date): bool
    {
        // Sunday always skipped
        if ($date->isSunday()) {
            return true;
        }
        // Check against holidays table if it exists
        if (class_exists(\App\Models\Holiday::class)) {
            try {
                return DB::table('holidays')->where('date', $date->format('Y-m-d'))->exists();
            } catch (\Throwable) {
                // table might not exist yet
            }
        }
        return false;
    }
}
