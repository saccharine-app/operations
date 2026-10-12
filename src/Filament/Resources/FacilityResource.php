<?php

namespace Saccharine\Operations\Filament\Resources;

use Saccharine\Operations\Filament\Resources\FacilityResource\Pages;
use Saccharine\Operations\Filament\Resources\FacilityResource\RelationManagers;
use Saccharine\Operations\Models\Facility;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FacilityResource extends Resource
{
    protected static ?string $model = Facility::class;

    protected static \UnitEnum|string|null $navigationGroup = 'Operations';
    protected static ?int $navigationSort = 1;

    // A building icon for physical locations
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-building-office';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Facility Overview')
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->options([
                                'other' => 'Other',
                            ])
                            ->required()
                            ->searchable(),
                            
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                            
                        Forms\Components\Select::make('parent_facility_id')
                            ->relationship('parent', 'name')
                            ->label('Parent Facility')
                            ->searchable()
                            ->preload()
                            ->helperText('If this facility is located inside or owned by another listed facility.'),
                            
                        Forms\Components\TextInput::make('license_number')
                            ->maxLength(255),
                    ])->columns(2),

                Section::make('Location & Contact')
                    ->schema([
                        Forms\Components\TextInput::make('address_street')->columnSpanFull(),
                        Forms\Components\TextInput::make('address_city'),
                        Forms\Components\TextInput::make('address_state_province')->label('Province/State'),
                        Forms\Components\TextInput::make('address_country')->default('Canada'),
                        Forms\Components\TextInput::make('address_postal_code'),
                        Forms\Components\TextInput::make('phone_number')->tel(),
                    ])->columns(3),

                Section::make('Extended Operations Data')
                    ->description('Custom attributes like gate codes, hours, or specific procedural flags.')
                    ->schema([
                        Forms\Components\KeyValue::make('extended_meta')
                            ->keyLabel('Attribute Name')
                            ->valueLabel('Value')
                            ->addActionLabel('Add Custom Field')
                            ->columnSpanFull(),
                    ])->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Facility $record): string => $record->parent ? '↳ Inside ' . $record->parent->name : ''),
                    
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('address_city')
                    ->label('City')
                    ->searchable(),
                    
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type'),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name', 'asc');
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
