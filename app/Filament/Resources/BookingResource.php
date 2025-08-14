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
use Filament\Forms\Set;
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
                            ->relationship('user', 'name', fn (Builder $query) => $query->where('role', 'masyarakat'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Nama Pemesan'),

                        Forms\Components\Select::make('facility_id')
                            ->relationship('facility', 'name', fn (Builder $query) => $query->where('is_active', true))
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required()
                            ->label('Fasilitas yang Dipesan'),

                        Forms\Components\DatePicker::make('booking_date')
                            ->label('Tanggal Pemesanan')
                            ->native(false)
                            ->required()
                            ->live(onBlur: true)
                            ->minDate(now()->addDay())
                            ->helperText('Pemesanan hanya dapat dilakukan paling cepat untuk H-1 (besok).')
                            
                            ->disabled(fn (Forms\Get $get): bool => !$get('facility_id'))
                            ->placeholder('Pilih fasilitas terlebih dahulu')

                            ->disabledDates(function (Forms\Get $get) {
                                $facilityId = $get('facility_id');
                                if (!$facilityId) {
                                    return array_map(fn ($i) => now()->addDays($i)->format('Y-m-d'), range(1, 365));
                                }

                                return cache()->remember("booking_disabled_dates_{$facilityId}", now()->addHour(), function () use ($facilityId) {
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
                            })

                            ->rule(function (Forms\Get $get): Closure {
                                return function (string $attribute, $value, Closure $fail) use ($get) {
                                    $facilityId = $get('facility_id');
                                    if (!$facilityId || !$value) return;

                                    /** @var \App\Models\Facility $facility */
                                    $facility = Facility::find($facilityId);
                                    $date = Carbon::parse($value);

                                    $specialDate = SpecialDate::where('facility_id', $facilityId)->where('date', $date->format('Y-m-d'))->first();
                                    if ($specialDate && $specialDate->is_closed) {
                                        $fail('Fasilitas tutup pada tanggal yang dipilih karena ada Jadwal Khusus. Alasan: ' . ($specialDate->reason ?? 'Hari Libur'));
                                        return;
                                    }
                                    
                                    if (!$specialDate && !$facility->isAvailableOnDay($date->dayOfWeekIso)) {
                                        $fail('Fasilitas tidak tersedia pada hari yang dipilih.');
                                    }
                                };
                            }),

                        Forms\Components\Select::make('start_time')
                            ->label('Jam Mulai')
                            ->live()
                            ->required()
                            ->native(false)
                            ->placeholder('Pilih jam mulai')
                            ->helperText('Jam hanya bisa dipilih setelah memilih tanggal')
                            ->options(function (Forms\Get $get) {
                                $facilityId = $get('facility_id');
                                $bookingDate = $get('booking_date');
                                if (!$facilityId || !$bookingDate) {
                                    return [];
                                }

                                $facility = Facility::find($facilityId);
                                $specialDate = SpecialDate::where('facility_id', $facilityId)->where('date', $bookingDate)->first();
                                
                                $openingTime = Carbon::parse($specialDate?->special_opening_time ?? $facility->opening_time);
                                $closingTime = Carbon::parse($specialDate?->special_closing_time ?? $facility->closing_time)->subHour();

                                $options = [];
                                for ($time = $openingTime; $time->lte($closingTime); $time->addHour()) {
                                    $options[$time->format('H:i:s')] = $time->format('H:00');
                                }
                                return $options;
                            }),

                        Forms\Components\Select::make('duration_in_hours')
                            ->label('Durasi Pemesanan')
                            ->required()
                            ->live()
                            ->native(false)
                            ->placeholder('Pilih durasi')
                            ->helperText('Durasi hanya bisa dipilih setelah memilih jam mulai')
                            ->options(function (Forms\Get $get) {
                                $facilityId = $get('facility_id');
                                $bookingDate = $get('booking_date');
                                $startTimeStr = $get('start_time');

                                if (!$facilityId || !$bookingDate || !$startTimeStr) {
                                    return [];
                                }

                                /** @var Facility $facility */
                                $facility = Facility::find($facilityId);
                                $specialDate = SpecialDate::where('facility_id', $facilityId)->where('date', $bookingDate)->first();
                                
                                $startTime = Carbon::parse($startTimeStr);
                                $closingTime = Carbon::parse($specialDate?->special_closing_time ?? $facility->closing_time);
                                $maxHours = $facility->max_booking_hours;

                                $options = [];
                                for ($hour = 1; $hour <= $maxHours; $hour++) {
                                    $endTime = $startTime->copy()->addHours($hour);
                                    if ($endTime->isAfter($closingTime)) {
                                        break; 
                                    }
                                    $options[$hour] = "$hour jam";
                                }

                                return $options;
                            })
                            ->afterStateUpdated(function (Set $set, Forms\Get $get, $state) {
                                $startTimeStr = $get('start_time');
                                if ($startTimeStr && $state) {
                                    $endTime = Carbon::parse($startTimeStr)->addHours((int)$state)->format('H:i:s'); 
                                    $set('end_time', $endTime);
                                }
                            })
                            ->rule(function (Forms\Get $get, $record): Closure {
                                return function (string $attribute, $value, Closure $fail) use ($get, $record) {
                                    $startTime = $get('start_time');
                                    $endTime = $startTime ? Carbon::parse($startTime)->addHours((int)$value)->format('H:i:s') : null;

                                    if (!$startTime || !$endTime) return;

                                    $query = Booking::where('facility_id', $get('facility_id'))
                                        ->where('booking_date', $get('booking_date'))
                                        ->whereIn('status', ['approved', 'pending'])
                                        ->where(fn(Builder $q) => $q->where('start_time', '<', $endTime)->where('end_time', '>', $startTime));

                                    if ($record) {
                                        $query->where('id', '!=', $record->id);
                                    }
                                    
                                    if ($query->exists()) {
                                        $fail('Jadwal pada rentang waktu ini sudah dipesan atau sedang menunggu persetujuan.');
                                    }
                                };
                            }),
                        
                        Forms\Components\Hidden::make('end_time')->required(),

                    ])->columns(2),

                Forms\Components\Section::make('Informasi Tambahan')
                    ->schema([
                        Forms\Components\Textarea::make('purpose')
                            ->label('Tujuan Peminjaman')
                            ->required()
                            ->columnSpanFull()
                            ->minLength(10)
                            ->maxLength(255)
                            ->validationMessages([
                                'minLength' => 'Silakan perpanjang teks ini menjadi :min karakter atau lebih (Anda saat ini menggunakan :value karakter).',
                                'maxLength' => 'Teks melebihi batas maksimal :max karakter.',
                                'required' => 'Kolom ini wajib diisi.',
                            ]),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                                'completed' => 'Completed',
                            ])
                            ->default('pending')
                            ->live()
                            ->required(),
                        Forms\Components\Textarea::make('rejection_reason')
                            ->label('Alasan Penolakan')
                            ->helperText('Isi jika status pemesanan ditolak.')
                            ->visible(fn (Forms\Get $get): bool => $get('status') === 'rejected'),
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
                Tables\Columns\SelectColumn::make('status')
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
