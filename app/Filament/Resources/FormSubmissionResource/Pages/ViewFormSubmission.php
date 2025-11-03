<?php

namespace App\Filament\Resources\FormSubmissionResource\Pages;

use App\Filament\Resources\FormSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewFormSubmission extends ViewRecord
{
    protected static string $resource = FormSubmissionResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $this->record->update(['is_read' => true]);
        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}