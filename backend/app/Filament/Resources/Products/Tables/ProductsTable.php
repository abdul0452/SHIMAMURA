<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Gambar')
                    ->defaultImageUrl(fn ($record) => $record->image_url)
                    ->square()
                    ->height(80),

                TextColumn::make('name')->searchable(),
                TextColumn::make('store.store_name')->label('Store'),
                TextColumn::make('price')->money('IDR'),
                TextColumn::make('stock'),
                TextColumn::make('weight')->label('Berat (g)')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}