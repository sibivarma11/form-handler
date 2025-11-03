<?php

namespace App\Providers;

use App\Models\EmailSetting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class EmailConfigServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        try {
            $mailHost = EmailSetting::get('mail_host');
            
            if ($mailHost) {
                Config::set('mail.mailers.smtp.host', $mailHost);
                Config::set('mail.mailers.smtp.port', EmailSetting::get('mail_port'));
                Config::set('mail.mailers.smtp.username', EmailSetting::get('mail_username'));
                Config::set('mail.mailers.smtp.password', EmailSetting::get('mail_password'));
                Config::set('mail.mailers.smtp.encryption', EmailSetting::get('mail_encryption'));
                Config::set('mail.from.address', EmailSetting::get('mail_from_address'));
                Config::set('mail.from.name', EmailSetting::get('mail_from_name'));
            }
        } catch (\Exception $e) {
            // Ignore database errors during boot
        }
    }
}