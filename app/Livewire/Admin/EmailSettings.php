<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class EmailSettings extends Component
{
    public $mailer;
    public $host;
    public $port;
    public $username;
    public $password;
    public $encryption;
    public $from_address;
    public $from_name;
    public $test_email;

    public function mount()
    {
        $this->mailer = setting('mail.mailer', config('mail.default'));
        $this->host = setting('mail.host', config('mail.mailers.smtp.host'));
        $this->port = setting('mail.port', config('mail.mailers.smtp.port'));
        $this->username = setting('mail.username', config('mail.mailers.smtp.username'));
        $this->password = setting('mail.password', '');
        $this->encryption = setting('mail.encryption', config('mail.mailers.smtp.encryption'));
        $this->from_address = setting('mail.from.address', config('mail.from.address'));
        $this->from_name = setting('mail.from.name', config('mail.from.name'));
        $this->test_email = '';
    }

    public function save()
    {
        \Illuminate\Support\Facades\Log::info('EmailSettings: Save called', $this->all());
        
        $this->validate([
            'mailer' => 'required|string|max:255',
            'host' => 'nullable|string|max:255',
            'port' => 'nullable|integer|min:1|max:65535',
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string|max:255',
            'encryption' => 'nullable|string|max:255',
            'from_address' => 'required|email|max:255',
            'from_name' => 'required|string|max:255',
        ]);

        try {
            setting_set('mail', 'mailer', $this->mailer, 'string');
            setting_set('mail', 'host', $this->host, 'string');
            setting_set('mail', 'port', $this->port, 'integer');
            setting_set('mail', 'username', $this->username, 'string');
            if ($this->password) {
                setting_set('mail', 'password', $this->password, 'string');
            }
            setting_set('mail', 'encryption', $this->encryption, 'string');
            setting_set('mail', 'from.address', $this->from_address, 'string');
            setting_set('mail', 'from.name', $this->from_name, 'string');

            Cache::forget('app.settings');
            
            // Reload component state to reflect saved values
            $this->mount();
            
            \Illuminate\Support\Facades\Log::info('EmailSettings: Save successful');
            session()->flash('success', 'Email settings updated successfully!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('EmailSettings: Save failed', ['error' => $e->getMessage()]);
            session()->flash('error', 'Failed to save settings: ' . $e->getMessage());
        }
    }

    public function sendTestEmail()
    {
        \Illuminate\Support\Facades\Log::info('EmailSettings: Send Test Email called', ['email' => $this->test_email]);
        
        $this->validate([
            'test_email' => 'required|email',
        ]);

        try {
            // Temporarily override config
            $config = [
                'mail.default' => $this->mailer,
                'mail.mailers.smtp.host' => $this->host,
                'mail.mailers.smtp.port' => $this->port,
                'mail.mailers.smtp.username' => $this->username,
                'mail.mailers.smtp.password' => $this->password ?: setting('mail.password', ''),
                'mail.mailers.smtp.encryption' => $this->encryption,
                'mail.from.address' => $this->from_address,
                'mail.from.name' => $this->from_name,
            ];
            
            \Illuminate\Support\Facades\Log::info('EmailSettings: Using config', $config);
            config($config);

            Mail::raw('This is a test email from ' . config('app.name'), function ($message) {
                $message->to($this->test_email)
                        ->subject('Test Email from ' . config('app.name'));
            });

            \Illuminate\Support\Facades\Log::info('EmailSettings: Test email sent');
            session()->flash('success', 'Test email sent successfully!');
            $this->test_email = '';
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('EmailSettings: Test email failed', ['error' => $e->getMessage()]);
            session()->flash('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.email-settings')->layout('layouts.admin');
    }
}

