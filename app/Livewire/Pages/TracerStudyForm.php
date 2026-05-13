<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\TracerStudy;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class TracerStudyForm extends Component
{
    public string $name              = '';
    public string $email             = '';
    public string $phone             = '';
    public string $graduation_year   = '';
    public string $current_status    = '';
    public string $company_or_institution = '';
    public string $position          = '';
    public string $city              = '';
    public string $income_range      = '';
    public ?int   $school_relevance  = null;
    public ?int   $school_quality    = null;
    public string $suggestions       = '';
    public bool   $allow_publish     = false;
    public bool   $submitted         = false;

    protected function rules(): array
    {
        return [
            'name'                  => 'required|string|max:255',
            'email'                 => 'nullable|email|max:255',
            'phone'                 => 'nullable|string|max:30',
            'graduation_year'       => 'required|integer|min:1990|max:' . date('Y'),
            'current_status'        => 'required|in:working,studying,entrepreneur,both,unemployed,other',
            'company_or_institution'=> 'nullable|string|max:255',
            'position'              => 'nullable|string|max:255',
            'city'                  => 'nullable|string|max:100',
            'income_range'          => 'nullable|in:<2jt,2-5jt,5-10jt,10-20jt,>20jt,prefer_not_to_say',
            'school_relevance'      => 'nullable|integer|min:1|max:5',
            'school_quality'        => 'nullable|integer|min:1|max:5',
            'suggestions'           => 'nullable|string|max:2000',
            'allow_publish'         => 'boolean',
        ];
    }

    public function submit(): void
    {
        $data = $this->validate();

        TracerStudy::create($data);

        $this->submitted = true;
        $this->reset([
            'name', 'email', 'phone', 'graduation_year', 'current_status',
            'company_or_institution', 'position', 'city', 'income_range',
            'school_relevance', 'school_quality', 'suggestions', 'allow_publish',
        ]);
    }

    #[Layout('layouts.app')]
    #[Title('Tracer Study Alumni')]
    public function render()
    {
        return view('livewire.pages.tracer-study-form');
    }
}
