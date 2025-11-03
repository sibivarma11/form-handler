<?php

namespace App\Filament\Pages;

use App\Models\EmailSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

class EmailSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static string $view = 'filament.pages.email-settings';
    protected static ?string $navigationLabel = 'Email Settings';
    protected static ?string $title = 'Email Configuration';
    protected static ?int $navigationSort = 99;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'mail_host' => EmailSetting::get('mail_host', 'smtp.gmail.com'),
            'mail_port' => EmailSetting::get('mail_port', '587'),
            'mail_username' => EmailSetting::get('mail_username'),
            'mail_password' => EmailSetting::get('mail_password'),
            'mail_encryption' => EmailSetting::get('mail_encryption', 'tls'),
            'mail_from_address' => EmailSetting::get('mail_from_address'),
            'mail_from_name' => EmailSetting::get('mail_from_name'),
            'contact_form_recipient' => EmailSetting::get('contact_form_recipient'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('SMTP Configuration')
                    ->schema([
                        Forms\Components\TextInput::make('mail_host')
                            ->label('SMTP Host')
                            ->required()
                            ->placeholder('smtp.gmail.com'),
                        Forms\Components\TextInput::make('mail_port')
                            ->label('SMTP Port')
                            ->numeric()
                            ->required()
                            ->placeholder('587'),
                        Forms\Components\TextInput::make('mail_username')
                            ->label('SMTP Username')
                            ->email()
                            ->required(),
                        Forms\Components\TextInput::make('mail_password')
                            ->label('SMTP Password (App Password)')
                            ->password()
                            ->required()
                            ->helperText('Use App Password for Gmail'),
                        Forms\Components\Select::make('mail_encryption')
                            ->label('Encryption')
                            ->options([
                                'tls' => 'TLS',
                                'ssl' => 'SSL',
                            ])
                            ->default('tls')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Email Settings')
                    ->schema([
                        Forms\Components\TextInput::make('mail_from_address')
                            ->label('From Email Address')
                            ->email()
                            ->required(),
                        Forms\Components\TextInput::make('mail_from_name')
                            ->label('From Name')
                            ->required(),
                        Forms\Components\TextInput::make('contact_form_recipient')
                            ->label('Form Notification Recipient')
                            ->email()
                            ->required()
                            ->helperText('Email address to receive form submission notifications'),
                    ])->columns(2),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Settings')
                ->color('primary')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->data;

        foreach ($data as $key => $value) {
            EmailSetting::set($key, $value);
        }

        Notification::make()
            ->title('Settings saved')
            ->success()
            ->send();
    }
}
