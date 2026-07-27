<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VegetasiSpeciesResource\Pages;
use App\Models\VegetasiSpecies;
use App\Models\Wisata;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class VegetasiSpeciesResource extends Resource
{
    protected static ?string $model = VegetasiSpecies::class;
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationLabel = 'Vegetasi';
    protected static ?string $modelLabel = 'Vegetasi';
    protected static ?string $pluralModelLabel = 'Vegetasi';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Tumbuhan')
                ->schema([
                    Forms\Components\TextInput::make('nama_lokal')
                        ->label('Nama Lokal')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, Forms\Set $set) =>
                            $set('slug', Str::slug($state))
                        ),

                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->helperText('Digunakan sebagai URL QR code: /vegetasi/{slug}'),

                    Forms\Components\TextInput::make('nama_ilmiah')
                        ->label('Nama Ilmiah')
                        ->placeholder('e.g. Tectona grandis')
                        ->maxLength(255),

                    Forms\Components\Select::make('wisata_id')
                        ->label('Lokasi Wisata (opsional)')
                        ->options(
                            Wisata::where('publish', true)
                                ->orderBy('nama')
                                ->pluck('nama', 'id')
                        )
                        ->searchable()
                        ->nullable()
                        ->placeholder('Tidak terkait wisata'),

                    Forms\Components\Toggle::make('publish')
                        ->label('Publikasikan')
                        ->default(true),

                    Forms\Components\RichEditor::make('deskripsi')
                        ->label('Deskripsi')
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('fun_fact')
                        ->label('Fun Fact')
                        ->placeholder('Fakta menarik tentang tumbuhan ini...')
                        ->rows(3)
                        ->columnSpanFull(),

                    Forms\Components\FileUpload::make('image')
                        ->label('Foto')
                        ->image()
                        ->disk('public')
                        ->directory('vegetasi')
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Foto')
                    ->square(),

                Tables\Columns\TextColumn::make('nama_lokal')
                    ->label('Nama Lokal')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_ilmiah')
                    ->label('Nama Ilmiah')
                    ->searchable()
                    ->extraAttributes(['class' => 'italic'])
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('wisata.nama')
                    ->label('Lokasi Wisata')
                    ->placeholder('—')
                    ->toggleable(),

                Tables\Columns\IconColumn::make('publish')
                    ->boolean(),
            ])
            ->defaultSort('nama_lokal')
            ->filters([
                Tables\Filters\TernaryFilter::make('publish'),
                Tables\Filters\SelectFilter::make('wisata_id')
                    ->label('Lokasi Wisata')
                    ->options(
                        Wisata::where('publish', true)->orderBy('nama')->pluck('nama', 'id')
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListVegetasiSpecies::route('/'),
            'create' => Pages\CreateVegetasiSpecies::route('/create'),
            'edit'   => Pages\EditVegetasiSpecies::route('/{record}/edit'),
        ];
    }
}
