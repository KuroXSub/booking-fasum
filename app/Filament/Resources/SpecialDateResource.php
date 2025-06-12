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
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                        Forms\Components\Select::make('facility_id') // 
                            ->relationship('facility', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->label('Pilih Fasilitas'),

                        Forms\Components\DatePicker::make('date') // 
                            ->label('Tanggal Khusus')
                            ->required()
                            ->native(false)
                            ->displayFormat('d F Y')
                            ->rules([
                                fn (Get $get): Closure => 
                                    function (string $attribute, $value, Closure $fail) use ($get) {
                                        $facilityId = $get('facility_id');
                                        if (!$facilityId) {
                                            return;
                                        }

                                        /** @var Facility|null $facility */
                                        $facility = Facility::find($facilityId);
                                        if (!$facility || !$facility->available_days) {
                                            return;
                                        }
                                        
                                        $selectedDayOfWeek = date('N', strtotime($value));

                                        if (!in_array($selectedDayOfWeek, $facility->available_days)) {
                                            $fail('Tanggal yang dipilih tidak sesuai dengan hari operasional fasilitas.');
                                        }
                                    },
                            ]),

                        Forms\Components\Textarea::make('reason') // 
                            ->label('Alasan/Keterangan')
                            ->helperText('Contoh: Libur Nasional, Perbaikan, Acara Internal.')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_closed') // 
                            ->label('Tutup Fasilitas Pada Tanggal Ini')
                            ->helperText('Jika diaktifkan, fasilitas tidak dapat dibooking sama sekali pada tanggal ini.')
                            ->reactive() // Membuat form interaktif
                            ->columnSpanFull(),

                        // Field jam khusus ini hanya akan muncul jika 'is_closed' TIDAK aktif
                        Forms\Components\TimePicker::make('special_opening_time') // 
                            ->label('Jam Buka Khusus')
                            ->seconds(false)
                            ->displayFormat('H:i')
                            ->hidden(fn (Get $get): bool => $get('is_closed')) // Sembunyikan jika is_closed = true
                            ->helperText('Atur jika ada jam buka yang berbeda dari biasanya.'),

                        Forms\Components\TimePicker::make('special_closing_time') // [cite: 6]
                            ->label('Jam Tutup Khusus')
                            ->seconds(false)
                            ->displayFormat('H:i')
                            ->hidden(fn (Get $get): bool => $get('is_closed')) // Sembunyikan jika is_closed = true
                            ->helperText('Atur jika ada jam tutup yang berbeda dari biasanya.'),

                    ])->columns(2),
            ]);
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
