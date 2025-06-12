<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Filament\Resources\BookingResource\RelationManagers;
use App\Models\Booking;
use App\Models\Facility;
use App\Models\SpecialDate;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationLabel = 'Pemesanan';

    protected static ?string $pluralModelLabel = 'Pemesanan';

    protected static ?string $navigationGroup = 'Pemesanan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Pemesanan')
                    ->description('Pilih pengguna, fasilitas, dan jadwal yang diinginkan.')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name', fn (Builder $query) => $query->where('role', 'masyarakat')) // [cite: 1]
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Nama Pemesan'),

                        Forms\Components\Select::make('facility_id')
                            // BARU: Hanya menampilkan fasilitas yang aktif
                            ->relationship('facility', 'name', fn (Builder $query) => $query->where('is_active', true)) // [cite: 5]
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required()
                            ->label('Fasilitas yang Dipesan'),

                        Forms\Components\DatePicker::make('booking_date')
                            ->label('Tanggal Pemesanan')
                            ->native(false)
                            ->live()
                            ->required()
                            ->minDate(now()->addDay()) // <-- BARU: Tanggal minimal adalah besok
                            ->helperText('Pemesanan hanya dapat dilakukan paling cepat untuk H-1 (besok).')
                            ->rule(function (\Filament\Forms\Get $get): Closure {
                                return function (string $attribute, $value, Closure $fail) use ($get) {
                                    $facilityId = $get('facility_id');
                                    if (!$facilityId) return;

                                    /** @var Facility $facility */
                                    $facility = Facility::find($facilityId);
                                    $date = Carbon::parse($value);

                                    $specialDate = SpecialDate::where('facility_id', $facilityId)->where('date', $date->format('Y-m-d'))->first();
                                    if ($specialDate && $specialDate->is_closed) {
                                        $fail('Fasilitas tutup pada tanggal yang dipilih. Alasan: ' . ($specialDate->reason ?? 'Hari Libur'));
                                        return;
                                    }

                                    if (!$specialDate && !$facility->isAvailableOnDay($date->dayOfWeekIso)) {
                                        $fail('Fasilitas tidak tersedia pada hari yang dipilih.');
                                    }
                                };
                            }),

                        Forms\Components\TimePicker::make('start_time')
                            ->label('Jam Mulai')
                            ->seconds(false)
                            ->minutesStep(60)
                            ->displayFormat('H:00')
                            ->live()
                            ->required()
                            // ->minDate(...) DIHAPUS dan diganti dengan rule di bawah
                            ->maxDate(function (\Filament\Forms\Get $get) {
                                $facility = Facility::find($get('facility_id'));
                                $specialDate = SpecialDate::where('facility_id', $get('facility_id'))->where('date', $get('booking_date'))->first();
                                return $specialDate?->special_closing_time ?? $facility?->closing_time;
                            })
                            ->rules([ // <-- BARU: Menambahkan rule untuk pesan error kustom
                                function (\Filament\Forms\Get $get): Closure {
                                    return function (string $attribute, $value, Closure $fail) use ($get) {
                                        $facilityId = $get('facility_id');
                                        $bookingDate = $get('booking_date');
                                        if (!$facilityId || !$bookingDate || !$value) return;

                                        $facility = Facility::find($facilityId);
                                        $specialDate = SpecialDate::where('facility_id', $facilityId)->where('date', $bookingDate)->first();
                                        $openingTime = $specialDate?->special_opening_time ?? $facility?->opening_time;

                                        if (!$openingTime) return;

                                        if (Carbon::parse($value)->isBefore(Carbon::parse($openingTime))) {
                                            $fail("Jam mulai tidak boleh lebih awal dari jam buka fasilitas (" . Carbon::parse($openingTime)->format('H:i') . ").");
                                        }
                                    };
                                }
                            ]),

                        Forms\Components\TimePicker::make('end_time')
                            ->label('Jam Selesai')
                            ->seconds(false)
                            ->minutesStep(60)
                            ->displayFormat('H:00')
                            ->required()
                            ->minDate(fn (\Filament\Forms\Get $get) => $get('start_time'))
                            // Gabungkan semua rules dalam satu array
                            ->rules([
                                // Rule 1: Validasi agar tidak bentrok
                                function (\Filament\Forms\Get $get, $record): Closure {
                                    return function (string $attribute, $value, Closure $fail) use ($get, $record) {
                                        $startTime = $get('start_time');
                                        if (!$startTime || !$value) return;

                                        $query = Booking::where('facility_id', $get('facility_id'))
                                            ->where('booking_date', $get('booking_date'))
                                            ->whereIn('status', ['approved', 'pending'])
                                            ->where(fn(Builder $q) => $q->where('start_time', '<', $value)->where('end_time', '>', $startTime));

                                        if ($record) $query->where('id', '!=', $record->id);
                                        if ($query->exists()) $fail('Jadwal pada jam ini sudah dipesan atau sedang menunggu persetujuan.');
                                    };
                                },
                                // Rule 2: Validasi durasi maksimal dan jam tutup (PENGGANTI maxDate)
                                function (\Filament\Forms\Get $get): Closure {
                                    return function (string $attribute, $value, Closure $fail) use ($get) {
                                        $facility = Facility::find($get('facility_id'));
                                        $startTimeValue = $get('start_time');
                                        if (!$facility || !$startTimeValue || !$value) return;

                                        // Hitung batas waktu sebenarnya
                                        $startTime = Carbon::parse($startTimeValue);
                                        $maxBookingTime = $startTime->copy()->addHours($facility->max_booking_hours); // [cite: 5]

                                        $specialDate = SpecialDate::where('facility_id', $facility->id)->where('date', $get('booking_date'))->first(); // [cite: 6]
                                        $closingTime = Carbon::parse($specialDate?->special_closing_time ?? $facility->closing_time); // [cite: 5, 6]

                                        $effectiveMaxTime = $maxBookingTime->lessThan($closingTime) ? $maxBookingTime : $closingTime;
                                        $userEndTime = Carbon::parse($value);

                                        if ($userEndTime->isAfter($effectiveMaxTime)) {
                                            // Cek alasan kenapa GAGAL dan berikan pesan yang sesuai
                                            if ($effectiveMaxTime->equalTo($maxBookingTime)) {
                                                // Gagal karena melebihi durasi
                                                $fail("Durasi pemesanan tidak boleh melebihi batas maksimal {$facility->max_booking_hours} jam."); // [cite: 5]
                                            } else {
                                                // Gagal karena melewati jam tutup
                                                $fail("Jam selesai tidak boleh melebihi jam tutup fasilitas ({$closingTime->format('H:i')})."); // [cite: 5]
                                            }
                                        }
                                    };
                                }
                            ]),
                    ])->columns(2),

                Forms\Components\Section::make('Informasi Tambahan')
                    ->schema([
                         Forms\Components\Textarea::make('purpose') // [cite: 8]
                            ->label('Tujuan Peminjaman')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('status') // [cite: 9]
                             ->options([
                                 'pending' => 'Pending',
                                 'approved' => 'Approved',
                                 'rejected' => 'Rejected',
                                 'completed' => 'Completed',
                             ])
                            ->default('pending')
                            ->live()
                            ->required(),
                        Forms\Components\Textarea::make('rejection_reason') // [cite: 9]
                             ->label('Alasan Penolakan')
                             ->helperText('Isi jika status pemesanan ditolak.')
                             ->visible(fn (\Filament\Forms\Get $get): bool => $get('status') === 'rejected'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('facility.name')->label('Fasilitas')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label('Pemesan')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('booking_date')->date('d M Y')->sortable(),
                Tables\Columns\TextColumn::make('start_time')->time('H:i'),
                Tables\Columns\TextColumn::make('end_time')->time('H:i'),
                Tables\Columns\SelectColumn::make('status') // 
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'completed' => 'Completed',
                    ])->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'completed' => 'Completed',
                    ]),
                Tables\Filters\SelectFilter::make('facility')->relationship('facility', 'name'),
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
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
