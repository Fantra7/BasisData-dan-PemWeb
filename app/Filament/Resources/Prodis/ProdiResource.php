<?php

namespace App\Filament\Resources\Prodis;

use App\Filament\Resources\Prodis\Pages;
use App\Models\Prodi;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;

// Action classes terpusat sesuai pola sukses MahasiswaResource
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

use Illuminate\Database\Eloquent\Builder;

class ProdiResource extends Resource
{
    protected static ?string $model = Prodi::class;

    public static function canViewAny(): bool
{
    return auth()->user()->hasRole('admin');
}

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationLabel = 'Program Studi';
    protected static string | \UnitEnum | null $navigationGroup = 'Data Master';
    protected static ?int $navigationSort = 1;
    protected static ?string $pluralModelLabel = 'Program Studi';


    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kode_prodi')
                    ->required()
                    ->maxLength(10)
                    ->unique(ignoreRecord: true),

                TextInput::make('nama_prodi')
                    ->required()
                    ->maxLength(100),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_prodi')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('nama_prodi')->searchable()->sortable(),
            ])
            ->recordActions([ // Menggunakan recordActions sesuai referensi sukses
                EditAction::make(),
                DeleteAction::make()
    ->before(function (DeleteAction $action, $record) {
        if ($record->dosen()->exists()
            || $record->mahasiswa()->exists()
            || $record->mataKuliah()->exists()) {
            \Filament\Notifications\Notification::make()
                ->title('Program Studi tidak dapat dihapus')
                ->body('Masih ada dosen, mahasiswa, atau mata kuliah yang terdaftar pada prodi ini. Pindahkan atau hapus data tersebut terlebih dahulu.')
                ->danger()
                ->send();

            $action->halt();
        }
    }),
            ])
            ->toolbarActions([ // Menggunakan toolbarActions sesuai referensi sukses
                BulkActionGroup::make([
                    DeleteBulkAction::make()
    ->before(function (DeleteBulkAction $action, \Illuminate\Support\Collection $records) {
        $terpakai = $records->filter(fn ($r) =>
            $r->dosen()->exists()
            || $r->mahasiswa()->exists()
            || $r->mataKuliah()->exists()
        );

        if ($terpakai->isNotEmpty()) {
            \Filament\Notifications\Notification::make()
                ->title('Sebagian Program Studi tidak dapat dihapus')
                ->body('Prodi berikut masih dipakai: ' . $terpakai->pluck('nama_prodi')->join(', ') . '. Pindahkan atau hapus data terkait terlebih dahulu.')
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
            'index' => Pages\ListProdis::route('/'),
            'create' => Pages\CreateProdi::route('/create'),
            'edit' => Pages\EditProdi::route('/{record}/edit'),
        ];
    }
}
