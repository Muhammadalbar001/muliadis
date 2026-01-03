<?php

namespace App\Livewire\Master;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $isOpen = false;
    public $isEdit = false;

    // Form Fields
    public $userId, $name, $username, $email, $role = 'admin', $password;

    public function render()
    {
        $users = User::query()
            ->where(function($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                  ->orWhere('email', 'like', '%'.$this->search.'%')
                  ->orWhere('username', 'like', '%'.$this->search.'%');
            })
            ->latest()
            ->paginate(12);

        return view('livewire.master.user-index', compact('users'))
            ->layout('layouts.app', ['header' => 'Manajemen User']);
    }

    public function create()
    {
        $this->resetFields();
        $this->isEdit = false;
        $this->isOpen = true;
    }

    public function edit($id)
    {
        $this->resetFields();
        $user = User::findOrFail($id);
        $this->userId = $id;
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->role = $user->role; 
        $this->isEdit = true;
        $this->isOpen = true;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|min:3',
            'username' => ['required', 'alpha_dash', Rule::unique('users', 'username')->ignore($this->userId)],
            'email' => ['nullable', 'email', Rule::unique('users', 'email')->ignore($this->userId)],
            // Role disesuaikan dengan enum terbaru di migration
            'role' => 'required|in:superadmin,pimpinan,supervisor,admin',
            'password' => $this->isEdit ? 'nullable|min:6' : 'required|min:6',
        ]);

        try {
            $data = [
                'name' => $this->name,
                'username' => $this->username,
                'email' => $this->email,
                'role' => $this->role,
            ];

            if ($this->password) {
                $data['password'] = Hash::make($this->password);
            }

            User::updateOrCreate(['id' => $this->userId], $data);

            $this->isOpen = false;
            $this->resetFields();
            $this->dispatch('show-toast', type: 'success', message: 'Data User & Role berhasil disimpan.');
        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Gagal simpan: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        if ($id == Auth::id()) {
            $this->dispatch('show-toast', type: 'error', message: 'Anda tidak bisa menghapus diri sendiri!');
            return;
        }
        
        try {
            $user = User::findOrFail($id);
            $user->delete();
            $this->dispatch('show-toast', type: 'success', message: 'User berhasil dihapus.');
        } catch (\Exception $e) {
            $this->dispatch('show-toast', type: 'error', message: 'Gagal menghapus: User ini mungkin memiliki keterkaitan data lain.');
        }
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetFields();
    }

    private function resetFields()
    {
        $this->reset(['userId', 'name', 'username', 'email', 'role', 'password']);
        $this->role = 'admin'; 
        $this->resetErrorBag();
    }
}