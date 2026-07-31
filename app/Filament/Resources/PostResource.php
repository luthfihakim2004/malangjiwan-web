<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?string $navigationLabel = 'Post';
    protected static ?string $pluralModelLabel = 'Posts';
    protected static ?string $modelLabel = 'Post';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Konten Utama')
                    ->schema([
                        Forms\Components\TextInput::make('judul')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, Forms\Set $set) =>
                                $set('slug', Str::slug($state))
                            ),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->prefix('malangjiwan.com/post/')
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

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
                                        $set('slug', Str::slug($state))
                                    ),
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->unique('tags', 'slug'),
                            ]),

                        Forms\Components\RichEditor::make('body')
                            ->label('Isi Post')
                            ->required()
                            ->columnSpanFull()
                            ->extraInputAttributes(['style'=>'min-height: 500px'])
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, Forms\Set $set) =>
                                $set('excerpt', Str::limit(strip_tags($state), 150))
                            )
                            ->dehydrateStateUsing(function ($state) {
                                if (empty($state)) {
                                    return $state;
                                }

                                $dom = new \DOMDocument();
                                @$dom->loadHTML(
                                    mb_convert_encoding($state, 'HTML-ENTITIES', 'UTF-8'),
                                    LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
                                );

                                $links = $dom->getElementsByTagName('a');
                                $appUrl = config('app.url');

                            foreach ($links as $link) {
                                $href = trim($link->getAttribute('href'));

                                // Tambahkan https:// jika user hanya mengetik domain
                                if (
                                    ! empty($href) &&
                                    ! str_starts_with($href, '#') &&
                                    ! str_starts_with($href, '/') &&
                                    ! preg_match('/^[a-z][a-z0-9+\-.]*:/i', $href)
                                ) {
                                    $href = 'https://' . $href;
                                    $link->setAttribute('href', $href);
                                }

                                $isInternal = parse_url($href, PHP_URL_HOST) === parse_url(config('app.url'), PHP_URL_HOST)
                                    || str_starts_with($href, '/')
                                    || ! str_starts_with($href, 'http');

                                if ($isInternal) {
                                    $link->removeAttribute('target');
                                    $link->removeAttribute('rel');
                                } else {
                                    $link->setAttribute('target', '_blank');
                                    $link->setAttribute('rel', 'noopener noreferrer');
                                }
                            }

                                return $dom->saveHTML();
                            }),

                        Forms\Components\TextInput::make('excerpt')
                            ->label('Ringkasan')
                            ->readOnly()
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

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

                // Publication
                Forms\Components\Section::make('Publikasi')
                    ->schema([
                        Forms\Components\Toggle::make('publish')
                            ->label('Publikasikan')
                            ->default(false)
                            ->live()
                            ->afterStateUpdated(function (bool $state, Forms\Get $get, Forms\Set $set) {
                                if ($state && blank($get('published_at'))) {
                                    $set('published_at', now()->format('Y-m-d\TH:i'));
                                }
                            }),

                        Forms\Components\DateTimePicker::make('published_at')
                            ->label('Tanggal Publikasi')
                            ->seconds(false),
                    ])
                    ->columns(2),

                Forms\Components\Select::make('postable_type')
                    ->label('Tautkan ke')
                    ->options([
                        'wisata'    => 'Wisata',
                        'umkm'      => 'UMKM',
                    ])
                    ->live()
                    ->nullable()
                    ->placeholder('Tidak ditautkan'),

                Forms\Components\Select::make('postable_id')
                    ->label('Pilih UMKM / Wisata')
                    ->options(function (Forms\Get $get) {
                        return match($get('postable_type')) {
                            'wisata' => \App\Models\Wisata::where('publish', true)->pluck('nama', 'id'),
                            'umkm'  => \App\Models\Umkm::where('publish', true)->pluck('nama', 'id'),
                            default => [],
                        };
                    })
                    ->nullable()
                    ->hidden(fn (Forms\Get $get) => blank($get('postable_type'))),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('cover_image')
                    ->label('Cover')
                    ->disk('public')
                    ->square()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('judul')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('tags.nama')
                    ->label('Tags')
                    ->badge()
                    ->separator(',')
                    ->toggleable(),

                Tables\Columns\IconColumn::make('publish')
                    ->label('Status')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Dipublikasikan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diupdate')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('publish'),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
