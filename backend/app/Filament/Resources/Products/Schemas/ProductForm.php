<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductForm
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

            TextInput::make('price')
                ->numeric()
                ->prefix('Rp')
                ->required(),

            TextInput::make('stock')
                ->numeric()
                ->required(),

            TextInput::make('weight')
                ->label('Berat (gram)')
                ->numeric(),

            Textarea::make('description')
                ->label('Deskripsi')
                ->columnSpanFull(),

            FileUpload::make('image')
                ->label('Gambar Produk')
                ->image()
                ->disk('public')
                ->directory('products')
                ->imageEditor()
                ->maxSize(2048)
                ->fetchFileInformation(false)
                ->columnSpanFull(),
        ]);
    }
}