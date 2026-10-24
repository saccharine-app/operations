<?php

namespace Saccharine\Operations\Filament\Resources;

use Saccharine\Operations\Filament\Resources\StaffMemberResource\Pages;
use Saccharine\Operations\Filament\Resources\StaffMemberResource\RelationManagers;
use Saccharine\Operations\Models\StaffMember;
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

class StaffMemberResource extends Resource
{
    protected static ?string $model = StaffMember::class;

    protected static \UnitEnum|string|null $navigationGroup = 'Operations';
    protected static ?int $navigationSort = 2;

    // An ID badge or user group icon
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-identification';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Personnel Details')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->required()
                            ->maxLength(255),
                            
                        Forms\Components\TextInput::make('last_name')
                            ->required()
                            ->maxLength(255),
                            
                        Forms\Components\TextInput::make('professional_title')
                            ->placeholder('e.g., Administrative Assistant')
                            ->maxLength(255),
                            
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('Linked User Account')
                            ->searchable()
                            ->preload()
                            ->helperText('Optional: Link this staff profile to a system login.'),
                            
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active Employee')
                            ->default(true)
                            ->inline(false),
                    ])->columns(2)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('last_name')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('professional_title')
                    ->searchable(),
                    
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean() // Automatically renders a green check or red X
                    ->sortable(),
            ])
            ->filters([
                // Adds a quick filter to view only inactive former employees
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Employment Status')
                    ->trueLabel('Active Staff')
                    ->falseLabel('Former Staff'),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => Pages\ListStaffMembers::route('/'),
            'create' => Pages\CreateStaffMember::route('/create'),
            'edit' => Pages\EditStaffMember::route('/{record}/edit'),
        ];
    }
}
