<?php

namespace App\Filament\Resources\EmployeeDetails\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EmployeeDetailsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.name')
                    ->label('Employee')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('employee.store.store_name')
                    ->label('Store'),

                TextColumn::make('employee_number')
                    ->searchable(),

                TextColumn::make('date_of_joining')
                    ->date()
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
