<?php

namespace App\Filament\Resources;

use App\Enums\SubmissionStatus;
use App\Enums\SubmissionType;
use App\Filament\Resources\SubmissionResource\Pages;
use App\Models\Submission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SubmissionResource extends Resource
{
    protected static ?string $model = Submission::class;
    protected static ?string $navigationIcon = 'heroicon-o-inbox';
    protected static ?string $navigationLabel = 'Aspirasi';
    protected static ?string $modelLabel = 'Aspirasi';
    protected static ?string $pluralModelLabel = 'Aspirasi';

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Identifikasi')
                ->schema([
                    Forms\Components\TextInput::make('tracking_code')
                        ->label('Kode Pelacakan')
                        ->disabled(),

                    Forms\Components\TextInput::make('type')
                        ->label('Jenis')
                        ->disabled(),

                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options(SubmissionStatus::options())
                        ->required()
                        ->live()
                        ->afterStateUpdated(function ($state, Forms\Set $set) {
                            if ($state === SubmissionStatus::Selesai->value ||
                                $state === SubmissionStatus::Ditolak->value ||
                                $state === SubmissionStatus::Ditutup->value) {
                                $set('resolved_at', now()->format('Y-m-d\TH:i'));
                            }
                        }),

                    Forms\Components\Select::make('priority')
                        ->label('Prioritas')
                        ->options([
                            'low'    => 'Rendah',
                            'normal' => 'Normal',
                            'high'   => 'Tinggi',
                            'urgent' => 'Mendesak',
                        ])
                        ->required(),
                ])
                ->columns(2),

            Forms\Components\Section::make('Isi Aspirasi')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Judul')
                        ->disabled(),

                    Forms\Components\Textarea::make('description')
                        ->label('Deskripsi')
                        ->disabled()
                        ->rows(5)
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('location_description')
                        ->label('Keterangan Lokasi')
                        ->disabled(),

                    Forms\Components\TextInput::make('incident_date')
                        ->label('Tanggal Kejadian')
                        ->disabled(),

                    Forms\Components\Section::make('Lampiran')
                        ->schema([
                            Forms\Components\ViewField::make('attachment')
                                ->label('Lampiran')
                                ->view('filament.forms.components.submission-attachment'),
                        ])
                        ->visible(fn ($record) => filled($record?->attachment))
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('recipient_type')
                        ->label('Tipe Penerima')
                        ->disabled(),

                    Forms\Components\TextInput::make('recipient_id')
                        ->label('ID Penerima')
                        ->disabled(),
                ])
                ->columns(2),

            Forms\Components\Section::make('Pelapor')
                ->schema([
                    Forms\Components\TextInput::make('identity_mode')
                        ->label('Mode Identitas')
                        ->disabled(),

                    Forms\Components\TextInput::make('reporter_name')
                        ->label('Nama')
                        ->disabled(),

                    Forms\Components\TextInput::make('reporter_phone')
                        ->label('Telepon')
                        ->disabled(),

                    Forms\Components\TextInput::make('reporter_email')
                        ->label('Email')
                        ->disabled(),
                ])
                ->columns(2),

            Forms\Components\Section::make('Tindak Lanjut')
                ->schema([
                    Forms\Components\Textarea::make('public_note')
                        ->label('Catatan Publik')
                        ->helperText('Catatan ini akan ditampilkan kepada pelapor di halaman tracking.')
                        ->rows(3)
                        ->columnSpanFull(),

                    Forms\Components\DateTimePicker::make('resolved_at')
                        ->label('Tanggal Diselesaikan')
                        ->seconds(false),
                ]),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tracking_code')
                    ->label('Kode')
                    ->searchable()
                    ->fontFamily('mono')
                    ->copyable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('type')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state instanceof SubmissionType ? $state->label() : $state),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => $state instanceof SubmissionStatus ? $state->color() : 'gray')
                    ->formatStateUsing(fn ($state) => $state instanceof SubmissionStatus ? $state->label() : $state),

                Tables\Columns\TextColumn::make('priority')
                    ->label('Prioritas')
                    ->badge()
                    ->color(fn (string $state) => match($state) {
                        'urgent' => 'danger',
                        'high'   => 'warning',
                        'normal' => 'gray',
                        'low'    => 'success',
                        default  => 'gray',
                    })
                    ->formatStateUsing(fn (string $state) => match($state) {
                        'urgent' => 'Mendesak',
                        'high'   => 'Tinggi',
                        'normal' => 'Normal',
                        'low'    => 'Rendah',
                        default  => $state,
                    }),

                Tables\Columns\TextColumn::make('submitted_at')
                    ->label('Dikirim')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('submitted_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(SubmissionStatus::options()),

                Tables\Filters\SelectFilter::make('type')
                    ->options(SubmissionType::options()),

                Tables\Filters\SelectFilter::make('priority')
                    ->options([
                        'low'    => 'Rendah',
                        'normal' => 'Normal',
                        'high'   => 'Tinggi',
                        'urgent' => 'Mendesak',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSubmissions::route('/'),
            'view'   => Pages\ViewSubmission::route('/{record}'),
            'edit'   => Pages\EditSubmission::route('/{record}/edit'),
        ];
    }
    public static function canCreate(): bool {
        return false;
    }

}
