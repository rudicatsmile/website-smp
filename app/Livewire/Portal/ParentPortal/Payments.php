<?php

declare(strict_types=1);

namespace App\Livewire\Portal\ParentPortal;

use App\Models\Student;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.portal')]
#[Title('Pembayaran')]
class Payments extends Component
{
    public Student $student;

    public function mount(Student $student): void
    {
        $user = auth()->user();
        abort_unless($user?->hasRole('parent'), 403);
        abort_unless($user->children()->whereKey($student->id)->exists(), 403, 'Bukan anak Anda.');
        $this->student = $student;
    }

    public function render()
    {
        $items = $this->student->payments()
            ->orderByRaw("CASE status WHEN 'overdue' THEN 0 WHEN 'unpaid' THEN 1 ELSE 2 END")
            ->orderByDesc('due_date')
            ->get();

        $unpaid = $items->whereIn('status', ['unpaid', 'overdue']);
        $totalUnpaid = (int) $unpaid->sum('amount');
        $totalPaid = (int) $items->where('status', 'paid')->sum('paid_amount');

        return view('livewire.portal.parent.payments', [
            'items' => $items,
            'totalUnpaid' => $totalUnpaid,
            'totalPaid' => $totalPaid,
        ]);
    }
}
