<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FormSubmissionResource\Pages;
use App\Models\FormSubmission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FormSubmissionResource extends Resource
{
    protected static ?string $model = FormSubmission::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox';

    protected static ?string $navigationLabel = 'Form Submissions';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('contact_form_id')
                    ->relationship('contactForm', 'name')
                    ->required(),
                Forms\Components\KeyValue::make('data')
                    ->label('Submitted Data')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('ip_address'),
                Forms\Components\Textarea::make('user_agent')
                    ->rows(3),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('contactForm.name')
                    ->label('Form')
                    ->sortable(),
                Tables\Columns\TextColumn::make('data.email')
                    ->label('Email')
                    ->default('N/A'),
                Tables\Columns\TextColumn::make('is_read')
                    ->label('Status')
                    ->formatStateUsing(fn ($state) => $state ? 'Seen' : 'Unseen')
                    ->badge()
                    ->color(fn ($state) => $state ? 'success' : 'warning'),
                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime()
                //     ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('contact_form_id')
                    ->relationship('contactForm', 'name')
                    ->label('Form'),
                Tables\Filters\TernaryFilter::make('is_read'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFormSubmissions::route('/'),
            'view' => Pages\ViewFormSubmission::route('/{record}'),
        ];
    }
}