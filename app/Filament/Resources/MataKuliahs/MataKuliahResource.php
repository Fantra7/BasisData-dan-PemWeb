<?php

namespace App\Filament\Resources\MataKuliahs;

use App\Filament\Resources\MataKuliahs\Pages;
use App\Models\MataKuliah;
use Filament\Resources\Resource;
use Filament\Schemas\Schema; // Menggunakan Schema sesuai referensi
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

// Action classes terpusat sesuai pola sukses MahasiswaResource
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

use Illuminate\Database\Eloquent\Builder;

class MataKuliahResource extends Resource
{
    protected static ?string $model = MataKuliah::class;

    public static function canViewAny(): bool
{
    return auth()->user()->hasRole('admin');
}

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Mata Kuliah';
    protected static string | \UnitEnum | null $navigationGroup = 'Data Master';
    protected static ?int $navigationSort = 2;
    protected static ?string $pluralModelLabel = 'Mata Kuliah';

    // Form disesuaikan menggunakan Schema $schema & ->components()
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kode_mk')
                    ->required()
                    ->maxLength(15)
                    ->unique(ignoreRecord: true),

                TextInput::make('nama_mk')
                    ->required()
                    ->maxLength(100),

                TextInput::make('sks')
                    ->numeric()
                    ->required(),

                TextInput::make('semester')
                    ->numeric()
                    ->required(),

                Select::make('id_prodi')
                    ->relationship('prodi', 'nama_prodi')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Program Studi'),
            ]);
    }

    // Tabel disesuaikan menggunakan ->recordActions() & ->toolbarActions()
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_mk')->searchable(),
                Tables\Columns\TextColumn::make('nama_mk')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('sks'),
                Tables\Columns\TextColumn::make('semester'),
                Tables\Columns\TextColumn::make('prodi.nama_prodi')->label('Prodi'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
    ->before(function (DeleteAction $action, $record) {
        if ($record->jadwalKuliah()->exists()) {
            \Filament\Notifications\Notification::make()
                ->title('Data tidak dapat dihapus')
                ->body('Mata kuliah ini masih dipakai pada jadwal kuliah. Hapus jadwal terkait terlebih dahulu.')
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
                ->body('Mata kuliah ini masih dipakai pada jadwal kuliah. Hapus jadwal terkait terlebih dahulu.')
                ->danger()
                ->send();

            $action->halt();
        }
    }),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMataKuliahs::route('/'),
            'create' => Pages\CreateMataKuliah::route('/create'),
            'edit' => Pages\EditMataKuliah::route('/{record}/edit'),
        ];
    }
}
