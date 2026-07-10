<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\SystemSetting;

class SystemSettings extends Component
{
    public $company_name;
    public $ocr_language;

    public function mount()
    {
        $this->company_name = SystemSetting::where('setting_key', 'company_name')->value('setting_value') ?? '';
        $this->ocr_language = SystemSetting::where('setting_key', 'ocr_language')->value('setting_value') ?? 'id';
    }

    public function saveSettings()
    {
        SystemSetting::updateOrCreate(
            ['setting_key' => 'company_name'],
            ['setting_value' => $this->company_name]
        );
        
        SystemSetting::updateOrCreate(
            ['setting_key' => 'ocr_language'],
            ['setting_value' => $this->ocr_language]
        );

        session()->flash('settings_message', 'Pengaturan sistem berhasil disimpan.');
    }

    public function approveUser($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $user->account_status = 'approved';
            $user->save();
            session()->flash('user_message', 'Akun ' . $user->name . ' telah disetujui.');
        }
    }

    public function rejectUser($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $name = $user->name;
            $user->delete(); // Hard delete or change status to rejected
            session()->flash('user_error', 'Akun ' . $name . ' telah ditolak dan dihapus.');
        }
    }

    public function runManualBackup()
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('backup:run');
            $this->dispatch('notify', message: 'Backup manual berhasil dieksekusi.', type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Gagal menjalankan backup: ' . $e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        $pendingUsers = User::where('account_status', 'pending')->get();
        return view('livewire.system-settings', compact('pendingUsers'))->layout('components.layouts.app');
    }
}
