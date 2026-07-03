<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserProfile extends Component
{
    public $name;
    public $email;
    public $phone_number;
    public $bio;
    public $new_password;
    public $new_password_confirmation;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone_number = $user->phone_number;
        $this->bio = $user->bio;
    }

    public function updateProfile()
    {
        $user = Auth::user();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
        ];

        if ($this->new_password) {
            $rules['new_password'] = 'required|min:8|confirmed';
        }

        $this->validate($rules);

        $user->name = $this->name;
        $user->email = $this->email;
        $user->phone_number = $this->phone_number;
        $user->bio = $this->bio;

        if ($this->new_password) {
            $user->password = Hash::make($this->new_password);
        }

        $user->save();

        session()->flash('message', 'Profil berhasil diperbarui!');
        $this->new_password = null;
        $this->new_password_confirmation = null;
    }

    public function render()
    {
        return view('livewire.user-profile')->layout('components.layouts.app');
    }
}
