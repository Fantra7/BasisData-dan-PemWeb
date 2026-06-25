<?php

namespace App\Filament\Resources\Krs;

use App\Filament\Resources\Krs\Pages;
use App\Models\JadwalKuliah;
use App\Models\Krs;
use App\Models\Mahasiswa;
use App\Models\TahunAkademik;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class KrsResource extends Resource
{
    protected static ?string $model = Krs::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationLabel = 'Pengisian KRS';
    protected static string | \UnitEnum | null $navigationGroup = 'Perkuliahan';
    protected static ?int $navigationSort = 2;
    protected static ?string $pluralModelLabel = 'KRS Mahasiswa';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'dosen', 'mahasiswa']) ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DateTimePicker::make('tanggal_krs')
                    ->label('Tanggal KRS')
                    ->default(now())
                    ->required(),

                Select::make('status_krs')
                    ->label('Status KRS')
                    ->options([
                        'draft'     => 'Draft',
                        'submitted' => 'Diajukan',
                        'approved'  => 'Disetujui',
                        'rejected'  => 'Ditolak',
                    ])
                    ->default(fn () => auth()->user()?->hasRole('admin') ? 'approved' : 'submitted')
                    ->disabled(fn () => auth()->user()?->hasRole('mahasiswa'))
                    ->dehydrated(),

                TextInput::make('total_sks')
                    ->label('Total SKS')
                    ->numeric()
                    ->default(0)
                    ->disabled()
                    ->dehydrated()
                    ->helperText('Total SKS dihitung otomatis dari mata kuliah yang dipilih.'),

                Select::make('id_mahasiswa')
                    ->label('Mahasiswa')
                    ->options(fn () => Mahasiswa::query()
                        ->orderBy('nama')
                        ->pluck('nama', 'id'))
                    ->searchable()
                    ->preload()
                    ->default(fn () => Mahasiswa::where('id_user', auth()->id())->value('id'))
->disabled(fn () => auth()->user()?->hasAnyRole(['mahasiswa', 'dosen']))
->dehydrated(),

                Select::make('id_ta')
                    ->label('Tahun Akademik')
                    ->options(fn () => TahunAkademik::query()
                        ->orderByDesc('status_aktif')
                        ->orderByDesc('tahun')
                        ->get()
                        ->mapWithKeys(fn ($ta) => [
                            $ta->id => $ta->tahun . ' - ' . $ta->semester . ($ta->status_aktif ? ' (Aktif)' : ''),
                        ]))
                   ->default(fn () => TahunAkademik::where('status_aktif', 1)->value('id'))
->searchable()
->preload()
->required(),

               Repeater::make('detailKrs')
    ->label('Mata Kuliah yang Diambil')
    ->relationship()
    ->disabled(fn () => auth()->user()?->hasRole('dosen'))
    ->schema([
                        Select::make('id_jadwal')
                            ->label('Pilih Mata Kuliah & Jadwal')
                            ->options(function () {
                                return JadwalKuliah::with(['mataKuliah', 'dosen', 'tahunAkademik'])
                                    ->whereHas('tahunAkademik', fn ($q) => $q->where('status_aktif', 1))
                                    ->get()
                                    ->mapWithKeys(function ($record) {
                                        $namaMk = $record->mataKuliah?->nama_mk ?? 'Mata Kuliah Tidak Ditemukan';
                                        $sks = $record->mataKuliah?->sks ?? 0;
                                        $kelas = $record->kode_kelas ?? '-';
                                        $hari = $record->hari ?? '-';
                                        $jamMulai = $record->jam_mulai ?? '-';
                                        $jamSelesai = $record->jam_selesai ?? '-';
                                        $ruang = $record->ruang ?? '-';
                                        $dosen = $record->dosen?->nama ?? 'Dosen Belum Ada';

                                        return [
                                            $record->id => "{$namaMk} ({$sks} SKS) - Kelas {$kelas} - {$hari} {$jamMulai}-{$jamSelesai} - Ruang {$ruang} - {$dosen}",
                                        ];
                                    });
                            })
                            ->searchable()
                            ->preload()
                            ->distinct()
                            ->required(),
                    ])
                    ->columns(1)
                    ->columnSpanFull()
                    ->minItems(1)
                    ->helperText('Pilih jadwal kuliah, bukan hanya nama mata kuliah. Jadwal sudah terhubung ke dosen, ruang, kuota, dan tahun akademik.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mahasiswa.nim')
                    ->label('NIM')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('mahasiswa.nama')
                    ->label('Nama Mahasiswa')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tahunAkademik.tahun')
                    ->label('Tahun Akademik'),

                Tables\Columns\TextColumn::make('tahunAkademik.semester')
                    ->label('Semester'),

                Tables\Columns\TextColumn::make('detailKrs.jadwalKuliah.mataKuliah.nama_mk')
                    ->label('Mata Kuliah yang Diambil')
                    ->badge()
                    ->color('info')
                    ->placeholder('Belum memilih MK'),

                Tables\Columns\TextColumn::make('detailKrs.jadwalKuliah.dosen.nama')
                    ->label('Dosen Pengampu')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('total_sks')
                    ->label('Total SKS')
                    ->alignCenter(),

                Tables\Columns\SelectColumn::make('status_krs')
                    ->label('Status KRS')
                    ->options([
                        'draft'     => 'Draft',
                        'submitted' => 'Diajukan',
                        'approved'  => 'Disetujui',
                        'rejected'  => 'Ditolak',
                    ])
                    ->disabled(fn () => auth()->user()?->hasRole('mahasiswa')),
            ])
            ->recordActions([
                EditAction::make(),
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

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if (! $user) {
            return $query->whereRaw('1 = 0');
        }

        if ($user->hasRole('mahasiswa')) {
            return $query->whereHas('mahasiswa', fn ($q) => $q->where('id_user', $user->id));
        }

        if ($user->hasRole('dosen')) {
            return $query->whereHas('detailKrs.jadwalKuliah.dosen', fn ($q) => $q->where('id_user', $user->id));
        }

        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListKrs::route('/'),
            'create' => Pages\CreateKrs::route('/create'),
            'edit'   => Pages\EditKrs::route('/{record}/edit'),
        ];
    }
}
