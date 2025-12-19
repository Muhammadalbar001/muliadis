<div class="min-h-screen space-y-6 pb-10 transition-colors duration-300 font-jakarta">

    <div class="sticky top-0 z-40 backdrop-blur-xl border-b transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 mb-6
        dark:bg-[#0a0a0a]/80 dark:border-white/5 bg-white/80 border-slate-200 shadow-sm">

        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="flex items-center gap-4 w-full md:w-auto">
                <div
                    class="p-2.5 rounded-xl shadow-lg dark:bg-indigo-500/20 bg-indigo-600 text-white dark:text-indigo-400">
                    <i class="fas fa-users-cog text-xl"></i>
                </div>
                <div>
                    <h1
                        class="text-xl font-black tracking-tighter uppercase leading-none dark:text-white text-slate-800">
                        Manajemen <span class="text-indigo-500">Akses</span>
                    </h1>
                    <p
                        class="text-[9px] font-bold uppercase tracking-[0.3em] opacity-50 mt-1.5 dark:text-slate-400 text-slate-500">
                        Security & User Control</p>
                </div>
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                <div class="relative flex-1 md:w-64 group">
                    <i
                        class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 text-xs"></i>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="w-full pl-9 pr-4 py-2.5 rounded-xl border text-[11px] font-bold uppercase tracking-widest focus:ring-2 focus:ring-indigo-500/20 dark:bg-black/40 dark:border-white/10 dark:text-white bg-slate-100 border-slate-200 shadow-inner"
                        placeholder="Cari User...">
                </div>

                <button wire:click="create"
                    class="flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-indigo-600/20 transition-all transform active:scale-95">
                    <i class="fas fa-plus"></i> Baru
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($users as $user)
            <div
                class="relative group p-6 rounded-[2.5rem] border transition-all duration-300
                dark:bg-slate-900/40 dark:border-white/5 bg-white border-slate-200 shadow-xl hover:shadow-indigo-500/10 dark:shadow-black/40">

                <div class="flex flex-col items-center text-center">
                    <div class="relative mb-4">
                        <div
                            class="w-20 h-20 rounded-[2rem] dark:bg-indigo-500/10 bg-indigo-50 flex items-center justify-center text-indigo-600 font-black text-2xl border dark:border-indigo-500/20 border-indigo-100 shadow-inner">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full border-4 dark:border-[#0a0a0a] border-white flex items-center justify-center
                            {{ $user->role == 'admin' ? 'bg-rose-500' : ($user->role == 'pimpinan' ? 'bg-amber-500' : 'bg-emerald-500') }}"
                            title="{{ $user->role }}">
                            <i
                                class="fas {{ $user->role == 'admin' ? 'fa-shield-alt' : ($user->role == 'pimpinan' ? 'fa-star' : 'fa-user') }} text-[10px] text-white"></i>
                        </div>
                    </div>

                    <h3 class="font-black dark:text-white text-slate-800 text-sm tracking-tight uppercase">
                        {{ $user->name }}</h3>
                    <p class="text-[10px] font-medium text-slate-500 mt-1">{{ $user->email }}</p>

                    <div
                        class="mt-4 px-4 py-1 rounded-full text-[9px] font-black tracking-[0.2em] border dark:bg-white/5 bg-slate-50 dark:text-slate-400 text-slate-500 border-slate-100 dark:border-white/10 uppercase">
                        {{ $user->role }}
                    </div>
                </div>

                <div class="flex items-center gap-2 mt-6 pt-4 border-t dark:border-white/5 border-slate-100">
                    <button wire:click="edit({{ $user->id }})"
                        class="flex-1 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest dark:bg-white/5 bg-slate-50 dark:text-slate-400 text-slate-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </button>
                    @if($user->id !== auth()->id())
                    <button wire:click="delete({{ $user->id }})"
                        onclick="confirm('Hapus user ini?') || event.stopImmediatePropagation()"
                        class="flex-1 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest dark:bg-white/5 bg-slate-50 text-rose-500 hover:bg-rose-600 hover:text-white transition-all shadow-sm">
                        <i class="fas fa-trash-alt mr-1"></i> Hapus
                    </button>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-span-full py-20 text-center opacity-20">
                <i class="fas fa-user-shield text-6xl mb-4"></i>
                <p class="text-xs font-black tracking-[0.4em]">Database User Kosong</p>
            </div>
            @endforelse
        </div>

        <div class="mt-8">{{ $users->links() }}</div>
    </div>

    @if($isOpen)
    <div class="fixed inset-0 z-[110] overflow-y-auto" role="dialog">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-md transition-opacity" wire:click="closeModal">
            </div>

            <div
                class="relative dark:bg-[#0a0a0a] bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md overflow-hidden border dark:border-white/10 border-slate-200">
                <div class="bg-indigo-600 px-8 py-6 text-white flex justify-between items-center shadow-lg">
                    <div>
                        <h3 class="font-black uppercase tracking-widest text-sm">
                            {{ $userId ? 'Update Akun' : 'Registrasi User' }}</h3>
                        <p class="text-[9px] font-bold opacity-60 uppercase tracking-[0.2em] mt-1">Identity Management
                        </p>
                    </div>
                    <button wire:click="closeModal"
                        class="w-8 h-8 rounded-full bg-black/10 flex items-center justify-center hover:bg-black/20 transition-all"><i
                            class="fas fa-times text-xs"></i></button>
                </div>

                <div class="p-8 space-y-5">
                    <div>
                        <label
                            class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Nama
                            Lengkap</label>
                        <input type="text" wire:model="name"
                            class="w-full px-5 py-3 rounded-2xl border dark:bg-black/40 bg-slate-50 dark:border-white/10 border-slate-200 text-sm font-bold dark:text-white text-slate-800 focus:ring-2 focus:ring-indigo-500/20">
                        @error('name') <span class="text-rose-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label
                            class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Email
                            Address</label>
                        <input type="email" wire:model="email"
                            class="w-full px-5 py-3 rounded-2xl border dark:bg-black/40 bg-slate-50 dark:border-white/10 border-slate-200 text-sm font-bold dark:text-white text-slate-800 focus:ring-2 focus:ring-indigo-500/20">
                        @error('email') <span
                            class="text-rose-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label
                            class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Level
                            Otoritas</label>
                        <select wire:model="role"
                            class="w-full px-5 py-3 rounded-2xl border dark:bg-black/40 bg-slate-50 dark:border-white/10 border-slate-200 text-sm font-bold dark:text-white text-slate-800 focus:ring-2 focus:ring-indigo-500/20">
                            <option value="staff">STAFF (Digital Ledger)</option>
                            <option value="pimpinan">PIMPINAN (Executive Control)</option>
                            <option value="admin">ADMINISTRATOR (Full Access)</option>
                        </select>
                        @error('role') <span class="text-rose-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">
                            {{ $userId ? 'Password Baru (Kosongkan jika tidak ganti)' : 'Set Password' }}
                        </label>
                        <input type="password" wire:model="password"
                            class="w-full px-5 py-3 rounded-2xl border dark:bg-black/40 bg-slate-50 dark:border-white/10 border-slate-200 text-sm font-bold dark:text-white text-slate-800 focus:ring-2 focus:ring-indigo-500/20">
                        @error('password') <span
                            class="text-rose-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div
                    class="dark:bg-white/[0.02] bg-slate-50 px-8 py-6 flex justify-end gap-3 border-t dark:border-white/5 border-slate-100">
                    <button wire:click="closeModal"
                        class="px-6 py-2.5 text-[10px] font-black uppercase tracking-widest dark:text-slate-400 text-slate-500 hover:text-rose-500 transition-colors">Batal</button>
                    <button wire:click="store"
                        class="px-8 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-indigo-600/20 transform active:scale-95 transition-all">Simpan
                        Akun</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>