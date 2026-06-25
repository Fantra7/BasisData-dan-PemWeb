<?php

namespace App\Filament\Resources\Mahasiswas;

use App\Filament\Resources\Mahasiswas\Pages;
use App\Models\Mahasiswa;
use App\Models\User;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;


use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

use Illuminate\Database\Eloquent\Builder;

class MahasiswaResource extends Resource
{
    protected static ?string $model = Mahasiswa::class;

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'dosen', 'mahasiswa']) ?? false;
    }

    // Pendaftaran mahasiswa baru = wewenang admin (bagian akademik).
    public static function canCreate(): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Data Mahasiswa';
    protected static string | \UnitEnum | null $navigationGroup = 'Data Master';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        // Mahasiswa hanya boleh memperbarui data kontak pribadinya sendiri;
        // identitas akademik (nim, nama, jenis kelamin, angkatan, prodi) dikunci.
        $terkunciUntukMahasiswa = fn (): bool => auth()->user()?->hasRole('mahasiswa') ?? false;

        return $schema
            ->components([
                TextInput::make('nim')
                    ->required()
                    ->maxLength(20)
                    ->unique(table: 'mahasiswa', column: 'nim', ignoreRecord: true)
                    ->disabled($terkunciUntukMahasiswa),

                TextInput::make('nama')
                    ->required()
                    ->maxLength(100)
                    ->disabled($terkunciUntukMahasiswa),

                Select::make('jenis_kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ])
                    ->required()
                    ->disabled($terkunciUntukMahasiswa),

                Textarea::make('alamat')
                    ->columnSpanFull(),

                TextInput::make('no_hp')
                    ->maxLength(20),

                TextInput::make('angkatan')
                    ->numeric()
                    ->required()
                    ->disabled($terkunciUntukMahasiswa),

                TextInput::make('username')
                    ->label('Akun User (Username Baru)')
                    ->hiddenOn('edit')
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->placeholder('Ketik username (contoh: Mh2026)...')
                    ->rules([
                        // Validasi kustom: Cek apakah email hasil kombinasi username sudah terdaftar atau belum (abaikan yang saat ini sedang diedit)
                        fn ($record) => function (string $attribute, $value, $fail) use ($record) {
                            $generatedEmail = $value . '@sttnf.ac.id';
                            // Jika ada $record (edit form), pastikan yang di cek tidak termasuk id_user saat ini
                            if ($record && User::where('email', $generatedEmail)->where('id', '!=', $record->id_user)->exists()) {
                                $fail('Username ini sudah terpakai karena email ' . $generatedEmail . ' sudah terdaftar pada akun lain.');
                            } else if (!$record && User::where('email', $generatedEmail)->exists()) {
                                $fail('Username ini sudah terpakai karena email ' . $generatedEmail . ' sudah terdaftar.');
                            }
                        },
                    ]),

                    Select::make('id_prodi')
                    ->label('Program Studi')
                    ->relationship('prodi', 'nama_prodi')
                    ->required()
                    ->disabled($terkunciUntukMahasiswa),

                    \Filament\Forms\Components\FileUpload::make('foto')
                    ->label('Foto Mahasiswa')
                    ->image()
                    ->disk('public')
                    ->directory('foto-mahasiswa')
                    ->avatar()
                    ->maxSize(10240)
                    ->nullable(),
            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto')
                    ->label('Foto')
                    ->disk('public')
                    ->circular(),
                Tables\Columns\TextColumn::make('nim')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('jenis_kelamin')->label('JK'),
                Tables\Columns\TextColumn::make('angkatan')->sortable(),
                Tables\Columns\TextColumn::make('prodi.nama_prodi')->label('Prodi'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()?->hasRole('mahasiswa')) {
            return $query->where('id_user', auth()->id());
        }

        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListMahasiswas::route('/'),
            'create' => Pages\CreateMahasiswa::route('/create'),
            'edit'   => Pages\EditMahasiswa::route('/{record}/edit'),
        ];
    }
}
