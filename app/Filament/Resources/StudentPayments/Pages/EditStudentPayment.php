<?php

declare(strict_types=1);

namespace App\Filament\Resources\StudentPayments\Pages;

use App\Filament\Resources\StudentPayments\StudentPaymentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStudentPayment extends EditRecord
{
    protected static string $resource = StudentPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
