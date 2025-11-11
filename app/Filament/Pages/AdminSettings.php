<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class AdminSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static string $view = 'filament.pages.admin-settings';
    protected static ?string $title = 'Admin Settings';
    protected static ?string $navigationLabel = 'Admin Settings';

    public ?array $data = [];

    public function mount(): void
    {
        $user = Auth::user();
        $this->form->fill([
            'email' => $user->email,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->label('Admin Email'),
                
                Forms\Components\TextInput::make('current_password')
                    ->password()
                    ->label('Current Password')
                    ->required()
                    ->helperText('Enter your current password to make changes'),
                
                Forms\Components\TextInput::make('new_password')
                    ->password()
                    ->label('New Password (Optional)')
                    ->helperText('Leave empty to keep current password'),
                
                Forms\Components\TextInput::make('new_password_confirmation')
                    ->password()
                    ->label('Confirm New Password')
                    ->same('new_password')
                    ->requiredWith('new_password'),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Update Settings')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $user = Auth::user();

        // Verify current password
        if (!Hash::check($data['current_password'], $user->password)) {
            Notification::make()
                ->title('Current password is incorrect')
                ->danger()
                ->send();
            return;
        }

        // Update email
        $user->email = $data['email'];

        // Update password if provided
        if (!empty($data['new_password'])) {
            $user->password = Hash::make($data['new_password']);
        }

        $user->save();

        Notification::make()
            ->title('Settings updated successfully')
            ->success()
            ->send();

        // Clear password fields
        $this->form->fill([
            'email' => $user->email,
            'current_password' => '',
            'new_password' => '',
            'new_password_confirmation' => '',
        ]);
    }
}