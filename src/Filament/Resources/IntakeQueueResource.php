<?php

namespace Saccharine\Operations\Filament\Resources;

use Saccharine\Operations\Filament\Resources\IntakeQueueResource\Pages;
use Saccharine\Operations\Filament\Resources\IntakeQueueResource\RelationManagers;
use Saccharine\Operations\Models\IntakeQueue;
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

class IntakeQueueResource extends Resource
{
    protected static ?string $model = IntakeQueue::class;

    protected static \UnitEnum|string|null $navigationGroup = 'Operations';
    protected static ?int $navigationSort = 2;
    
    // An inbox arrow represents incoming web data
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-inbox-arrow-down';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Inquiry Details')
                    ->description('Unverified data submitted via web or cold-call.')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'promoted' => 'Promoted',
                                'spam' => 'Spam',
                            ])
                            ->default('pending')
                            ->required()
                            ->native(false), // Uses a nicer custom dropdown instead of the browser default
                            
                        Forms\Components\TextInput::make('submitted_name')
                            ->label('Submitted Name')
                            ->maxLength(255),
                            
                        Forms\Components\TextInput::make('contact_phone')
                            ->label('Phone Number')
                            ->tel() // Adds HTML5 tel validation
                            ->maxLength(255),
                            
                        Forms\Components\TextInput::make('contact_email')
                            ->label('Email Address')
                            ->email() // Adds HTML5 email validation
                            ->maxLength(255),
                            
                        Forms\Components\TextInput::make('source_channel')
                            ->label('Source Channel')
                            ->placeholder('e.g., Google Ad, Website Form')
                            ->maxLength(255),
                    ])->columns(2)
                    ->columnSpanFull(), // Puts the inputs into a clean 2-column CSS grid

                Section::make('Raw Webhook Payload')
                    ->description('The unmodified JSON payload from the source.')
                    ->schema([
                        Forms\Components\Textarea::make('raw_payload')
                            ->label('')
                            ->rows(6)
                            ->columnSpanFull(), // Stretches across the entire section
                    ])->collapsed()
                    ->columnSpanFull(), // Keeps the UI clean by hiding the raw JSON by default
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'pending' => 'warning',
                    'promoted' => 'success',
                    'spam' => 'danger',
                    default => 'gray',
                }),
                
            Tables\Columns\TextColumn::make('submitted_name')
                ->searchable()
                ->sortable(),
                
            Tables\Columns\TextColumn::make('contact_phone')
                ->searchable(),
                
            Tables\Columns\TextColumn::make('source_channel')
                ->badge()
                ->color('gray')
                ->toggleable(isToggledHiddenByDefault: false),
                
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('status')
                ->options([
                    'pending' => 'Pending',
                    'promoted' => 'Promoted',
                    'spam' => 'Spam',
                ]),
        ])
        ->actions([
            EditAction::make(),
        ])
        ->bulkActions([
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ])
        ->defaultSort('created_at', 'desc'); // Newest leads at the top
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
            'index' => Pages\ListIntakeQueues::route('/'),
            'create' => Pages\CreateIntakeQueue::route('/create'),
            'edit' => Pages\EditIntakeQueue::route('/{record}/edit'),
        ];
    }
}
