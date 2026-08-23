<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('store_id')
                ->label('Store')
                ->relationship('store', 'store_name')
                ->required()
                ->searchable()
                ->preload(),

            TextInput::make('name')
                ->required()
                ->maxLength(255),

            TextInput::make('position')
                ->required()
                ->maxLength(255),

            TextInput::make('employee_number')
                ->label('Nomor Karyawan')
                ->maxLength(255),

            DatePicker::make('date_of_joining')
                ->label('Tanggal Bergabung'),
        ]);
    }
}