<?php

namespace Saccharine\Operations\Filament\Resources;

use Saccharine\Operations\Filament\Resources\CaseFileResource\Pages;
use Saccharine\Operations\Filament\Resources\CaseFileResource\RelationManagers;
use Saccharine\Operations\Models\CaseFile;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
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

class CaseFileResource extends Resource
{
    protected static ?string $model = CaseFile::class;

    protected static \UnitEnum|string|null $navigationGroup = 'Operations';
    protected static ?int $navigationSort = 1;

    // A clipboard or folder icon represents the dossier
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Profile Status')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'lead' => 'Lead (Inquiry)',
                                'active' => 'Active (Active Case)',
                                'closed' => 'Closed (Complete)',
                                'lost' => 'Lost to Competitor',
                            ])
                            ->default('lead')
                            ->required()
                            ->native(false),
                    ]),

                Section::make('Assignments & Logistics')
                    ->schema([                            
                        Forms\Components\Select::make('assigned_staff_id')
                            ->relationship('assignedStaff', 'id')
                            // Tell Filament how to display the virtual full_name attribute
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->full_name)
                            ->label('Assigned Staff')
                            ->searchable()
                            ->preload(),
                    ]),

                Tabs::make('Case Details')
                    ->tabs([
                        Tabs\Tab::make('Vital Statistics')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                // The Subject Profile nested relationship form
                                Fieldset::make('Subject Profile')
                                    ->relationship('subjectProfiles')
                                    ->schema([
                                        Forms\Components\TextInput::make('first_name')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('middle_name')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('last_name')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('last_name_at_birth')
                                            ->label('Last Name at Birth')
                                            ->maxLength(255),
                                        
                                        Forms\Components\DatePicker::make('date_of_birth'),
                                        Forms\Components\TextInput::make('place_of_birth'),
                                        
                                        Forms\Components\TextInput::make('ssn_sin')
                                            ->label('SSN / SIN'),
                                        Forms\Components\Toggle::make('ssn_sin_verified')
                                            ->inline(false),
                                            
                                        Forms\Components\Select::make('marital_status')
                                            ->options([
                                                'Never Married' => 'Never Married',
                                                'Single' => 'Single',
                                                'Married' => 'Married',
                                                'Widowed' => 'Widowed',
                                                'Common-Law' => 'Common-Law',
                                                'Same-sex Partner' => 'Same-sex Partner',
                                            ])->native(false),
                                            
                                        Forms\Components\TextInput::make('sex'),
                                        Forms\Components\TextInput::make('occupation'),
                                        Forms\Components\TextInput::make('industry'),
                                    ])
                                    ->columns(2), // Makes the fields sit side-by-side inside the fieldset
                                    
                            ]),

                        Tabs\Tab::make('Case Participants')
                            ->icon('heroicon-o-shield-exclamation')
                            ->badge(fn ($record) => $record?->caseContacts()?->count() ?: null)
                            ->schema([
                                Forms\Components\Repeater::make('caseContacts')
                                    ->relationship('caseContacts') // This links to the morphMany relationship
                                    ->itemLabel(fn (array $state): ?string => 
                                        !empty($state['first_name']) || !empty($state['last_name'])
                                            ? trim(($state['first_name'] ?? '') . ' ' . ($state['last_name'] ?? '')) . ' (' . ($state['role'] ?? 'Participant') . ')'
                                            : null
                                    )
                                    ->collapsible()
                                    ->collapsed(fn (string $operation): bool => $operation === 'edit')
                                    ->cloneable()
                                    ->schema([
                                        Forms\Components\Select::make('role')
                                            ->label('Designated Role')
                                            ->options([
                                                'primary_contact' => 'Primary Informant / Client',
                                                'executor' => 'Estate Executor',
                                                'next_of_kin' => 'Next of Kin',
                                                'spouse' => 'Spouse / Partner',
                                                'parent' => 'Parent / Guardian',
                                                'physician' => 'Certifying Physician',
                                                'other' => 'Other Representative',
                                            ])
                                            ->required()
                                            ->native(false),

                                        Forms\Components\TextInput::make('relationship_to_subject')
                                            ->label('Relationship')
                                            ->placeholder('e.g., Daughter, Solicitor')
                                            ->maxLength(255),
                                        
                                        Forms\Components\TextInput::make('first_name')
                                            ->label('First Name')
                                            ->required()
                                            ->maxLength(255),
                                        
                                        Forms\Components\TextInput::make('middle_name')
                                            ->label('Middle Name')
                                            ->maxLength(255),

                                        Forms\Components\TextInput::make('last_name')
                                            ->label('Last Name')
                                            ->required()
                                            ->maxLength(255),

                                        Forms\Components\TextInput::make('phone_number')
                                            ->label('Telephone')
                                            ->tel()
                                            ->maxLength(255),

                                        Forms\Components\TextInput::make('email_address')
                                            ->label('Email Address')
                                            ->email()
                                            ->maxLength(255),
                                            
                                        Forms\Components\TextInput::make('address_street'),
                                        Forms\Components\TextInput::make('address_city'),
                                        Forms\Components\TextInput::make('address_state_province'),
                                        Forms\Components\TextInput::make('address_zip_postal_code'),
                                        Forms\Components\Checkbox::make('is_sync_ignored')
                                            ->label('Ignore Sync?')
                                            ->helperText('Check this box to prevent this contact from being synced to the master contact list.'),
                                    ])
                                    ->deleteAction(
                                        fn (Action $action) => $action->requiresConfirmation()
                                            ->modalHeading('Delete this critical entry?')
                                            ->modalDescription('Are you absolutely sure? This data cannot be recovered once saved.')
                                            ->modalSubmitActionLabel('Yes, delete it')
                                    )
                                    ->columns(2)
                                    ->addActionLabel('Add Contact')
                                    ->createItemButtonLabel('Add Participant'),
                            ]),
                    ])
                    ->columnSpanFull() // Ensures the tabs stretch across the whole screen
                    ->persistTabInQueryString(), // Remembers which tab the user was on if they refresh
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Case ID')
                    ->limit(8)
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('fullName')
                    ->label('Full Name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'lead' => 'gray',
                        'active' => 'danger',
                        'closed' => 'success',
                        'lost' => 'gray',
                        default => 'primary',
                    }),
                    
                Tables\Columns\TextColumn::make('assignedStaff.first_name')
                    ->label('Assigned Staff')
                    ->formatStateUsing(fn ($record) => $record->assignedStaff ? $record->assignedStaff->full_name : 'Unassigned')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListCaseFiles::route('/'),
            'create' => Pages\CreateCaseFile::route('/create'),
            'edit' => Pages\EditCaseFile::route('/{record}/edit'),
        ];
    }
}
