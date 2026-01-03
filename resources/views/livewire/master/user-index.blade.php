<div class="min-h-screen space-y-6 pb-10 transition-colors duration-300 font-jakarta bg-slate-50 dark:bg-[#050505]">

    <div class="sticky top-0 z-40 backdrop-blur-xl border-b transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 mb-6
        dark:bg-[#0a0a0a]/80 dark:border-white/5 bg-white/95 border-slate-200 shadow-sm">

        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="flex items-center gap-4 w-full md:w-auto">
                <div
                    class="p-3 rounded-2xl shadow-xl bg-gradient-to-tr from-indigo-600 to-blue-500 text-white ring-4 ring-indigo-500/10">
                    <i class="fas fa-users-cog text-xl"></i>
                </div>
                <div>
                    <h1
                        class="text-xl font-black tracking-tighter uppercase leading-none dark:text-white text-slate-900">
                        Manajemen <span class="text-indigo-600">Akses</span>
                    </h1>
                    <p
                        class="text-[9px] font-bold uppercase tracking-[0.3em] dark:text-slate-400 text-slate-600 mt-1.5 opacity-80">
                        Security & User Control
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                <div class="relative group">
                    <i
                        class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-600 transition-colors text-xs"></i>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="pl-10 pr-4 py-2.5 w-64 rounded-2xl border text-[11px] font-bold uppercase transition-all
                        dark:bg-white/5 dark:border-white/10 dark:text-white dark:focus:border-indigo-500/50
                        bg-white border-slate-300 text-slate-900 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none shadow-sm"
                        placeholder="Cari User / Username...">
                </div>

                <button wire:click="create"
                    class="px-6 py-2.5 bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-indigo-600/20 hover:bg-indigo-700 hover:scale-105 transition-all active:scale-95 flex items-center gap-2">
                    <i class="fas fa-plus"></i> Baru
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($users as $user)
            <div
                class="relative group p-8 rounded-[2.5rem] border transition-all duration-300
                dark:bg-[#0f0f0f] dark:border-white/5 bg-white border-slate-200 shadow-2xl shadow-slate-200/50 dark:shadow-none hover:border-indigo-500/50 dark:hover:border-indigo-500/50">

                <div class="flex flex-col items-center text-center">
                    <div class="relative mb-6">
                        <div
                            class="w-20 h-20 rounded-[2rem] dark:bg-indigo-500/10 bg-indigo-600 flex items-center justify-center text-white font-black text-2xl border-4 border-white dark:border-[#1a1a1a] shadow-xl group-hover:scale-110 transition-transform">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-7 h-7 rounded-full border-4 dark:border-[#0f0f0f] border-white flex items-center justify-center shadow-lg
                            {{ $user->role == 'superadmin' ? 'bg-rose-500' : ($user->role == 'pimpinan' ? 'bg-amber-500' : ($user->role == 'supervisor' ? 'bg-indigo-500' : 'bg-emerald-500')) }}"
                            title="{{ strtoupper($user->role) }}">
                            <i
                                class="fas {{ $user->role == 'superadmin' ? 'fa-shield-alt' : ($user->role == 'pimpinan' ? 'fa-star' : ($user->role == 'supervisor' ? 'fa-user-tie' : 'fa-user')) }} text-[10px] text-white"></i>
                        </div>
                    </div>

                    <h3
                        class="font-black dark:text-white text-slate-900 text-sm tracking-tight uppercase group-hover:text-indigo-600 transition-colors">
                        {{ $user->name }}
                    </h3>
                    <p
                        class="text-[10px] font-black text-slate-500 dark:text-slate-400 mt-1 lowercase opacity-70 italic">
                        @<span>{{ $user->username }}</span></p>

                    <div class="mt-5 px-5 py-1.5 rounded-full text-[9px] font-black tracking-[0.2em] border uppercase
                        {{ $user->role == 'superadmin' ? 'dark:bg-rose-500/10 bg-rose-50 text-rose-700 border-rose-200' : 
                          ($user->role == 'pimpinan' ? 'dark:bg-amber-500/10 bg-amber-50 text-amber-700 border-amber-200' : 
                          ($user->role == 'supervisor' ? 'dark:bg-indigo-500/10 bg-indigo-50 text-indigo-700 border-indigo-200' :
                          'dark:bg-emerald-500/10 bg-emerald-50 text-emerald-700 border-emerald-200')) }}">
                        @if($user->role == 'superadmin') Super Admin
                        @elseif($user->role == 'pimpinan') Pimpinan
                        @elseif($user->role == 'supervisor') Supervisor
                        @else Admin @endif
                    </div>
                </div>

                <div class="flex items-center gap-3 mt-8 pt-6 border-t dark:border-white/5 border-slate-100">
                    <button wire:click="edit({{ $user->id }})"
                        class="flex-1 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest bg-slate-100 dark:bg-white/5 text-slate-700 dark:text-slate-400 hover:bg-indigo-600 hover:text-white dark:hover:bg-indigo-600 transition-all shadow-sm flex items-center justify-center gap-2 border border-slate-200 dark:border-none">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    @if($user->id !== auth()->id())
                    <button wire:click="delete({{ $user->id }})"
                        onclick="confirm('Hapus user ini?') || event.stopImmediatePropagation()"
                        class="flex-1 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest bg-rose-50 dark:bg-rose-500/10 text-rose-700 dark:text-rose-400 hover:bg-rose-600 hover:text-white dark:hover:bg-rose-600 transition-all shadow-sm flex items-center justify-center gap-2 border border-rose-100 dark:border-none">
                        <i class="fas fa-trash-alt"></i> Hapus
                    </button>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-span-full py-32 text-center">
                <div class="flex flex-col items-center opacity-20 text-slate-400">
                    <i class="fas fa-user-shield text-6xl mb-4"></i>
                    <p class="text-xs font-black tracking-[0.4em] uppercase">Database User Kosong</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    @if($isOpen)
    <div class="fixed inset-0 z-[160] overflow-y-auto px-4" role="dialog">
        <div class="flex items-center justify-center min-h-screen">
            <div class="fixed inset-0 bg-slate-900/90 backdrop-blur-md transition-opacity" wire:click="closeModal">
            </div>

            <div
                class="relative dark:bg-[#0d0d0d] bg-white rounded-[2.5rem] shadow-[0_0_50px_rgba(0,0,0,0.4)] w-full max-w-md overflow-hidden border dark:border-white/10 border-slate-200 transform transition-all">
                <div
                    class="bg-gradient-to-r from-indigo-700 to-indigo-500 px-10 py-8 text-white flex justify-between items-center relative overflow-hidden shadow-lg">
                    <div class="absolute -right-4 -bottom-4 opacity-10 rotate-12">
                        <i class="fas fa-user-shield text-7xl"></i>
                    </div>
                    <div class="relative z-10">
                        <h3 class="font-black uppercase tracking-widest text-sm">Personnel Profile</h3>
                        <p class="text-[10px] font-bold opacity-70 mt-1 uppercase tracking-widest">
                            {{ $userId ? 'Update User Account' : 'Register New User' }}</p>
                    </div>
                    <button wire:click="closeModal"
                        class="relative z-10 w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition-all">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>

                <div class="p-10 space-y-6">
                    <div class="group">
                        <label
                            class="text-[10px] font-black uppercase text-slate-600 dark:text-slate-400 group-focus-within:text-indigo-600 transition-colors block mb-2 ml-1 tracking-wider">Nama
                            Lengkap</label>
                        <input type="text" wire:model="name"
                            class="w-full rounded-2xl border-slate-300 dark:bg-white/5 dark:border-white/10 dark:text-white text-xs font-black uppercase focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 transition-all p-3.5 bg-slate-50 shadow-inner text-slate-900">
                        @error('name') <span
                            class="text-rose-600 text-[9px] font-black mt-1.5 ml-1 uppercase block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="group">
                        <label
                            class="text-[10px] font-black uppercase text-slate-600 dark:text-slate-400 group-focus-within:text-indigo-600 transition-colors block mb-2 ml-1 tracking-wider">Username
                            (Login ID)</label>
                        <input type="text" wire:model="username"
                            class="w-full rounded-2xl border-slate-300 dark:bg-white/5 dark:border-white/10 dark:text-white text-xs font-black lowercase focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 transition-all p-3.5 bg-slate-50 shadow-inner text-slate-900"
                            placeholder="username_untuk_login">
                        @error('username') <span
                            class="text-rose-600 text-[9px] font-black mt-1.5 ml-1 uppercase block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="group">
                        <label
                            class="text-[10px] font-black uppercase text-slate-600 dark:text-slate-400 group-focus-within:text-indigo-600 transition-colors block mb-2 ml-1 tracking-wider">Level
                            Otoritas</label>
                        <select wire:model="role"
                            class="w-full rounded-2xl border-slate-300 dark:bg-white/5 dark:border-white/10 dark:text-white text-xs font-black uppercase focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 transition-all p-3.5 bg-slate-50 shadow-inner appearance-none text-slate-900">
                            <option value="superadmin">SUPER ADMIN</option>
                            <option value="pimpinan">PIMPINAN</option>
                            <option value="supervisor">SUPERVISOR</option>
                            <option value="admin">ADMIN</option>
                        </select>
                    </div>

                    <div class="group">
                        <label
                            class="text-[10px] font-black uppercase text-slate-600 dark:text-slate-400 group-focus-within:text-indigo-600 transition-colors block mb-2 ml-1 tracking-wider">
                            {{ $userId ? 'Password Baru (Opsional)' : 'Set Password' }}
                        </label>
                        <input type="password" wire:model="password"
                            class="w-full rounded-2xl border-slate-300 dark:bg-white/5 dark:border-white/10 dark:text-white text-xs font-black focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 transition-all p-3.5 bg-slate-50 shadow-inner text-slate-900">
                        @error('password') <span
                            class="text-rose-600 text-[9px] font-black mt-1.5 ml-1 uppercase block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div
                    class="dark:bg-white/[0.02] bg-slate-100 px-10 py-8 flex justify-end gap-3 border-t dark:border-white/5 border-slate-200">
                    <button wire:click="closeModal"
                        class="px-6 py-2.5 text-[10px] font-black uppercase text-slate-500 hover:text-rose-600 transition-colors tracking-widest">Batal</button>
                    <button wire:click="store"
                        class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl text-[10px] font-black uppercase shadow-xl shadow-indigo-600/30 transition-all active:scale-95 tracking-widest">Simpan
                        Akun</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>