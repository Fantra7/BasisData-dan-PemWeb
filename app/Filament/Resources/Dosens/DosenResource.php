<?php

namespace App\Filament\Resources\Dosens;

use App\Filament\Resources\Dosens\Pages;
use App\Models\Dosen;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;

// Action classes terpusat (Filament v4 Style)
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

use Illuminate\Database\Eloquent\Builder;

class DosenResource extends Resource
{
    protected static ?string $model = Dosen::class;

    public static function canViewAny(): bool
    {
        // Admin mengelola semua dosen; dosen boleh melihat (dan mengedit) profilnya sendiri.
        return auth()->user()?->hasAnyRole(['admin', 'dosen']) ?? false;
    }

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
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Data Dosen';
    protected static string | \UnitEnum | null $navigationGroup = 'Data Master';
    protected static ?int $navigationSort = 4;
    protected static ?string $pluralModelLabel = 'Data Dosen';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nidn')
                    ->required()
                    ->maxLength(20)
                    ->unique(ignoreRecord: true),

                TextInput::make('nama')
                    ->required()
                    ->maxLength(100),

                TextInput::make('gelar')
                    ->required()
                    ->maxLength(50),

                    Select::make('id_prodi')
                    ->options(\App\Models\Prodi::pluck('nama_prodi', 'id'))
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Program Studi'),

                    \Filament\Forms\Components\TextInput::make('username')
                    ->label('Akun User (Username Baru)')
                    ->hiddenOn('edit')
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->placeholder('Ketik username...')
                    ->rules([
                        fn ($record) => function (string $attribute, $value, $fail) use ($record) {
                            $generatedEmail = $value . '@sttnf.ac.id';
                            if ($record && \App\Models\User::where('email', $generatedEmail)->where('id', '!=', $record->id_user)->exists()) {
                                $fail('Username ini sudah terpakai karena email ' . $generatedEmail . ' sudah terdaftar pada akun lain.');
                            } else if (!$record && \App\Models\User::where('email', $generatedEmail)->exists()) {
                                $fail('Username ini sudah terpakai karena email ' . $generatedEmail . ' sudah terdaftar.');
                            }
                        },
                    ])
                    ->maxLength(255),

                    \Filament\Forms\Components\FileUpload::make('foto')
                    ->label('Foto Dosen')
                    ->image()
                    ->disk('public')
                    ->directory('foto-dosen')
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
                Tables\Columns\TextColumn::make('nidn')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('gelar'),
                Tables\Columns\TextColumn::make('prodi.nama_prodi')
                ->label('Program Studi')
                ->sortable(),
                Tables\Columns\TextColumn::make('id_user')->label('ID User'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
    ->before(function (DeleteAction $action, $record) {
        if ($record->jadwalKuliah()->exists()) {
            \Filament\Notifications\Notification::make()
                ->title('Data tidak dapat dihapus')
                ->body('Dosen ini masih mengampu jadwal kuliah. Pindahkan atau hapus jadwal terkait terlebih dahulu.')
                ->danger()
                ->send();

            $action->halt();
        }
    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
    ->before(function (DeleteBulkAction $action, \Illuminate\Support\Collection $records) {
        $terpakai = $records->filter(fn ($r) => $r->jadwalKuliah()->exists());

        if ($terpakai->isNotEmpty()) {
            \Filament\Notifications\Notification::make()
                ->title('Sebagian data tidak dapat dihapus')
                ->body('Dosen ini masih mengampu jadwal kuliah. Pindahkan atau hapus jadwal terkait terlebih dahulu.')
                ->danger()
                ->send();

            $action->halt();
        }
    }),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()?->hasRole('dosen')) {
            return $query->where('id_user', auth()->id());
        }

        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDosens::route('/'),
            'create' => Pages\CreateDosen::route('/create'),
            'edit' => Pages\EditDosen::route('/{record}/edit'),
        ];
    }
}
