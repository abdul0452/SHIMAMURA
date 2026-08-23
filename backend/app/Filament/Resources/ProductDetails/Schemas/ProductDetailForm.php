<?php

namespace App\Filament\Resources\ProductDetails\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductDetailForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('product_id')
                ->label('Product')
                ->relationship('product', 'name')
                ->searchable()
                ->preload()
                ->required()
                ->unique(ignoreRecord: true),

            Textarea::make('description')
                ->required()
                ->columnSpanFull(),

            TextInput::make('weight')
                ->numeric()
                ->suffix('gram')
                ->required(),
        ]);
    }
}
