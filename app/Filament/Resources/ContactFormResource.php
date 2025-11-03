<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactFormResource\Pages;
use App\Models\ContactForm;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ContactFormResource extends Resource
{
    protected static ?string $model = ContactForm::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Forms';
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Basic Information')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, callable $set) => 
                            $set('slug', \Illuminate\Support\Str::slug($state))
                        ),
                    
                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),
                    
                    Forms\Components\Textarea::make('description')
                        ->maxLength(65535)
                        ->columnSpanFull(),
                    
                    Forms\Components\Toggle::make('is_active')
                        ->default(true)
                        ->label('Active'),
                ])
                ->columns(2),

            Forms\Components\Section::make('Form Fields')
                ->schema([
                    Forms\Components\Repeater::make('fields')
                        ->schema([
                            Forms\Components\TextInput::make('label')
                                ->required()
                                ->maxLength(255),
                            
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->maxLength(255)
                                ->hint('Use lowercase with underscores (e.g., first_name)'),
                            
                            Forms\Components\Select::make('type')
                                ->options([
                                    'text' => 'Text',
                                    'email' => 'Email',
                                    'textarea' => 'Textarea',
                                    'select' => 'Select',
                                    'checkbox' => 'Checkbox',
                                ])
                                ->required()
                                ->reactive(),
                            
                            Forms\Components\Textarea::make('options')
                                ->hint('For select/checkbox: one option per line')
                                ->visible(fn ($get) => in_array($get('type'), ['select', 'checkbox']))
                                ->rows(3),
                            
                            Forms\Components\Toggle::make('required')
                                ->default(false),
                            
                            Forms\Components\TextInput::make('placeholder')
                                ->maxLength(255),
                        ])
                        ->columns(2)
                        ->defaultItems(1)
                        ->collapsible()
                        ->itemLabel(fn (array $state): ?string => $state['label'] ?? null),
                ])
                ->columnSpanFull(),

            Forms\Components\Section::make('Customization')
                ->schema([
                    Forms\Components\TextInput::make('submit_button_text')
                        ->default('Submit')
                        ->required(),
                    
                    Forms\Components\Textarea::make('success_message')
                        ->default('Thank you! Your submission has been received.')
                        ->required(),
                    
                    Forms\Components\TextInput::make('notification_email')
                        ->email()
                        ->hint('Leave empty to use default email'),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->copyable(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                
                Tables\Columns\TextColumn::make('submissions_count')
                    ->counts('submissions')
                    ->label('Submissions'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('view_submissions')
                    ->icon('heroicon-o-eye')
                    ->url(fn (ContactForm $record): string => 
                        route('filament.admin.resources.form-submissions.index', [
                            'tableFilters' => [
                                'contact_form_id' => ['value' => $record->id]
                            ]
                        ])
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactForms::route('/'),
            'create' => Pages\CreateContactForm::route('/create'),
            'edit' => Pages\EditContactForm::route('/{record}/edit'),
        ];
    }
}