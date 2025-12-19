<div class="min-h-screen space-y-6 pb-10 transition-colors duration-300 font-jakarta" x-data="{ filterOpen: false }">

    <div class="sticky top-0 z-40 backdrop-blur-xl border-b transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 mb-6
        dark:bg-[#0a0a0a]/80 dark:border-white/5 bg-white/80 border-slate-200 shadow-sm">

        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">
            <div class="flex items-center gap-4 w-full xl:w-auto">
                <div class="p-2.5 rounded-xl shadow-lg dark:bg-blue-500/20 bg-blue-600 text-white dark:text-blue-400">
                    <i class="fas fa-user-tie text-xl"></i>
                </div>
                <div>
                    <h1
                        class="text-xl font-black tracking-tighter uppercase leading-none dark:text-white text-slate-800">
                        Master <span class="text-blue-500">Salesman</span></h1>
                    <p
                        class="text-[9px] font-bold uppercase tracking-[0.3em] opacity-50 mt-1.5 dark:text-slate-400 text-slate-500">
                        Personnel & Target Management</p>
                </div>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap gap-3 items-center w-full xl:w-auto justify-end">
                <div class="relative w-full sm:w-48 group">
                    <i
                        class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-500 transition-colors text-xs"></i>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="w-full pl-9 pr-4 py-2 rounded-xl border text-[11px] font-bold uppercase tracking-widest focus:ring-2 focus:ring-blue-500/20 transition-all
                        dark:bg-black/40 dark:border-white/10 dark:text-white bg-slate-100 border-slate-200 shadow-inner" placeholder="Cari Sales...">
                </div>

                <div class="flex items-center gap-2">
                    <button wire:click="syncCodes"
                        class="px-4 py-2 dark:bg-white/5 bg-white border dark:border-white/10 border-slate-200 dark:text-slate-300 text-slate-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-500 hover:text-white transition-all shadow-sm">
                        <i class="fas fa-sync mr-1" wire:loading.class="fa-spin" wire:target="syncCodes"></i> Sync
                    </button>
                    <button wire:click="openImportModal"
                        class="px-4 py-2 dark:bg-white/5 bg-white border dark:border-white/10 border-slate-200 dark:text-slate-300 text-slate-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-500 hover:text-white transition-all shadow-sm">
                        <i class="fas fa-file-import mr-1"></i> Import
                    </button>
                    <button wire:click="create"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-blue-600/20 transition-all transform active:scale-95">
                        <i class="fas fa-plus mr-1"></i> Baru
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div wire:loading.class="opacity-50 pointer-events-none"
        class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 transition-opacity duration-300">
        <div
            class="rounded-[2.5rem] border overflow-hidden transition-all duration-300 dark:bg-slate-900/40 dark:border-white/5 bg-white border-slate-200 shadow-2xl">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-sm text-left border-collapse uppercase font-jakarta">
                    <thead>
                        <tr
                            class="dark:bg-white/5 bg-slate-50 text-slate-500 dark:text-slate-400 font-black text-[10px] tracking-[0.15em] border-b dark:border-white/5 border-slate-100">
                            <th
                                class="px-6 py-5 border-r dark:border-white/5 border-slate-100 text-indigo-500 bg-indigo-50/10 w-32">
                                Kode</th>
                            <th class="px-6 py-5">Nama Salesman</th>
                            <th class="px-6 py-5">Regional/Kota</th>
                            <th class="px-6 py-5 text-center">Status</th>
                            <th
                                class="px-6 py-5 text-center bg-slate-100/50 dark:bg-white/5 border-l dark:border-white/5 border-slate-100">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-white/5 divide-slate-100">
                        @forelse($sales as $item)
                        <tr class="hover:bg-blue-500/[0.02] transition-colors group">
                            <td
                                class="px-6 py-4 font-mono font-bold text-indigo-500 bg-indigo-50/[0.02] border-r dark:border-white/5 border-slate-50 text-xs">
                                {{ $item->sales_code ?: '-' }}</td>
                            <td class="px-6 py-4 font-black dark:text-white text-slate-800 text-xs tracking-tight">
                                {{ $item->sales_name }}</td>
                            <td class="px-6 py-4 text-[10px] font-bold dark:text-slate-400 text-slate-500">
                                {{ $item->city ?: '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="px-3 py-1 rounded-full text-[9px] font-black tracking-widest border {{ $item->status == 'Active' ? 'dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20 bg-emerald-50 text-emerald-600 border-emerald-100' : 'dark:bg-slate-800 dark:text-slate-500 dark:border-white/5 bg-slate-50 text-slate-400 border-slate-100' }}">
                                    {{ strtoupper($item->status) }}
                                </span>
                            </td>
                            <td
                                class="px-6 py-4 text-center bg-slate-50/30 dark:bg-white/[0.01] border-l dark:border-white/5 border-slate-50">
                                <div
                                    class="flex justify-center gap-1.5 opacity-40 group-hover:opacity-100 transition-opacity">
                                    <button wire:click="edit({{ $item->id }})"
                                        class="w-8 h-8 rounded-lg dark:bg-white/5 bg-white border border-slate-200 text-blue-500 hover:bg-blue-500 hover:text-white transition-all shadow-sm"><i
                                            class="fas fa-edit text-[10px]"></i></button>
                                    <button wire:click="delete({{ $item->id }})"
                                        class="w-8 h-8 rounded-lg dark:bg-white/5 bg-white border border-slate-200 text-rose-500 hover:bg-rose-500 hover:text-white transition-all shadow-sm"><i
                                            class="fas fa-trash-alt text-[10px]"></i></button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center opacity-20">
                                <p class="text-xs font-black tracking-[0.4em]">Database Kosong</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div
                class="px-6 py-5 border-t dark:border-white/5 border-slate-100 dark:bg-white/[0.02] bg-slate-50/50 uppercase font-black text-[10px]">
                {{ $sales->links() }}</div>
        </div>
    </div>

    @if($isImportOpen)
    <div class="fixed inset-0 z-[150] overflow-y-auto" role="dialog">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-slate-900/90 backdrop-blur-md transition-opacity"
                wire:click="closeImportModal"></div>
            <div
                class="relative dark:bg-[#0a0a0a] bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden border dark:border-white/10 border-slate-200">
                <div class="bg-emerald-600 px-8 py-6 text-white flex justify-between items-center shadow-lg">
                    <div>
                        <h3 class="font-black uppercase tracking-widest text-sm">Import Salesman</h3>
                        <p class="text-[9px] font-bold opacity-60 uppercase tracking-[0.2em] mt-1">Excel / CSV Data
                            Source</p>
                    </div>
                    <button wire:click="closeImportModal"
                        class="w-8 h-8 rounded-full bg-black/10 flex items-center justify-center hover:bg-black/20"><i
                            class="fas fa-times text-xs"></i></button>
                </div>
                <div class="p-8 space-y-6">
                    <div
                        class="border-2 border-dashed dark:border-white/10 border-slate-200 rounded-[2rem] p-10 text-center group hover:border-emerald-500/50 transition-all relative">
                        <input type="file" wire:model="file"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <div class="space-y-3">
                            <i
                                class="fas fa-cloud-upload-alt text-4xl dark:text-slate-700 text-slate-300 group-hover:text-emerald-500 transition-colors"></i>
                            <p class="text-xs font-black uppercase tracking-widest dark:text-slate-400 text-slate-500">
                                {{ $file ? $file->getClientOriginalName() : 'Klik atau seret file ke sini' }}
                            </p>
                        </div>
                    </div>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" wire:model="resetData"
                            class="w-5 h-5 rounded-lg border-slate-300 text-emerald-600 focus:ring-emerald-500/20">
                        <span class="text-[10px] font-black uppercase tracking-widest text-rose-500">Kosongkan database
                            sebelum import</span>
                    </label>
                </div>
                <div
                    class="dark:bg-white/[0.02] bg-slate-50 px-8 py-6 flex justify-end gap-3 border-t dark:border-white/5 border-slate-100">
                    <button wire:click="closeImportModal"
                        class="px-6 py-2.5 text-[10px] font-black uppercase tracking-widest text-slate-400">Batal</button>
                    <button wire:click="import" wire:loading.attr="disabled"
                        class="px-8 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-emerald-600/20">
                        <span wire:loading.remove wire:target="import">Proses Import</span>
                        <span wire:loading wire:target="import"><i
                                class="fas fa-spinner fa-spin mr-2"></i>Mendata...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>