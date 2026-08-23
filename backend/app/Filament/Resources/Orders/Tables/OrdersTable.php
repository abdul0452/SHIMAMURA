<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('#'),

                TextColumn::make('user.name')
                    ->label('Customer')
                    ->default(fn ($record) => $record->guest_name)
                    ->description(fn ($record) => $record->user ? 'Login' : 'Guest'),

                TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid', 'processing', 'shipped', 'completed' => 'success',
                        'pending_payment' => 'warning',
                        default => 'danger',
                    }),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
