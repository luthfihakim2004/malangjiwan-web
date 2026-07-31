<?php

namespace App\Filament\Pages;

use App\Enums\ContactType;
use App\Models\Profile as ProfileModel;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class Profile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';
    protected static ?string $navigationLabel = 'Profil Desa';
    protected static ?string $title = 'Profil Desa';
    protected static string $view = 'filament.pages.profile';

    public ProfileModel $profile;
    public ?array $data = [];

    public function mount(): void
    {
        $this->profile = ProfileModel::current();
        $this->form->fill($this->profile->toArray());
    }

    public function form(Form $form): Form
    {
        return $form->model($this->profile)
            ->schema([
                TextInput::make('nama_desa')
                    ->label('Nama Desa')
                    ->required(),

                RichEditor::make('sejarah')
                    ->label('Sejarah Desa')
                    ->columnSpanFull()
                    ->extraInputAttributes(['style'=>'min-height: 500px']),

                RichEditor::make('visi')
                    ->label('Visi'),

                RichEditor::make('misi')
                    ->label('Misi'),

                RichEditor::make('struktur_organisasi')
                    ->label('Struktur Organisasi')
                    ->columnSpanFull(),

                TextInput::make('alamat_kantor')
                    ->label('Alamat Kantor Desa'),

                Repeater::make('contacts')
                    ->label('Kontak & Media Sosial')
                    ->relationship('contacts')
                    ->schema([
                        Select::make('type')
                            ->label('Jenis')
                            ->options(ContactType::options())
                            ->required()
                            ->native(false),

                        TextInput::make('label')
                            ->label('Label')
                            ->placeholder('Contoh: Kantor Desa, Kepala Desa, Admin')
                            ->maxLength(255),

                        TextInput::make('value')
                            ->label('Kontak')
                            ->placeholder('Contoh: 08123456789, desa_malangjiwan, atau desa.id')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->columns(3)
                    ->defaultItems(0)
                    ->addActionLabel('Tambah Kontak')
                    ->reorderable(false)
                    ->collapsible()
                    ->columnSpanFull(),

                FileUpload::make('logo')
                    ->label('Logo Desa')
                    ->image()
                    ->directory('profil-desa'),

                FileUpload::make('foto_kantor')
                    ->label('Foto Kantor Desa')
                    ->image()
                    ->directory('profil-desa'),

                FileUpload::make('hero_image')
                    ->label('Foto Beranda')
                    ->image()
                    ->directory('profil-desa')
                    ->helperText('Foto latar belakang bagian atas halaman beranda. Gunakan foto landscape minimal 1920×600px.'),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        $this->profile->update(
            collect($state)
                ->except('contacts')
                ->toArray()
        );

        $this->form->model($this->profile)->saveRelationships();

        Notification::make()
            ->title('Profil desa berhasil disimpan')
            ->success()
            ->send();
    }
}
