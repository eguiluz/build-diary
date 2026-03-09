<?php

declare(strict_types=1);

namespace App\Filament\Resources\InventoryItemResource\Pages;

use App\Filament\Resources\InventoryItemResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateInventoryItem extends CreateRecord
{
    protected static string $resource = InventoryItemResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();

        return $data;
    }
}
