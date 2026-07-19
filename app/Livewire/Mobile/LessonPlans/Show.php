<?php

namespace App\Livewire\Mobile\LessonPlans;

use App\Models\CurriculumPlan;
use App\Models\CurriculumPlanTopic;
use App\Models\LearningObjective;
use App\Models\LearningMethod;
use App\Models\KkoLevel;
use App\Models\LearningMedia;
use App\Services\CurriculumPlanService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.mobile')]
#[Title('Detail Rencana Pembelajaran')]
class Show extends Component
{
    public CurriculumPlan $plan;

    // Topic Modal State
    public $isTopicModalOpen = false;
    public $topicModalMode = 'create';
    public $topicId = null;

    // Topic Form Fields
    public $week_number = 1;
    public $order = 1;
    public $topic = '';
    public $learning_objectives = [];
    public $learning_paths = [];
    public $methods = [];
    public $media = [];
    public $assessment_plan = '';
    public $notes = '';
    public $default_duration_minutes = 90;

    // Apply Modal State
    public $isApplyModalOpen = false;

    // Apply Form Fields
    public $start_date = '';
    public $end_date = '';
    public $weekdays = [1, 2, 3, 4, 5]; // Senin - Jumat
    public $start_time = '07:30';
    public $end_time = '09:00';
    public $period = '';
    public $skip_holidays = true;
    public $publish_immediately = false;
    
    public $notification = null;

    public function mount(CurriculumPlan $plan)
    {
        $user = Auth::user();
        if (!$user || !$user->staffMember || $plan->staff_member_id != $user->staffMember->id) {
            return redirect()->route('mobile.plans');
        }
        $this->plan = $plan->load('topics', 'subject');
        
        $this->start_date = now()->format('Y-m-d');
        $this->end_date = now()->addMonths(6)->format('Y-m-d');
    }

    public function openTopicModal()
    {
        $this->resetValidation();
        $this->reset(['topicId', 'topic', 'learning_objectives', 'learning_paths', 'methods', 'media', 'assessment_plan', 'notes']);
        $this->week_number = $this->plan->topics()->max('week_number') ?? 1;
        $this->order = ($this->plan->topics()->where('week_number', $this->week_number)->max('order') ?? 0) + 1;
        $this->default_duration_minutes = 90;
        
        $this->topicModalMode = 'create';
        $this->isTopicModalOpen = true;
    }

    public function editTopic($id)
    {
        $this->resetValidation();
        $topic = CurriculumPlanTopic::findOrFail($id);
        
        $this->topicId = $topic->id;
        $this->week_number = $topic->week_number;
        $this->order = $topic->order;
        $this->topic = $topic->topic;
        $this->learning_objectives = $topic->learning_objectives ?? [];
        $this->learning_paths = is_array($topic->learning_paths) ? $topic->learning_paths : json_decode($topic->learning_paths ?? '[]', true);
        if (!$this->learning_paths) $this->learning_paths = [];
        $this->methods = $topic->methods ?? [];
        $this->media = $topic->media ?? [];
        $this->assessment_plan = $topic->assessment_plan;
        $this->notes = $topic->notes;
        $this->default_duration_minutes = $topic->default_duration_minutes;

        $this->topicModalMode = 'edit';
        $this->isTopicModalOpen = true;
    }

    public function addLearningPath()
    {
        $this->learning_paths[] = ['description' => '', 'kko_level_id' => ''];
    }

    public function removeLearningPath($index)
    {
        unset($this->learning_paths[$index]);
        $this->learning_paths = array_values($this->learning_paths);
    }

    public function saveTopic()
    {
        $this->validate([
            'week_number' => 'required|integer|min:1',
            'order' => 'required|integer|min:1',
            'topic' => 'required|string|max:255',
            'default_duration_minutes' => 'required|integer|min:1',
            'learning_paths.*.description' => 'required|string|max:500',
            'learning_paths.*.kko_level_id' => 'required',
        ]);

        $data = [
            'curriculum_plan_id' => $this->plan->id,
            'week_number' => $this->week_number,
            'order' => $this->order,
            'topic' => $this->topic,
            'learning_objectives' => array_filter($this->learning_objectives),
            'learning_paths' => array_values($this->learning_paths),
            'methods' => array_filter($this->methods),
            'media' => array_filter($this->media),
            'assessment_plan' => $this->assessment_plan,
            'notes' => $this->notes,
            'default_duration_minutes' => $this->default_duration_minutes,
        ];

        if ($this->topicModalMode === 'create') {
            CurriculumPlanTopic::create($data);
        } else {
            CurriculumPlanTopic::findOrFail($this->topicId)->update($data);
        }

        $this->plan->load('topics'); // Refresh
        $this->isTopicModalOpen = false;
        $this->showNotification('Topik berhasil disimpan.');
    }

    public function deleteTopic($id)
    {
        CurriculumPlanTopic::findOrFail($id)->delete();
        $this->plan->load('topics'); // Refresh
        $this->showNotification('Topik berhasil dihapus.');
    }

    public function openApplyModal()
    {
        $this->resetValidation();
        $this->isApplyModalOpen = true;
    }

    public function applyToDates()
    {
        $this->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'weekdays' => 'required|array|min:1',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $result = app(CurriculumPlanService::class)->applyToDateRange(
            plan: $this->plan,
            startDate: Carbon::parse($this->start_date),
            endDate: Carbon::parse($this->end_date),
            weekdays: array_map('intval', $this->weekdays),
            startTime: $this->start_time,
            endTime: $this->end_time,
            period: $this->period,
            skipHolidays: $this->skip_holidays,
            publishImmediately: $this->publish_immediately
        );

        $this->isApplyModalOpen = false;
        $this->showNotification("{$result['created']} sesi dibuat, {$result['skipped']} dilewati.");
    }
    
    public function showNotification($message)
    {
        $this->notification = $message;
        $this->dispatch('show-notification');
    }

    public function render()
    {
        // Load options for the topic form based on plan's defaults
        $availableObjectives = [];
        if ($this->plan->learning_objective_ids) {
            $availableObjectives = LearningObjective::whereIn('id', $this->plan->learning_objective_ids)
                ->orderBy('name')->pluck('name', 'id')->toArray();
        }
        
        $availableMethods = [];
        if ($this->plan->default_methods) {
            $availableMethods = LearningMethod::whereIn('id', $this->plan->default_methods)
                ->orderBy('name')->pluck('name', 'id')->toArray();
        }

        $availableMedia = [];
        if ($this->plan->default_media) {
            $availableMedia = LearningMedia::whereIn('id', $this->plan->default_media)
                ->orderBy('name')->pluck('name', 'id')->toArray();
        }

        return view('livewire.mobile.lesson-plans.show', [
            'availableObjectives' => $availableObjectives,
            'availableMethods' => $availableMethods,
            'availableMedia' => $availableMedia,
            'kkoLevels' => KkoLevel::active()->ordered()->pluck('name', 'id')->toArray(),
        ]);
    }
}
