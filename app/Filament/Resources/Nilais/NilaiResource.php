<?php

namespace App\Filament\Resources\Nilais;

use App\Filament\Resources\Nilais\Pages;
use App\Models\DetailKrs;
use App\Models\Nilai;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class NilaiResource extends Resource
{
    protected static ?string $model = Nilai::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static ?string $navigationLabel = 'Data Nilai';
    protected static string | \UnitEnum | null $navigationGroup = 'Penilaian';
    protected static ?int $navigationSort = 1;
    protected static ?string $pluralModelLabel = 'Data Nilai';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'dosen', 'mahasiswa']) ?? false;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if (! $user) {
            return $query->whereRaw('1 = 0');
        }

        if ($user->hasRole('mahasiswa')) {
            return $query->whereHas('detailKrs.krs.mahasiswa', fn ($q) => $q->where('id_user', $user->id));
        }

        if ($user->hasRole('dosen')) {
            return $query->whereHas('detailKrs.jadwalKuliah.dosen', fn ($q) => $q->where('id_user', $user->id));
        }

        return $query;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Akademik')
                    ->schema([
                        Select::make('id_detail_krs')
                            ->label('Mahasiswa - Mata Kuliah')
                            ->options(function () {
                                $query = DetailKrs::with([
                                    'krs.mahasiswa',
                                    'jadwalKuliah.mataKuliah',
                                    'jadwalKuliah.dosen',
                                ])->whereHas('krs', fn ($q) => $q->where('status_krs', 'approved'));

                                if (auth()->user()?->hasRole('dosen')) {
                                    $query->whereHas('jadwalKuliah.dosen', fn ($q) => $q->where('id_user', auth()->id()));
                                }

                                return $query->get()->mapWithKeys(function ($record) {
                                    $nim = $record->krs?->mahasiswa?->nim ?? '-';
                                    $nama = $record->krs?->mahasiswa?->nama ?? '-';
                                    $mk = $record->jadwalKuliah?->mataKuliah?->nama_mk ?? '-';
                                    $kelas = $record->jadwalKuliah?->kode_kelas ?? '-';

                                    return [$record->id => "{$nim} - {$nama} - {$mk} - Kelas {$kelas}"];
                                });
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled(fn () => auth()->user()?->hasRole('mahasiswa')),
                    ]),

                Section::make('Komponen Nilai')
                    ->schema([
                        TextInput::make('nilai_angka')
                            ->label('Nilai Angka')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->required()
                            ->disabled(fn () => auth()->user()?->hasRole('mahasiswa'))
                            ->helperText('Nilai huruf dihitung otomatis oleh trigger database.'),

                        TextInput::make('nilai_huruf')
                            ->label('Nilai Huruf')
                            ->disabled()
                            ->dehydrated(false),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('detailKrs.krs.mahasiswa.nim')
                    ->label('NIM')
                    ->searchable(),

                TextColumn::make('detailKrs.krs.mahasiswa.nama')
                    ->label('Nama Mahasiswa')
                    ->searchable(),

                TextColumn::make('detailKrs.jadwalKuliah.mataKuliah.kode_mk')
                    ->label('Kode MK'),

                TextColumn::make('detailKrs.jadwalKuliah.mataKuliah.nama_mk')
                    ->label('Mata Kuliah')
                    ->searchable(),

                TextColumn::make('detailKrs.jadwalKuliah.kode_kelas')
                    ->label('Kelas'),

                TextColumn::make('detailKrs.jadwalKuliah.dosen.nama')
                    ->label('Dosen'),

                TextColumn::make('nilai_angka')
                    ->label('Nilai Angka'),

                TextColumn::make('nilai_huruf')
                    ->label('Nilai Huruf')
                    ->badge(),
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn () => auth()->user()?->hasAnyRole(['admin', 'dosen'])),
                DeleteAction::make()
                    ->visible(fn () => auth()->user()?->hasRole('admin')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()?->hasRole('admin')),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNilais::route('/'),
            'create' => Pages\CreateNilai::route('/create'),
            'edit' => Pages\EditNilai::route('/{record}/edit'),
        ];
    }
}
