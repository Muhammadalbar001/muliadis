<div class="space-y-6 font-jakarta">

    <div
        class="sticky top-0 z-40 backdrop-blur-md bg-slate-900/90 p-4 rounded-b-2xl shadow-lg border-b border-slate-700 transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 mb-6">
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">

            <div class="flex items-center gap-4 w-full md:w-auto">
                <div class="p-2 bg-slate-800 rounded-lg text-slate-300 border border-slate-700">
                    <i class="fas fa-users-cog text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold text-white tracking-tight">Manajemen User</h1>
                    <p class="text-xs text-slate-400 font-medium mt-0.5">Atur akses dan role pengguna sistem.</p>
                </div>
            </div>

            <div class="flex gap-3 items-center w-full md:w-auto justify-end">

                <div class="relative w-full md:w-64">
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="pl-10 w-full bg-slate-800 border-slate-700 rounded-lg text-xs font-bold text-white focus:ring-blue-500 py-2.5 shadow-inner placeholder-slate-500"
                        placeholder="Cari Nama / Email...">
                    <i class="fas fa-search absolute left-3 top-3 text-slate-500 text-xs"></i>
                </div>

                <button wire:click="create"
                    class="px-4 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-lg text-xs font-bold shadow-lg shadow-blue-500/30 transition-all flex items-center gap-2 whitespace-nowrap">
                    <i class="fas fa-user-plus"></i> <span class="hidden sm:inline">User Baru</span>
                </button>

                <div wire:loading
                    class="p-2.5 bg-slate-800 border border-slate-700 text-blue-400 rounded-lg shadow-sm flex items-center justify-center animate-pulse">
                    <i class="fas fa-circle-notch fa-spin"></i>
                </div>
            </div>
        </div>
    </div>

    <div wire:loading.class="opacity-50 pointer-events-none" class="transition-opacity duration-200">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase border-b border-slate-200 text-xs">
                        <tr>
                            <th class="px-6 py-4">User Info</th>
                            <th class="px-6 py-4">Username</th>
                            <th class="px-6 py-4 text-center">Role (Hak Akses)</th>
                            <th class="px-6 py-4 text-center">Bergabung</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $user)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-9 h-9 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 font-bold border border-slate-300 uppercase">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800">{{ $user->name }}</div>
                                        <div class="text-xs text-slate-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-mono text-slate-600 text-xs">
                                @ {{ $user->username }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($user->role == 'admin')
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-900 text-white border border-slate-700">
                                    <i class="fas fa-crown mr-1.5 text-yellow-400"></i> ADMIN
                                </span>
                                @elseif($user->role == 'pimpinan')
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-700 border border-purple-200">
                                    <i class="fas fa-user-tie mr-1.5"></i> PIMPINAN
                                </span>
                                @else
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                    <i class="fas fa-user mr-1.5"></i> PENGGUNA
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center text-xs text-slate-500">
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <button wire:click="edit({{ $user->id }})"
                                        class="w-8 h-8 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all border border-transparent hover:border-blue-100">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @if($user->id !== auth()->id())
                                    <button wire:click="delete({{ $user->id }})"
                                        onclick="return confirm('Hapus user ini?') || event.stopImmediatePropagation()"
                                        class="w-8 h-8 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-all border border-transparent hover:border-red-100">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">Tidak ada data user.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">{{ $users->links() }}</div>
        </div>
    </div>

    @if($isOpen)
    <div class="fixed inset-0 z-[60] overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" wire:click="closeModal">
            </div>
            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-white/20">

                <div class="bg-slate-900 px-6 py-4 border-b border-slate-700 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="fas {{ $isEdit ? 'fa-user-edit' : 'fa-user-plus' }} text-blue-400"></i>
                        {{ $isEdit ? 'Edit User' : 'Tambah User Baru' }}
                    </h3>
                    <button wire:click="closeModal" class="text-slate-400 hover:text-white transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="px-6 py-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Nama Lengkap</label>
                            <input type="text" wire:model="name"
                                class="w-full rounded-lg border-slate-300 text-sm focus:ring-blue-900 focus:border-blue-900"
                                placeholder="John Doe">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Username</label>
                            <input type="text" wire:model="username"
                                class="w-full rounded-lg border-slate-300 text-sm focus:ring-blue-900 focus:border-blue-900"
                                placeholder="johndoe">
                            @error('username') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Email Address</label>
                        <input type="email" wire:model="email"
                            class="w-full rounded-lg border-slate-300 text-sm focus:ring-blue-900 focus:border-blue-900"
                            placeholder="email@perusahaan.com">
                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-1">Role / Hak Akses</label>
                        <div class="grid grid-cols-3 gap-2">
                            <label class="cursor-pointer">
                                <input type="radio" wire:model="role" value="admin" class="peer sr-only">
                                <div
                                    class="p-2 rounded-lg border border-slate-200 text-center text-xs font-bold text-slate-500 peer-checked:bg-slate-900 peer-checked:text-white peer-checked:border-slate-900 transition-all hover:bg-slate-50">
                                    ADMIN
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" wire:model="role" value="pimpinan" class="peer sr-only">
                                <div
                                    class="p-2 rounded-lg border border-slate-200 text-center text-xs font-bold text-slate-500 peer-checked:bg-purple-600 peer-checked:text-white peer-checked:border-purple-600 transition-all hover:bg-slate-50">
                                    PIMPINAN
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" wire:model="role" value="pengguna" class="peer sr-only">
                                <div
                                    class="p-2 rounded-lg border border-slate-200 text-center text-xs font-bold text-slate-500 peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:border-emerald-600 transition-all hover:bg-slate-50">
                                    PENGGUNA
                                </div>
                            </label>
                        </div>
                        @error('role') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-2 border-t border-slate-100 mt-2">
                        <label class="block text-xs font-bold text-slate-600 uppercase mb-1">
                            {{ $isEdit ? 'Reset Password (Opsional)' : 'Password' }}
                        </label>
                        <input type="password" wire:model="password"
                            class="w-full rounded-lg border-slate-300 text-sm focus:ring-blue-900 focus:border-blue-900"
                            placeholder="{{ $isEdit ? 'Kosongkan jika tidak ingin mengubah' : 'Minimal 6 karakter' }}">
                        @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="bg-slate-50 px-6 py-4 flex flex-row-reverse gap-2 border-t border-slate-200">
                    <button wire:click="store"
                        class="px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-lg text-sm font-bold shadow-lg transition-all transform hover:-translate-y-0.5">
                        Simpan Data
                    </button>
                    <button wire:click="closeModal"
                        class="px-4 py-2 bg-white border border-slate-300 text-slate-600 rounded-lg text-sm font-bold hover:bg-slate-50 transition-all">
                        Batal
                    </button>
                </div>

            </div>
        </div>
    </div>
    @endif

</div>