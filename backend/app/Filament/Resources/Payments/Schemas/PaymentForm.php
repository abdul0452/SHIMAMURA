<?php

namespace App\Filament\Resources\Payments\Schemas;

use App\Models\Order;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('order_id')
                ->label('Order')
                ->relationship('order', 'id')
                ->getOptionLabelFromRecordUsing(
                    fn (Order $record): string => 'Order #'.$record->id.' - '.$record->buyer_name
                )
                ->searchable(['id'])
                ->preload()
                ->required()
                ->unique(ignoreRecord: true),

            Select::make('method')
                ->options([
                    'midtrans' => 'Midtrans',
                    'cash' => 'Cash (manual)',
                    'transfer' => 'Transfer Bank (manual)',
                ])
                ->required(),

            Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'settlement' => 'Settlement (Lunas)',
                    'expire' => 'Expired',
                    'cancel' => 'Cancelled',
                    'deny' => 'Denied',
                ])
                ->required(),

            TextInput::make('amount')
                ->numeric()
                ->prefix('Rp')
                ->required(),

            TextInput::make('transaction_id')
                ->label('Transaction ID (dari Midtrans)')
                ->maxLength(255)
                ->disabled()
                ->dehydrated(),
        ]);
    }
}
