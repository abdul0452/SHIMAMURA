<?php

namespace App\Filament\Resources\Employees\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EmployeesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('position'),
                TextColumn::make('store.store_name')->label('Store')->sortable(),
                TextColumn::make('employee_number')->label('No. Karyawan')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('date_of_joining')->date()->label('Tgl Bergabung')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}