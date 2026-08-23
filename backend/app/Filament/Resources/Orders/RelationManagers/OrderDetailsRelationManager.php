<?php

namespace App\Filament\Resources\Orders\RelationManagers;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

class OrderDetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'orderDetails';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('product_id')
                ->relationship('product', 'name')
                ->required()
                ->searchable()
                ->preload(),

            TextInput::make('quantity')
                ->numeric()
                ->required(),

            TextInput::make('unit_price')
                ->numeric()
                ->prefix('Rp')
                ->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product.name')
            ->columns([
                TextColumn::make('product.name')->label('Produk'),
                TextColumn::make('quantity'),
                TextColumn::make('unit_price')->money('IDR'),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}