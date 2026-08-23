<?php

namespace App\Filament\Resources\OrderDetails\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrderDetailsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order.id')
                    ->label('Order #')
                    ->sortable(),

                TextColumn::make('order.user.name')
                    ->label('Customer'),

                TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable(),

                TextColumn::make('quantity'),

                TextColumn::make('unit_price')
                    ->money('IDR'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
