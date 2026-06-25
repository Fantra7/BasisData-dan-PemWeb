<?php

namespace App\Filament\Resources\JadwalKuliahs;

use App\Filament\Resources\JadwalKuliahs\Pages;
use App\Models\JadwalKuliah;
use Filament\Forms;
use Filament\Schemas\Schema; // Schema sesuai versi Filament v4
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

// Action classes Filament v4 -> namespace terpusat di Filament\Actions
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

use Illuminate\Database\Eloquent\Builder;

class JadwalKuliahResource extends Resource
{
    protected static ?string $model = JadwalKuliah::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Jadwal Kuliah';
    protected static string | \UnitEnum | null $navigationGroup = 'Perkuliahan';
    protected static ?int $navigationSort = 1;
    protected static ?string $pluralModelLabel = 'Jadwal Kuliah';
    protected static ?string $modelLabel = 'Jadwal Kuliah';

    public static function form(Schema $schema): Schema // Schema sesuai induk kelas v4
    {
        return $schema
            ->components([ // components(), bukan schema()
                Forms\Components\TextInput::make('kode_kelas')
                    ->required()
                    ->maxLength(10),

                Forms\Components\Select::make('hari')
                    ->options([
                        'Senin'  => 'Senin',
                        'Selasa' => 'Selasa',
                        'Rabu'   => 'Rabu',
                        'Kamis'  => 'Kamis',
                        'Jumat'  => 'Jumat',
                        'Sabtu'  => 'Sabtu',
                    ])
                    ->required(),

                Forms\Components\TimePicker::make('jam_mulai')
                    ->required(),

                Forms\Components\TimePicker::make('jam_selesai')
                    ->required(),

                Forms\Components\TextInput::make('ruang')
                    ->required()
                    ->maxLength(20),

                Forms\Components\TextInput::make('kuota')
                    ->numeric()
                    ->default(40),

                Forms\Components\Select::make('id_mk')
                    ->relationship('mataKuliah', 'nama_mk') // nama mata kuliah
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Mata Kuliah'),

                Forms\Components\Select::make('id_dosen')
                    ->relationship('dosen', 'nama') // nama dosen
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Dosen Pengajar'),

                Forms\Components\Select::make('id_ta')
                    ->relationship('tahunAkademik', 'tahun') // tahun akademik
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Tahun Akademik'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_kelas')
                    ->label('Kelas')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('mataKuliah.nama_mk')
                    ->label('Mata Kuliah')
                    ->searchable(),

                Tables\Columns\TextColumn::make('dosen.nama')
                    ->label('Dosen')
                    ->searchable(),

                Tables\Columns\TextColumn::make('hari')
                    ->sortable(),

                Tables\Columns\TextColumn::make('jam_mulai')
                    ->time('H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('jam_selesai')
                    ->time('H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('ruang')
                    ->searchable(),

                Tables\Columns\TextColumn::make('kuota')
                    ->alignCenter(),
            ])
            ->filters([
                //
            ])
            ->recordActions([ // dulu ->actions([])
                EditAction::make(),
                DeleteAction::make()
    ->before(function (DeleteAction $action, $record) {
        if ($record->detailKrs()->exists()) {
            \Filament\Notifications\Notification::make()
                ->title('Data tidak dapat dihapus')
                ->body('Jadwal ini sudah dipilih mahasiswa di KRS. Hapus pilihan KRS terkait terlebih dahulu.')
                ->danger()
                ->send();

            $action->halt();
        }
    }),
            ])
            ->toolbarActions([ // dulu ->bulkActions([])
                BulkActionGroup::make([
                    DeleteBulkAction::make()
    ->before(function (DeleteBulkAction $action, \Illuminate\Support\Collection $records) {
        $terpakai = $records->filter(fn ($r) => $r->detailKrs()->exists());

        if ($terpakai->isNotEmpty()) {
            \Filament\Notifications\Notification::make()
                ->title('Sebagian data tidak dapat dihapus')
                ->body('Jadwal ini sudah dipilih mahasiswa di KRS. Hapus pilihan KRS terkait terlebih dahulu.')
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

        // Jika login sebagai dosen, cari ID dosen berdasarkan id_user-nya
        if (auth()->user()?->hasRole('dosen')) {
            $dosenId = \App\Models\Dosen::where('id_user', auth()->id())->value('id');
            return $query->where('id_dosen', $dosenId);
        }

        // Admin/mahasiswa melihat seluruh jadwal
        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListJadwalKuliahs::route('/'),
            'create' => Pages\CreateJadwalKuliah::route('/create'),
            'edit'   => Pages\EditJadwalKuliah::route('/{record}/edit'),
        ];
    }
}
