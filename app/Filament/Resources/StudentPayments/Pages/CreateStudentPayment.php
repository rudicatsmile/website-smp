<?php

declare(strict_types=1);

namespace App\Filament\Resources\StudentPayments\Pages;

use App\Filament\Resources\StudentPayments\StudentPaymentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStudentPayment extends CreateRecord
{
    protected static string $resource = StudentPaymentResource::class;
}
