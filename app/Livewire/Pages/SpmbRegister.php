<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Mail\SpmbRegistrationConfirmation;
use App\Models\SpmbDocument;
use App\Models\SpmbPeriod;
use App\Models\SpmbRegistration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

class SpmbRegister extends Component
{
    use WithFileUploads;

    public ?SpmbPeriod $period = null;
    public int $step = 1;

    // Step 1
    public string $full_name = '';
    public string $nick_name = '';
    public string $gender = 'L';
    public string $birth_place = '';
    public string $birth_date = '';
    public string $nik = '';
    public string $nisn = '';
    public string $religion = 'Islam';
    public string $address = '';
    public string $phone = '';
    public string $email = '';

    // Step 2
    public string $father_name = '';
    public string $father_job = '';
    public string $father_phone = '';
    public string $mother_name = '';
    public string $mother_job = '';
    public string $mother_phone = '';
    public string $guardian_name = '';
    public string $previous_school = '';
    public string $graduation_year = '';
    public string $npsn = '';

    // Step 3 - documents
    public $doc_kk = null;
    public $doc_akta = null;
    public $doc_foto = null;
    public $doc_ijazah = null;
    public $doc_raport = null;

    public ?SpmbRegistration $created = null;

    public function mount(): void
    {
        $this->period = SpmbPeriod::active();
        abort_unless($this->period, 404, 'Periode SPMB belum dibuka.');
    }

    protected function rulesStep1(): array
    {
        return [
            'full_name' => 'required|string|max:120',
            'nick_name' => 'nullable|string|max:50',
            'gender' => 'required|in:L,P',
            'birth_place' => 'required|string|max:100',
            'birth_date' => 'required|date',
            'nik' => 'nullable|string|max:32',
            'nisn' => 'nullable|string|max:32',
            'religion' => 'required|string|max:30',
            'address' => 'required|string|max:500',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:160',
        ];
    }

    protected function rulesStep2(): array
    {
        return [
            'father_name' => 'required|string|max:120',
            'father_job' => 'nullable|string|max:80',
            'father_phone' => 'nullable|string|max:30',
            'mother_name' => 'required|string|max:120',
            'mother_job' => 'nullable|string|max:80',
            'mother_phone' => 'nullable|string|max:30',
            'guardian_name' => 'nullable|string|max:120',
            'previous_school' => 'required|string|max:120',
            'graduation_year' => 'required|string|max:10',
            'npsn' => 'nullable|string|max:30',
        ];
    }

    protected function rulesStep3(): array
    {
        $rule = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120';
        return [
            'doc_kk' => $rule,
            'doc_akta' => $rule,
            'doc_foto' => 'nullable|image|max:2048',
            'doc_ijazah' => $rule,
            'doc_raport' => $rule,
        ];
    }

    public function next(): void
    {
        if ($this->step === 1) {
            $this->validate($this->rulesStep1());
        } elseif ($this->step === 2) {
            $this->validate($this->rulesStep2());
        }
        $this->step++;
    }

    public function back(): void
    {
        if ($this->step > 1) $this->step--;
    }

    public function submit(): void
    {
        $this->validate(array_merge($this->rulesStep1(), $this->rulesStep2(), $this->rulesStep3()));

        DB::transaction(function () {
            $reg = SpmbRegistration::create([
                'spmb_period_id' => $this->period->id,
                'registration_number' => SpmbRegistration::generateNumber(),
                'full_name' => $this->full_name,
                'nick_name' => $this->nick_name ?: null,
                'gender' => $this->gender,
                'birth_place' => $this->birth_place,
                'birth_date' => $this->birth_date,
                'nik' => $this->nik ?: null,
                'nisn' => $this->nisn ?: null,
                'religion' => $this->religion,
                'address' => $this->address,
                'phone' => $this->phone ?: null,
                'email' => $this->email ?: null,
                'father_name' => $this->father_name,
                'father_job' => $this->father_job ?: null,
                'father_phone' => $this->father_phone ?: null,
                'mother_name' => $this->mother_name,
                'mother_job' => $this->mother_job ?: null,
                'mother_phone' => $this->mother_phone ?: null,
                'guardian_name' => $this->guardian_name ?: null,
                'previous_school' => $this->previous_school,
                'graduation_year' => $this->graduation_year,
                'npsn' => $this->npsn ?: null,
                'status' => 'pending',
            ]);

            foreach (['kk' => $this->doc_kk, 'akta' => $this->doc_akta, 'foto' => $this->doc_foto, 'ijazah' => $this->doc_ijazah, 'raport' => $this->doc_raport] as $type => $file) {
                if ($file) {
                    $path = $file->store('spmb/'.$reg->id, 'public');
                    SpmbDocument::create(['spmb_registration_id' => $reg->id, 'type' => $type, 'file_path' => $path]);
                }
            }

            $this->created = $reg;

            if ($reg->email) {
                try {
                    Mail::to($reg->email)->send(new SpmbRegistrationConfirmation($reg));
                } catch (\Throwable $e) {
                    report($e);
                }
            }
        });

        $this->step = 4;
    }

    #[Layout('layouts.app')]
    #[Title('Pendaftaran SPMB')]
    public function render()
    {
        return view('livewire.pages.spmb-register');
    }
}
