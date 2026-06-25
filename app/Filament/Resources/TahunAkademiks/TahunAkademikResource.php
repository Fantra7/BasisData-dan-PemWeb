<?php

namespace App\Filament\Resources\TahunAkademiks;

use App\Filament\Resources\TahunAkademiks\Pages;
use App\Models\TahunAkademik;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;

// Action classes terpusat sesuai pola sukses MahasiswaResource
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

use Illuminate\Database\Eloquent\Builder;

class TahunAkademikResource extends Resource
{
    protected static ?string $model = TahunAkademik::class;

    public static function canViewAny(): bool
{

    return auth()->user()->hasRole('admin');
}

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'Tahun Akademik';
    protected static string | \UnitEnum | null $navigationGroup = 'Data Master';
    protected static ?int $navigationSort = 5;
    protected static ?string $pluralModelLabel = 'Tahun Akademik';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('tahun')
                    ->required()
                    ->maxLength(9)
                    ->placeholder('2025/2026'),

                Select::make('semester')
                    ->options([
                        'Ganjil' => 'Ganjil',
                        'Genap' => 'Genap',
                    ])
                    ->required(),

                Toggle::make('status_aktif')
                    ->label('Status Aktif')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tahun')->sortable(),
                Tables\Columns\TextColumn::make('semester'),
                Tables\Columns\IconColumn::make('status_aktif')->boolean()->label('Aktif'),
            ])
            ->recordActions([ // Menggunakan recordActions sesuai referensi sukses
                EditAction::make(),
                DeleteAction::make()
    ->before(function (DeleteAction $action, $record) {
        if ($record->krs()->exists() || $record->jadwalKuliah()->exists()) {
            \Filament\Notifications\Notification::make()
                ->title('Data tidak dapat dihapus')
                ->body('Tahun akademik ini masih dipakai pada KRS atau jadwal kuliah. Hapus data terkait terlebih dahulu.')
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
        $terpakai = $records->filter(fn ($r) => $r->krs()->exists() || $r->jadwalKuliah()->exists());

        if ($terpakai->isNotEmpty()) {
            \Filament\Notifications\Notification::make()
                ->title('Sebagian data tidak dapat dihapus')
                ->body('Tahun akademik ini masih dipakai pada KRS atau jadwal kuliah. Hapus data terkait terlebih dahulu.')
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
            'index' => Pages\ListTahunAkademiks::route('/'),
            'create' => Pages\CreateTahunAkademik::route('/create'),
            'edit' => Pages\EditTahunAkademik::route('/{record}/edit'),
        ];
    }
}
