<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FacilityResource\Pages;
use App\Filament\Resources\FacilityResource\RelationManagers;
use App\Models\Facility;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;

class FacilityResource extends Resource
{
    protected static ?string $model = Facility::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationLabel = 'Fasilitas';

    protected static ?string $pluralModelLabel = 'Fasilitas';

    protected static ?string $navigationGroup = 'Fasilitas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Utama')
                    ->description('Detail dasar mengenai fasilitas yang akan didaftarkan.')
                    ->schema([
                        Forms\Components\Select::make('category_id') //
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Kategori Fasilitas'),
                        Forms\Components\TextInput::make('name') //
                            ->label('Nama Fasilitas')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('image') //
                            ->label('Gambar Fasilitas')
                            ->image()
                            ->directory('facilities')
                            ->helperText('Unggah gambar representatif untuk fasilitas ini.'),
                        Forms\Components\Textarea::make('description') //
                            ->label('Deskripsi')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Jadwal & Ketersediaan')
                    ->description('Atur hari dan jam operasional standar untuk fasilitas ini.')
                    ->schema([
                        Forms\Components\CheckboxList::make('available_days') //
                            ->label('Hari Tersedia')
                            ->options([
                                '1' => 'Senin',
                                '2' => 'Selasa',
                                '3' => 'Rabu',
                                '4' => 'Kamis',
                                '5' => 'Jumat',
                                '6' => 'Sabtu',
                                '7' => 'Minggu',
                            ])
                            ->columns(4)
                            ->required()
                            ->helperText('Pilih hari-hari di mana fasilitas ini dapat dipinjam.'),
                        Forms\Components\TimePicker::make('opening_time') //
                            ->label('Jam Buka')
                            ->seconds(false)
                            ->live()
                            ->required(),
                        Forms\Components\TimePicker::make('closing_time') //
                            ->label('Jam Tutup')
                            ->seconds(false)
                            ->required()
                            ->live()
                            ->minDate(function (Forms\Get $get) {
                                $startDate = $get('opening_time');
                                return $startDate ? Carbon::parse($startDate) : now();
                            })
                            ->rules([
                                function (Forms\Get $get) {
                                    return function (string $attribute, $value, \Closure $fail) use ($get) {
                                        $startDate = $get('opening_time');
                                        if ($startDate && $value && Carbon::parse($value)->lt(Carbon::parse($startDate))) {
                                            $fail("Waktu berakhir harus setelah waktu mulai.");
                                        }
                                    };
                                },
                            ]),
                    ])->columns(2),

                Forms\Components\Section::make('Aturan & Pengaturan Lanjutan')
                    ->description('Konfigurasi spesifik untuk peminjaman dan status fasilitas.')
                    ->schema([
                        Forms\Components\TextInput::make('max_booking_hours')
                            ->label('Maksimal Durasi Peminjaman (Jam)')
                            ->numeric()
                            ->required()
                            ->minValue(1),
                        Forms\Components\Toggle::make('is_active') //
                            ->label('Status Aktif')
                            ->inline(false)
                            ->helperText('Jika nonaktif, fasilitas tidak akan muncul di halaman peminjaman user.')
                            ->default(false),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Gambar'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Fasilitas')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name') //
                    ->label('Kategori')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active') //
                    ->label('Status Aktif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('opening_time') //
                    ->label('Jam Buka')
                    ->time('H:i'),
                Tables\Columns\TextColumn::make('closing_time') //
                    ->label('Jam Tutup')
                    ->time('H:i'),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->label('Filter Kategori'),
                TernaryFilter::make('is_active')
                    ->label('Filter Status Aktif')
                    ->trueLabel('Aktif')
                    ->falseLabel('Nonaktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFacilities::route('/'),
            'create' => Pages\CreateFacility::route('/create'),
            'edit' => Pages\EditFacility::route('/{record}/edit'),
        ];
    }
}
