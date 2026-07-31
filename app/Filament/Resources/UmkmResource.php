<?php

namespace App\Filament\Resources;

use App\Enums\ContactType;
use App\Filament\Resources\UmkmResource\Pages;
use App\Filament\Resources\UmkmResource\RelationManagers;
use App\Models\Umkm;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class UmkmResource extends Resource
{
    protected static ?string $model = Umkm::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'UMKM';
    protected static ?string $pluralModelLabel = 'UMKM';
    protected static ?string $modelLabel = 'UMKM';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi UMKM')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, Forms\set $set)=>
                                $set('slug', Str::slug($state))
                            ),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->prefix('malangjiwan.com/umkm/')
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('owner')
                            ->label('Pemilik')
                            ->maxLength(255),

                        Forms\Components\Select::make('tags')
                            ->relationship('tags', 'nama')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('nama')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, Forms\Set $set) =>
                                        $set('slug', \Illuminate\Support\Str::slug($state))
                                    ),

                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->unique('tags', 'slug'),
                            ]),

                        Forms\Components\Textarea::make('deskripsi')
                            ->rows(5)
                            ->columnSpanFull(),

                        // Media Gallery
                        Forms\Components\Repeater::make('media')
                            ->relationship()
                            ->schema([
                                Forms\Components\FileUpload::make('path')
                                    ->label('Foto')
                                    ->image()
                                    ->disk('public')
                                    ->directory('gallery')
                                    ->visibility('public')
                                    ->openable()
                                    ->downloadable()
                                    ->required(),
                                Forms\Components\TextInput::make('caption')
                                    ->label('Caption')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0)
                                    ->hidden()           // Hide it if you only want drag & drop
                                    ->dehydrated(false), // Important: don't send it twice
                            ])
                            ->addActionLabel('Tambah Gambar')
                            ->orderColumn('sort_order')        // ← This is the key
                            ->reorderableWithDragAndDrop(true) // Explicitly enable drag & drop
                            ->columns(2)
                            ->columnSpanFull(),

                    ])->columns(2),


                Forms\Components\Section::make('Lokasi & Kontak')
                    ->schema([
                        Forms\Components\TextInput::make('alamat')
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('latitude')
                            ->numeric(),

                        Forms\Components\TextInput::make('longitude')
                            ->numeric(),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TimePicker::make('jam_buka')
                                    ->label('Jam Buka')
                                    ->seconds(false)
                                    ->native(false)
                                    ->displayFormat('H:i'),

                                Forms\Components\TimePicker::make('jam_tutup')
                                    ->label('Jam Tutup')
                                    ->seconds(false)
                                    ->native(false)
                                    ->displayFormat('H:i'),
                            ]),

                        Forms\Components\Repeater::make('contacts')
                            ->label('Kontak & Media Sosial')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('type')
                                    ->label('Jenis')
                                    ->options(ContactType::options())
                                    ->required()
                                    ->native(false),

                                Forms\Components\TextInput::make('label')
                                    ->label('Label')
                                    ->placeholder('Contoh: Pemilik, Admin, Reservasi')
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('value')
                                    ->label('Kontak')
                                    ->placeholder('Contoh: 08123456789, @namaumkm, atau website.com')
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->columns(3)
                            ->defaultItems(0)
                            ->addActionLabel('Tambah Kontak')
                            ->reorderable(false)
                            ->collapsible()
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Pengaturan')
                    ->schema([
                        Forms\Components\Toggle::make('featured')
                            ->label('Featured')
                            ->helperText('Tampilkan UMKM ini di halaman unggulan')
                            ->default(false),

                        Forms\Components\Toggle::make('publish')
                            ->label('Publish')
                            ->helperText('Tampilkan UMKM ini di halaman publik')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('cover_image')
                    ->label('Foto')
                    ->disk('public')
                    ->square(),

                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('owner')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tags.nama')
                    ->label('Tags')
                    ->badge()
                    ->separator(','),

                Tables\Columns\IconColumn::make('featured')
                    ->label('Featured')
                    ->boolean(),

                Tables\Columns\IconColumn::make('publish')
                    ->label('Publish')
                    ->boolean(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diupdate')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('publish')
                    ->label('Status Publish'),

                Tables\Filters\TernaryFilter::make('featured')
                    ->label('Featured'),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUmkms::route('/'),
            'create' => Pages\CreateUmkm::route('/create'),
            'edit' => Pages\EditUmkm::route('/{record}/edit'),
        ];
    }
}
