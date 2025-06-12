<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Masyarakat';

    protected static ?string $pluralModelLabel = 'Masyarakat';

    protected static ?string $navigationGroup = 'Data Master';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('role', 'masyarakat'); // 
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Personal')
                    ->description('Data diri dan kontak pengguna.')
                    ->schema([
                        Forms\Components\TextInput::make('name') // 
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email') // 
                            ->label('Alamat Email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('phone') // 
                            ->label('Nomor Telepon')
                            ->tel()
                            ->helperText('Contoh: 081234567890'),
                    ])->columns(2),

                Forms\Components\Section::make('Keamanan & Akses')
                    ->description('Pengaturan untuk login dan hak akses pengguna.')
                    ->schema([
                        Forms\Components\TextInput::make('password') // 
                            ->label('Password Baru')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->helperText('Wajib diisi saat membuat user baru. Kosongkan jika tidak ingin mengubah password saat mengedit.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(), // 
                Tables\Columns\TextColumn::make('name') // 
                    ->label('Nama')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email') // 
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone') // 
                    ->label('No. Telepon')
                    ->searchable()
                    ->placeholder('Tidak ada'),
                Tables\Columns\TextColumn::make('created_at') // 
                    ->label('Tanggal Daftar')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
