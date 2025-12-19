<div class="min-h-screen space-y-6 pb-10 transition-colors duration-300 font-jakarta" x-data="{ filterOpen: false }">

    <div class="sticky top-0 z-40 backdrop-blur-xl border-b transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 mb-6
        dark:bg-[#0a0a0a]/80 dark:border-white/5 bg-white/80 border-slate-200 shadow-sm">

        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">
            <div class="flex items-center gap-4 w-full xl:w-auto">
                <div class="p-2.5 rounded-xl shadow-lg dark:bg-pink-500/20 bg-pink-600 text-white dark:text-pink-400">
                    <i class="fas fa-truck text-xl"></i>
                </div>
                <div>
                    <h1
                        class="text-xl font-black tracking-tighter uppercase leading-none dark:text-white text-slate-800">
                        Master <span class="text-pink-500">Supplier</span>
                    </h1>
                    <p
                        class="text-[9px] font-bold uppercase tracking-[0.3em] opacity-50 mt-1.5 dark:text-slate-400 text-slate-500">
                        Vendor & PIC Database</p>
                </div>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap gap-3 items-center w-full xl:w-auto justify-end">

                <div class="relative w-full sm:w-48 group">
                    <i
                        class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-pink-500 transition-colors text-xs"></i>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="w-full pl-9 pr-4 py-2 rounded-xl border text-[11px] font-bold uppercase tracking-widest focus:ring-2 focus:ring-pink-500/20 transition-all
                        dark:bg-black/40 dark:border-white/10 dark:text-white bg-slate-100 border-slate-200 shadow-inner" placeholder="Cari Supplier / PIC...">
                </div>

                <div class="relative w-full sm:w-40" x-data="{ open: false, selected: @entangle('filterCabang').live }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full flex items-center justify-between border px-4 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm
                        dark:bg-black/40 dark:border-white/5 dark:text-slate-300 bg-white border-slate-200 text-slate-700 hover:border-pink-400">
                        <span class="truncate"
                            x-text="selected.length > 0 ? selected.length + ' Regional' : 'Regional Hub'"></span>
                        <i class="fas fa-chevron-down opacity-40 text-[10px] transition-transform"
                            :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" x-transition class="absolute z-50 mt-2 w-full border rounded-2xl shadow-2xl p-2 max-h-60 overflow-y-auto custom-scrollbar
                        dark:bg-slate-900 border-slate-800 bg-white border-slate-200" style="display: none;">
                        <div @click="selected = []"
                            class="px-3 py-2 text-[10px] text-rose-500 font-black uppercase tracking-widest cursor-pointer hover:bg-rose-500/10 rounded-xl mb-1 flex items-center gap-2">
                            <i class="fas fa-times-circle"></i> Reset Regional
                        </div>
                        @foreach($optCabang as $c)
                        <label
                            class="flex items-center px-3 py-2.5 hover:bg-pink-500/10 rounded-xl cursor-pointer transition-colors group">
                            <input type="checkbox" value="{{ $c }}" x-model="selected"
                                class="rounded-full border-slate-500 text-pink-600 focus:ring-pink-500 h-3.5 w-3.5">
                            <span
                                class="ml-3 text-[10px] font-bold uppercase tracking-tight group-hover:text-pink-400 dark:text-slate-400 text-slate-600">{{ $c }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <button wire:click="syncFromProducts" wire:loading.attr="disabled"
                    class="px-4 py-2 dark:bg-white/5 bg-white border dark:border-white/10 border-slate-200 dark:text-slate-300 text-slate-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-pink-500 hover:text-white transition-all shadow-sm">
                    <span wire:loading.remove wire:target="syncFromProducts"><i class="fas fa-sync-alt mr-1"></i>
                        Sync</span>
                    <span wire:loading wire:target="syncFromProducts"><i class="fas fa-spinner fa-spin"></i></span>
                </button>

                <button wire:click="create"
                    class="px-5 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-pink-600/20 transition-all transform active:scale-95">
                    <i class="fas fa-plus mr-1"></i> Baru
                </button>
            </div>
        </div>
    </div>

    <div wire:loading.class="opacity-50 pointer-events-none"
        class="transition-opacity duration-300 max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
        <div
            class="rounded-[2.5rem] border overflow-hidden transition-all duration-300
            dark:bg-slate-900/40 dark:border-white/5 bg-white border-slate-200 shadow-2xl shadow-slate-200/40 dark:shadow-black/40">

            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-sm text-left border-collapse uppercase">
                    <thead>
                        <tr
                            class="dark:bg-white/5 bg-slate-50 text-slate-500 dark:text-slate-400 font-black text-[10px] tracking-[0.15em] border-b dark:border-white/5 border-slate-100">
                            <th class="px-6 py-5 w-16 text-center">No</th>
                            <th class="px-6 py-5">Regional</th>
                            <th class="px-6 py-5">Nama Supplier</th>
                            <th class="px-6 py-5">PIC / Kontak</th>
                            <th class="px-6 py-5">Informasi Telepon</th>
                            <th
                                class="px-6 py-5 text-center bg-slate-100/50 dark:bg-white/5 border-l dark:border-white/5 border-slate-100">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-white/5 divide-slate-100">
                        @forelse($suppliers as $index => $item)
                        <tr class="hover:bg-pink-500/[0.02] transition-colors group">
                            <td class="px-6 py-4 text-center text-slate-400 font-mono text-[10px]">
                                {{ $suppliers->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2.5 py-1 rounded-lg text-[9px] font-black tracking-widest border dark:bg-indigo-500/10 dark:text-indigo-400 dark:border-indigo-500/20 bg-indigo-50 text-indigo-600 border-indigo-100 uppercase">
                                    {{ $item->cabang }}
                                </span>
                            </td>
                            <td
                                class="px-6 py-4 font-black dark:text-white text-slate-800 text-xs tracking-tight group-hover:text-pink-500 transition-colors">
                                {{ $item->supplier_name }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-full dark:bg-slate-800 bg-slate-100 flex items-center justify-center text-slate-400 group-hover:text-pink-500 transition-all shadow-inner">
                                        <i class="fas fa-user-circle text-sm"></i>
                                    </div>
                                    <span
                                        class="text-[10px] font-bold dark:text-slate-300 text-slate-600">{{ $item->contact_person ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                @if($item->phone)
                                <a href="tel:{{ $item->phone }}"
                                    class="inline-flex items-center gap-2 text-[10px] font-black text-slate-500 dark:text-slate-400 hover:text-emerald-500 transition-colors dark:bg-black/20 bg-slate-50 px-3 py-1.5 rounded-xl border dark:border-white/5 border-slate-100 group-hover:border-emerald-500/30 shadow-sm">
                                    <i class="fas fa-phone-alt opacity-40 text-[8px]"></i> {{ $item->phone }}
                                </a>
                                @else
                                <span class="text-[10px] font-bold opacity-30 italic">No Contact</span>
                                @endif
                            </td>
                            <td
                                class="px-6 py-4 text-center bg-slate-50/30 dark:bg-white/[0.01] border-l dark:border-white/5 border-slate-50">
                                <div
                                    class="flex justify-center gap-1.5 opacity-40 group-hover:opacity-100 transition-opacity">
                                    <button wire:click="edit({{ $item->id }})"
                                        class="w-8 h-8 rounded-lg dark:bg-white/5 bg-white border dark:border-white/5 border-slate-200 text-blue-500 hover:bg-blue-500 hover:text-white transition-all shadow-sm">
                                        <i class="fas fa-edit text-[10px]"></i>
                                    </button>
                                    <button wire:click="delete({{ $item->id }})"
                                        onclick="return confirm('Hapus?') || event.stopImmediatePropagation()"
                                        class="w-8 h-8 rounded-lg dark:bg-white/5 bg-white border dark:border-white/5 border-slate-200 text-rose-500 hover:bg-rose-500 hover:text-white transition-all shadow-sm">
                                        <i class="fas fa-trash-alt text-[10px]"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-24 text-center">
                                <div class="flex flex-col items-center gap-4 opacity-20">
                                    <i class="fas fa-truck-loading text-6xl"></i>
                                    <p class="text-xs font-black tracking-[0.4em]">Supplier Database Empty</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div
                class="px-6 py-5 border-t dark:border-white/5 border-slate-100 dark:bg-white/[0.02] bg-slate-50/50 uppercase font-black text-[10px]">
                {{ $suppliers->links() }}
            </div>
        </div>
    </div>

    @if($isOpen)
    <div class="fixed inset-0 z-[110] overflow-y-auto" role="dialog">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" wire:click="closeModal">
            </div>

            <div
                class="relative dark:bg-[#0a0a0a] bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden border dark:border-white/10 border-slate-200 transition-all">
                <div
                    class="bg-gradient-to-r from-pink-600 to-rose-600 px-8 py-6 text-white flex justify-between items-center shadow-lg">
                    <div>
                        <h3 class="font-black uppercase tracking-widest text-sm">
                            <i class="fas {{ $supplierId ? 'fa-edit' : 'fa-plus-circle' }} mr-2"></i>
                            {{ $supplierId ? 'Update Supplier' : 'Register Vendor' }}
                        </h3>
                        <p class="text-[9px] font-bold opacity-60 uppercase tracking-[0.2em] mt-1">Vendor Master
                            Database</p>
                    </div>
                    <button wire:click="closeModal"
                        class="w-8 h-8 rounded-full bg-black/10 flex items-center justify-center hover:bg-black/20 transition-all">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>

                <div class="p-8 space-y-6 font-jakarta">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label
                                class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Regional
                                Cabang</label>
                            <div class="relative group">
                                <span
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-pink-500 transition-colors"><i
                                        class="fas fa-map-marker-alt text-xs"></i></span>
                                <input type="text" wire:model="cabang"
                                    class="w-full pl-10 pr-5 py-3 rounded-2xl border dark:bg-black/40 bg-slate-50 dark:border-white/10 border-slate-200 text-sm font-bold focus:ring-2 focus:ring-pink-500/20 transition-all dark:text-white text-slate-800"
                                    placeholder="Contoh: Banjarmasin">
                            </div>
                            @error('cabang') <span
                                class="text-rose-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label
                                class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Nama
                                Perusahaan / Supplier</label>
                            <div class="relative group">
                                <span
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-pink-500 transition-colors"><i
                                        class="fas fa-building text-xs"></i></span>
                                <input type="text" wire:model="supplier_name"
                                    class="w-full pl-10 pr-5 py-3 rounded-2xl border dark:bg-black/40 bg-slate-50 dark:border-white/10 border-slate-200 text-sm font-black focus:ring-2 focus:ring-pink-500/20 transition-all dark:text-white text-slate-800"
                                    placeholder="PT. Nama Supplier Terdaftar">
                            </div>
                            @error('supplier_name') <span
                                class="text-rose-500 text-[10px] font-bold mt-1 ml-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">PIC
                                    / Kontak Utama</label>
                                <input type="text" wire:model="contact_person"
                                    class="w-full px-5 py-3 rounded-2xl border dark:bg-black/40 bg-slate-50 dark:border-white/10 border-slate-200 text-sm font-bold focus:ring-2 focus:ring-pink-500/20 transition-all dark:text-white text-slate-800"
                                    placeholder="Nama Personil">
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">No.
                                    WhatsApp / HP</label>
                                <input type="text" wire:model="phone"
                                    class="w-full px-5 py-3 rounded-2xl border dark:bg-black/40 bg-slate-50 dark:border-white/10 border-slate-200 text-sm font-mono font-bold focus:ring-2 focus:ring-pink-500/20 transition-all dark:text-white text-slate-800"
                                    placeholder="0812...">
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="dark:bg-white/[0.02] bg-slate-50 px-8 py-6 flex justify-end gap-3 border-t dark:border-white/5 border-slate-100">
                    <button wire:click="closeModal"
                        class="px-6 py-2.5 text-[10px] font-black uppercase tracking-widest dark:text-slate-400 text-slate-500 hover:text-rose-500 transition-colors">Batal</button>
                    <button wire:click="store"
                        class="px-8 py-2.5 bg-pink-600 hover:bg-pink-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-pink-600/20 transform active:scale-95 transition-all">Simpan
                        Database</button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
    height: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(236, 72, 153, 0.2);
    border-radius: 10px;
}
</style>