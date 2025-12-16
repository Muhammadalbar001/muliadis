<?php

namespace App\Livewire\Master;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $isOpen = false;
    public $isEdit = false;

    // Form Fields
    public $userId, $name, $username, $email, $role, $password;

    public function render()
    {
        $users = User::query()
            ->where('name', 'like', '%'.$this->search.'%')
            ->orWhere('email', 'like', '%'.$this->search.'%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

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
        // Validasi
        $rules = [
            'name' => 'required',
            'username' => 'required|unique:users,username,' . $this->userId,
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'role' => 'required',
        ];

        // Jika mode tambah baru, password wajib. Jika edit, password opsional (untuk reset).
        if (!$this->isEdit) {
            $rules['password'] = 'required|min:6';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'role' => $this->role,
        ];

        // Hanya update password jika diisi
        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        User::updateOrCreate(['id' => $this->userId], $data);

        $this->isOpen = false;
        $this->dispatch('show-toast', ['type' => 'success', 'message' => 'Data User berhasil disimpan.']);
    }

    public function delete($id)
    {
        if ($id == auth()->id()) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'Anda tidak bisa menghapus diri sendiri!']);
            return;
        }
        
        User::destroy($id);
        $this->dispatch('show-toast', ['type' => 'success', 'message' => 'User berhasil dihapus.']);
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetFields()
    {
        $this->reset(['userId', 'name', 'username', 'email', 'role', 'password']);
    }
}