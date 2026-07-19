<?php

namespace App\Livewire\Mobile\LessonPlans;

use App\Models\CurriculumPlan;
use App\Models\SchoolClass;
use App\Models\MaterialCategory;
use App\Models\LearningObjective;
use App\Models\LearningModel;
use App\Models\LearningMethod;
use App\Models\LearningMedia;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.mobile')]
#[Title('Rencana Pembelajaran - Mobile')]
class Index extends Component
{
    use WithPagination;

    // Form State
    public $isModalOpen = false;
    public $modalMode = 'create';
    public $planId = null;

    // Form Fields
    public $title = '';
    public $academic_year = '';
    public $semester = 'ganjil';
    public $time_allocation = '';
    public $school_class_id = null;
    public $material_category_id = null;
    public $learning_objective_ids = [];
    public $learning_model_ids = [];
    public $default_methods = [];
    public $default_media = [];
    public $default_media_other = '';
    public $is_active = true;

    // Listeners for dynamic updates
    public function updatedMaterialCategoryId()
    {
        // Reset objectives if subject changes
        $this->learning_objective_ids = [];
    }

    public function openCreateModal()
    {
        $this->resetValidation();
        $this->reset(['title', 'academic_year', 'time_allocation', 'school_class_id', 'material_category_id', 'learning_objective_ids', 'learning_model_ids', 'default_methods', 'default_media', 'default_media_other', 'planId']);
        $this->semester = 'ganjil';
        $this->is_active = true;
        $this->modalMode = 'create';
        $this->isModalOpen = true;
    }

    public function openEditModal($id)
    {
        $this->resetValidation();
        $plan = CurriculumPlan::findOrFail($id);
        
        $this->planId = $plan->id;
        $this->title = $plan->title;
        $this->academic_year = $plan->academic_year;
        $this->semester = $plan->semester;
        $this->time_allocation = $plan->time_allocation;
        $this->school_class_id = $plan->school_class_id;
        $this->material_category_id = $plan->material_category_id;
        $this->learning_objective_ids = $plan->learning_objective_ids ?? [];
        $this->learning_model_ids = $plan->learning_model_ids ?? [];
        $this->default_methods = $plan->default_methods ?? [];
        $this->default_media = $plan->default_media ?? [];
        $this->default_media_other = $plan->default_media_other;
        $this->is_active = $plan->is_active;

        $this->modalMode = 'edit';
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function save()
    {
        $user = Auth::user();
        if (!$user || !$user->staffMember) return;

        $this->validate([
            'title' => 'required|string|max:200',
            'school_class_id' => 'required|exists:school_classes,id',
            'material_category_id' => 'required|exists:material_categories,id',
            'academic_year' => 'required|string|max:20',
            'semester' => 'required|in:ganjil,genap',
            'time_allocation' => 'nullable|string|max:50',
            'learning_objective_ids' => 'nullable|array',
            'learning_model_ids' => 'nullable|array',
            'default_methods' => 'nullable|array',
            'default_media' => 'nullable|array',
        ]);

        $data = [
            'staff_member_id' => $user->staffMember->id,
            'title' => $this->title,
            'school_class_id' => $this->school_class_id,
            'material_category_id' => $this->material_category_id,
            'academic_year' => $this->academic_year,
            'semester' => $this->semester,
            'time_allocation' => $this->time_allocation,
            'learning_objective_ids' => array_filter($this->learning_objective_ids),
            'learning_model_ids' => array_filter($this->learning_model_ids),
            'default_methods' => array_filter($this->default_methods),
            'default_media' => array_filter($this->default_media),
            'default_media_other' => in_array('lainnya', (array)$this->default_media) ? $this->default_media_other : null,
            'is_active' => $this->is_active,
        ];

        if ($this->modalMode === 'create') {
            $data['created_by'] = $user->id;
            CurriculumPlan::create($data);
        } else {
            CurriculumPlan::findOrFail($this->planId)->update($data);
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        $user = Auth::user();
        if (!$user || !$user->staffMember) return;

        $plan = CurriculumPlan::findOrFail($id);
        if ($plan->staff_member_id === $user->staffMember->id) {
            $plan->delete();
        }
    }

    public function render()
    {
        $user = Auth::user();
        
        $plans = collect();
        $teacherSubjects = [];
        $learningObjectives = [];

        if ($user && $user->staffMember) {
            // Load plans
            $plans = CurriculumPlan::with(['schoolClass', 'subject'])
                ->where('staff_member_id', $user->staffMember->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
                
            // Load teacher's subjects for dropdown
            $teacherSubjects = $user->staffMember->teachingSubjects()->pluck('name', 'material_categories.id')->toArray();
            
            // Load learning objectives if subject is selected
            if ($this->material_category_id) {
                $learningObjectives = LearningObjective::where('material_category_id', $this->material_category_id)
                    ->orderBy('name')->pluck('name', 'id')->toArray();
            }
        }

        return view('livewire.mobile.lesson-plans.index', [
            'plans' => $plans,
            'classes' => SchoolClass::orderBy('name')->pluck('name', 'id')->toArray(),
            'subjects' => $teacherSubjects,
            'objectives' => $learningObjectives,
            'models' => LearningModel::orderBy('name')->pluck('name', 'id')->toArray(),
            'methods' => LearningMethod::orderBy('name')->pluck('name', 'id')->toArray(),
            'medias' => LearningMedia::orderBy('name')->pluck('name', 'id')->toArray(),
        ]);
    }
}
