<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpecialDateResource\Pages;
use App\Filament\Resources\SpecialDateResource\RelationManagers;
use App\Models\Facility;
use App\Models\SpecialDate;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;

class SpecialDateResource extends Resource
{
    protected static ?string $model = SpecialDate::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Jadwal Khusus';

    protected static ?string $pluralModelLabel = 'Jadwal Khusus';

    protected static ?string $navigationGroup = 'Fasilitas';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Pengaturan Jadwal Khusus')
                    ->description('Tentukan tanggal libur, maintenance, atau hari dengan jam operasional khusus.')
                    ->schema([
                        Forms\Components\Select::make('facility_id')
                            ->relationship('facility', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->label('Pilih Fasilitas'),

                        Forms\Components\DatePicker::make('date')
                            ->label('Tanggal Khusus')
                            ->required()
                            ->native(false)
                            ->displayFormat('d F Y')
                            ->minDate(now()->addDay())

                            ->disabled(fn (Get $get): bool => !$get('facility_id'))
                            
                            ->placeholder('Pilih fasilitas terlebih dahulu')

                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Set $set) {
                                $set('special_opening_time', null);
                                $set('special_closing_time', null);
                            })
                            ->disabledDates(function (Get $get) {
                                $facilityId = $get('facility_id');
                                if (!$facilityId) {
                                    return [];
                                }

                                return cache()->remember("disabled_dates_{$facilityId}", now()->addHour(), function () use ($facilityId) {
                                    /** @var \App\Models\Facility|null $facility */
                                    $facility = Facility::find($facilityId);
                                    $availableDays = $facility->available_days ?? [];

                                    $disabled = [];
                                    $startDate = now()->addDay();
                                    $endDate = now()->addYear();

                                    for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                                        if (!in_array($date->dayOfWeekIso, $availableDays)) {
                                            $disabled[] = $date->format('Y-m-d');
                                        }
                                    }
                                    return $disabled;
                                });
                            }),

                        Forms\Components\Textarea::make('reason')
                            ->label('Alasan/Keterangan')
                            ->helperText('Contoh: Libur Nasional, Perbaikan, Acara Internal.')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_closed')
                            ->label('Tutup Fasilitas Pada Tanggal Ini')
                            ->helperText('Jika diaktifkan, fasilitas tidak dapat dibooking sama sekali pada tanggal ini.')
                            ->live()
                            ->columnSpanFull(),

                        Forms\Components\Select::make('special_opening_time')
                            ->label('Jam Buka Khusus')
                            ->options(self::generateTimeOptions())
                            ->native(false)
                            ->live()
                            ->hidden(fn (Get $get): bool => $get('is_closed'))
                            ->required(fn (Get $get): bool => !$get('is_closed'))
                            ->helperText('Atur jika ada jam buka yang berbeda dari biasanya.'),

                        Forms\Components\Select::make('special_closing_time')
                            ->label('Jam Tutup Khusus')
                            ->options(self::generateTimeOptions())
                            ->native(false)
                            ->hidden(fn (Get $get): bool => $get('is_closed'))
                            ->required(fn (Get $get): bool => !$get('is_closed'))
                            ->helperText('Atur jika ada jam tutup yang berbeda dari biasanya.')
                            // --- VALIDASI BARU ---
                            ->rule(function (Get $get): Closure {
                                return function (string $attribute, $value, Closure $fail) use ($get) {
                                    $facilityId = $get('facility_id');
                                    $openingTime = $get('special_opening_time');

                                    if (!$facilityId || !$openingTime || !$value) return;

                                    $start = Carbon::parse($openingTime);
                                    $end = Carbon::parse($value);

                                    if ($end->lte($start)) {
                                        $fail('Jam tutup khusus harus setelah jam buka khusus.');
                                        return;
                                    }

                                    /** @var Facility $facility */
                                    $facility = Facility::find($facilityId);
                                    $minDuration = $facility->max_booking_hours;
                                    $actualDuration = $start->diffInHours($end);

                                    if ($actualDuration < $minDuration) {
                                        $fail("Total jam operasional khusus ({$actualDuration} jam) harus lebih besar atau sama dengan durasi pinjam maksimal fasilitas ({$minDuration} jam).");
                                    }
                                };
                            }),

                    ])->columns(2),
            ]);
    }

    protected static function generateTimeOptions(): array
    {
        $options = [];
        $time = Carbon::now()->startOfDay();
        for ($i = 0; $i < 24; $i++) {
            $options[$time->format('H:i:s')] = $time->format('H:00');
            $time->addHour();
        }
        return $options;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('facility.name')
                    ->label('Fasilitas')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d F Y')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_closed')
                    ->label('Status Tutup')
                    ->boolean(),
                Tables\Columns\TextColumn::make('special_opening_time')
                    ->label('Buka Khusus')
                    ->time('H:i')
                    ->placeholder('N/A'),
                Tables\Columns\TextColumn::make('special_closing_time')
                    ->label('Tutup Khusus')
                    ->time('H:i')
                    ->placeholder('N/A'),
                Tables\Columns\TextColumn::make('reason')
                    ->label('Alasan')
                    ->limit(40),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListSpecialDates::route('/'),
            'create' => Pages\CreateSpecialDate::route('/create'),
            'edit' => Pages\EditSpecialDate::route('/{record}/edit'),
        ];
    }
}
