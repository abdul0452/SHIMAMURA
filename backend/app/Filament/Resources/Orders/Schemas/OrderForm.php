<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('user_id')
                ->label('Customer (jika login)')
                ->relationship('user', 'name')
                ->searchable()
                ->preload()
                ->helperText('Kosongkan jika order berasal dari checkout tanpa login (guest).'),

            TextInput::make('guest_name')
                ->label('Nama Guest')
                ->maxLength(255),

            TextInput::make('guest_email')
                ->label('Email Guest')
                ->email()
                ->maxLength(255),

            TextInput::make('guest_phone')
                ->label('No. HP Guest')
                ->tel()
                ->maxLength(20),

            Select::make('status')
                ->options([
                    'pending_payment' => 'Menunggu Pembayaran',
                    'paid' => 'Sudah Dibayar',
                    'processing' => 'Diproses',
                    'shipped' => 'Dikirim',
                    'completed' => 'Selesai',
                    'expired' => 'Kedaluwarsa',
                    'cancelled' => 'Dibatalkan',
                ])
                ->required(),

            TextInput::make('total_amount')
                ->numeric()
                ->prefix('Rp')
                ->required(),
        ]);
    }
}
