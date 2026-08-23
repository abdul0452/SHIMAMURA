<?php

namespace App\Filament\Resources\Stores\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class StoreForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('user_id')
                ->label('User (Pemilik Toko)')
                ->relationship(
                    name: 'user',
                    titleAttribute: 'name',
                    modifyQueryUsing: fn ($query) => $query->where('role', 'customer'),
                )
                ->required()
                ->searchable()
                ->preload(),

            TextInput::make('store_name')
                ->required()
                ->maxLength(255),

            Textarea::make('address')
                ->columnSpanFull(),
        ]);
    }
}