<?php

namespace App\Filament\Resources\OrderDetails\Schemas;

use App\Models\Order;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderDetailForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('order_id')
                ->label('Order')
                ->relationship('order', 'id')
                ->getOptionLabelFromRecordUsing(
                    fn (Order $record): string => 'Order #'.$record->id.' - '.($record->user?->name ?? '-')
                )
                ->searchable(['id'])
                ->preload()
                ->required(),

            Select::make('product_id')
                ->label('Product')
                ->relationship('product', 'name')
                ->searchable()
                ->preload()
                ->required(),

            TextInput::make('quantity')
                ->numeric()
                ->minValue(1)
                ->required(),

            TextInput::make('unit_price')
                ->numeric()
                ->prefix('Rp')
                ->required(),
        ]);
    }
}
