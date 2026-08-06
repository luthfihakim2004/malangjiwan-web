<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlaceResource\Pages;
use App\Filament\Resources\PlaceResource\RelationManagers;
use App\Models\Place;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PlaceResource extends Resource
{
    protected static ?string $model = Place::class;
    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationLabel = 'Tempat';
    protected static ?string $modelLabel = 'Tempat';
    protected static ?string $pluralModelLabel = 'Tempat';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()
                ->schema([
                    Forms\Components\TextInput::make('nama')
                        ->label('Nama Tempat')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\Select::make('kategori')
                        ->label('Kategori')
                        ->options(Place::kategoriOptions())
                        ->required()
                        ->native(false),

                    Forms\Components\TextInput::make('latitude')
                        ->label('Latitude')
                        ->numeric()
                        ->required()
                        ->placeholder('-7.6905'),

                    Forms\Components\TextInput::make('longitude')
                        ->label('Longitude')
                        ->numeric()
                        ->required()
                        ->placeholder('110.5548'),

                    Forms\Components\Toggle::make('publish')
                        ->label('Tampilkan di Peta')
                        ->default(true),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kategori')
                    ->label('Kategori')
                    ->badge()
                    ->formatStateUsing(fn ($state) => Place::kategoriOptions()[$state] ?? $state)
                    ->color(fn (string $state) => match($state) {
                        'kuliner'        => 'warning',
                        'penginapan'     => 'info',
                        'fasilitas_umum' => 'success',
                        default          => 'gray',
                    }),

                Tables\Columns\TextColumn::make('latitude')
                    ->label('Lat')
                    ->fontFamily('mono')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('longitude')
                    ->label('Lng')
                    ->fontFamily('mono')
                    ->toggleable(),

                Tables\Columns\IconColumn::make('publish')
                    ->label('Tampil')
                    ->boolean(),
            ])
            ->defaultSort('kategori')
            ->filters([
                Tables\Filters\SelectFilter::make('kategori')
                    ->options(Place::kategoriOptions()),

                Tables\Filters\TernaryFilter::make('publish')
                    ->label('Status Tampil'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('publish')
                        ->label('Tampilkan di Peta')
                        ->icon('heroicon-o-eye')
                        ->action(fn ($records) => $records->each->update(['publish' => true])),
                    Tables\Actions\BulkAction::make('unpublish')
                        ->label('Sembunyikan dari Peta')
                        ->icon('heroicon-o-eye-slash')
                        ->action(fn ($records) => $records->each->update(['publish' => false])),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPlaces::route('/'),
            'create' => Pages\CreatePlace::route('/create'),
            'edit'   => Pages\EditPlace::route('/{record}/edit'),
        ];
    }
}
