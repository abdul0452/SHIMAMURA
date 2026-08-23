<?php

namespace App\Filament\Resources\Payments\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order.id')
                    ->label('Order #')
                    ->sortable(),

                TextColumn::make('order.buyer_name')
                    ->label('Pembeli'),

                TextColumn::make('method')
                    ->badge(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'settlement' => 'success',
                        'pending' => 'warning',
                        default => 'danger',
                    }),

                TextColumn::make('amount')
                    ->money('IDR'),

                TextColumn::make('paid_at')
                    ->dateTime()
                    ->placeholder('-')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
