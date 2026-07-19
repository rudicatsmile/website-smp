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
        array $schedules,
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

        // Create a lookup for schedule by weekday
        $scheduleMap = [];
        foreach ($schedules as $s) {
            $scheduleMap[(int) $s['weekday']] = $s;
        }

        // Collect all valid dates in sequence
        $activeDates = [];
        foreach ($period as $date) {
            $dayOfWeek = (int) $date->dayOfWeek ?: 7;
            if (! isset($scheduleMap[$dayOfWeek])) {
                continue;
            }
            if ($skipHolidays && $this->isHoliday($date)) {
                $skipped++;
                continue;
            }
            $activeDates[] = [
                'date' => $date,
                'start_time' => $scheduleMap[$dayOfWeek]['start_time'],
                'end_time' => $scheduleMap[$dayOfWeek]['end_time'],
            ];
        }

        if (empty($activeDates)) {
            return ['created' => 0, 'skipped' => $skipped, 'sessions' => collect()];
        }

        $rows = [];
        $sessionIndex = 0;

        foreach ($activeDates as $item) {
            $date = $item['date'];
            $startTime = $item['start_time'];
            $endTime = $item['end_time'];

            // Sequential 1-to-1 mapping based on session count
            $topicIndex = $sessionIndex % $topics->count();
            $topic = $topics[$topicIndex];

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
                'learning_objectives' => json_encode($topic->learning_objectives ?? []),
                'methods' => json_encode($topic->methods ?? $plan->default_methods ?? []),
                'media' => json_encode($topic->media ?? $plan->default_media ?? []),
                'assessment_plan' => $topic->assessment_plan,
                'status' => $publishImmediately ? 'published' : 'draft',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $created++;
            $sessionIndex++;
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
                'learning_objectives' => $topic->learning_objectives ?? [],
                'methods' => $topic->methods ?? $plan->default_methods ?? [],
                'media' => $topic->media ?? $plan->default_media ?? [],
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
