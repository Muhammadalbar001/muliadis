<div class="space-y-6 font-jakarta">

    <div
        class="sticky top-0 z-40 backdrop-blur-md bg-pink-50/90 p-4 rounded-b-2xl shadow-sm border-b border-pink-200 transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 mb-6">
        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">

            <div class="flex items-center gap-4 w-full xl:w-auto">
                <div class="p-2 bg-pink-100 rounded-lg text-pink-600 shadow-sm"><i class="fas fa-truck text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold text-pink-900 tracking-tight">Master Supplier</h1>
                    <p class="text-xs text-pink-600 font-medium mt-0.5">Database pemasok & kontak.</p>
                </div>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap gap-2 items-center w-full xl:w-auto justify-end">

                <div class="relative w-full sm:w-48">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                        <i class="fas fa-search text-slate-400 text-xs"></i>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="pl-8 w-full border-white rounded-lg text-xs font-bold text-slate-700 focus:ring-pink-500 py-2 shadow-sm placeholder-slate-400 transition-all"
                        placeholder="Cari Supplier / PIC...">
                </div>

                <div class="relative w-full sm:w-40" x-data="{ open: false, selected: @entangle('filterCabang').live }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full flex items-center justify-between bg-white border-white text-slate-700 px-3 py-2 rounded-lg text-xs font-bold shadow-sm hover:bg-pink-50 transition-all">
                        <span class="truncate"
                            x-text="selected.length > 0 ? selected.length + ' Cabang Dipilih' : 'Semua Cabang'"></span>
                        <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform"
                            :class="{'rotate-180': open}"></i>
                    </button>

                    <div x-show="open" x-transition
                        class="absolute z-50 mt-1 w-48 bg-white border border-slate-200 rounded-lg shadow-xl p-2 max-h-60 overflow-y-auto custom-scrollbar"
                        style="display: none;">
                        <div @click="selected = []"
                            class="px-2 py-1.5 text-xs text-rose-500 font-bold cursor-pointer hover:bg-rose-50 rounded mb-1 flex items-center gap-1">
                            <i class="fas fa-times-circle"></i> Reset Filter
                        </div>
                        @foreach($optCabang as $c)
                        <div @click="selected.includes('{{ $c }}') ? selected = selected.filter(i => i !== '{{ $c }}') : selected.push('{{ $c }}')"
                            class="flex items-center px-2 py-1.5 hover:bg-pink-50 rounded cursor-pointer transition-colors group">
                            <div class="w-4 h-4 rounded border flex items-center justify-center transition-colors mr-2"
                                :class="selected.includes('{{ $c }}') ? 'bg-pink-500 border-pink-500' : 'border-slate-300 bg-white group-hover:border-pink-400'">
                                <i x-show="selected.includes('{{ $c }}')"
                                    class="fas fa-check text-white text-[9px]"></i>
                            </div>
                            <span class="text-xs text-slate-600 truncate"
                                :class="selected.includes('{{ $c }}') ? 'font-bold text-pink-700' : ''">{{ $c }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="hidden sm:block h-6 w-px bg-pink-200 mx-1"></div>

                <button wire:click="syncFromProducts" wire:loading.attr="disabled"
                    class="px-3 py-2 bg-white border border-pink-200 text-pink-600 rounded-lg text-xs font-bold hover:bg-pink-50 shadow-sm transition-all flex items-center gap-2 disabled:opacity-50"
                    title="Sync dari Produk">
                    <span wire:loading.remove wire:target="syncFromProducts"><i class="fas fa-sync-alt"></i> Sync</span>
                    <span wire:loading wire:target="syncFromProducts"><i class="fas fa-spinner fa-spin"></i></span>
                </button>

                <button wire:click="create"
                    class="px-3 py-2 bg-pink-600 text-white rounded-lg text-xs font-bold hover:bg-pink-700 shadow-md shadow-pink-500/20 transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
                    <i class="fas fa-plus"></i> <span class="hidden sm:inline">Baru</span>
                </button>

                <!-- <div wire:loading class="text-pink-600 ml-1"><i class="fas fa-circle-notch fa-spin"></i></div> -->
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col h-[85vh] overflow-hidden">
        <div class="overflow-auto flex-1 w-full custom-scrollbar">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200 sticky top-0 z-10 shadow-sm">
                    <tr>
                        <th
                            class="px-6 py-4 w-16 text-center font-bold text-slate-600 text-xs uppercase tracking-wider">
                            No</th>
                        <th class="px-6 py-4 font-bold text-slate-600 text-xs uppercase tracking-wider">Cabang</th>
                        <th class="px-6 py-4 font-bold text-slate-600 text-xs uppercase tracking-wider">Nama Supplier
                        </th>
                        <th class="px-6 py-4 font-bold text-slate-600 text-xs uppercase tracking-wider">Kontak (PIC)
                        </th>
                        <th class="px-6 py-4 font-bold text-slate-600 text-xs uppercase tracking-wider">Telepon</th>
                        <th class="px-6 py-4 text-center font-bold text-slate-600 text-xs uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($suppliers as $index => $item)
                    <tr class="hover:bg-pink-50/20 transition-colors group">
                        <td class="px-6 py-4 text-center text-slate-500 text-xs font-mono">
                            {{ $suppliers->firstItem() + $index }}</td>
                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-600 border border-indigo-100">{{ $item->cabang }}</span>
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-700 group-hover:text-pink-600 transition-colors">
                            {{ $item->supplier_name }}</td>
                        <td class="px-6 py-4 text-slate-600">
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 text-xs group-hover:bg-white group-hover:text-pink-400 transition-colors">
                                    <i class="fas fa-user"></i></div>
                                <span class="font-medium">{{ $item->contact_person ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            @if($item->phone)
                            <a href="tel:{{ $item->phone }}"
                                class="inline-flex items-center gap-1.5 text-slate-600 hover:text-emerald-600 font-medium transition-colors bg-slate-50 px-2 py-1 rounded-lg border border-slate-100 group-hover:border-emerald-200">
                                <i class="fas fa-phone text-xs text-slate-400"></i> {{ $item->phone }}
                            </a>
                            @else
                            <span class="text-slate-400 text-xs italic">Tidak ada no.</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div
                                class="flex justify-center gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                <button wire:click="edit({{ $item->id }})"
                                    class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all"><i
                                        class="fas fa-edit"></i></button>
                                <button wire:click="delete({{ $item->id }})"
                                    onclick="return confirm('Hapus?') || event.stopImmediatePropagation()"
                                    class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-all"><i
                                        class="fas fa-trash-alt"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center text-slate-400">Tidak ada data supplier.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">{{ $suppliers->links() }}</div>
    </div>

    @if($isOpen)
    <div class="fixed inset-0 z-[60] overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm transition-opacity" wire:click="closeModal">
            </div>
            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-white/20">
                <div
                    class="bg-gradient-to-r from-pink-600 to-rose-600 px-6 py-4 border-b border-white/10 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="fas {{ $supplierId ? 'fa-edit' : 'fa-plus-circle' }}"></i>
                        {{ $supplierId ? 'Edit Supplier' : 'Supplier Baru' }}
                    </h3>
                    <button wire:click="closeModal" class="text-white/70 hover:text-white transition-colors"><i
                            class="fas fa-times"></i></button>
                </div>
                <div class="px-6 py-6 space-y-5">
                    <div>
                        <label
                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5 ml-1">Cabang</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400"><i
                                    class="fas fa-map-marker-alt"></i></span>
                            <input type="text" wire:model="cabang"
                                class="w-full pl-10 pr-4 py-2.5 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-500 focus:border-pink-500 placeholder-slate-400 font-medium transition-all"
                                placeholder="Nama Cabang">
                        </div>
                        @error('cabang') <span class="text-rose-500 text-xs font-bold mt-1 ml-1">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5 ml-1">Nama
                            Supplier</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400"><i
                                    class="fas fa-truck"></i></span>
                            <input type="text" wire:model="supplier_name"
                                class="w-full pl-10 pr-4 py-2.5 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-500 focus:border-pink-500 placeholder-slate-400 font-bold text-slate-700 transition-all"
                                placeholder="PT. Nama Supplier">
                        </div>
                        @error('supplier_name') <span
                            class="text-rose-500 text-xs font-bold mt-1 ml-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5 ml-1">PIC
                                / Kontak</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400"><i
                                        class="fas fa-user"></i></span>
                                <input type="text" wire:model="contact_person"
                                    class="w-full pl-10 pr-4 py-2.5 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-500 focus:border-pink-500 placeholder-slate-400 transition-all"
                                    placeholder="Nama PIC">
                            </div>
                        </div>
                        <div>
                            <label
                                class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5 ml-1">No
                                HP / Telp</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400"><i
                                        class="fas fa-phone"></i></span>
                                <input type="text" wire:model="phone"
                                    class="w-full pl-10 pr-4 py-2.5 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-pink-500 focus:border-pink-500 placeholder-slate-400 transition-all"
                                    placeholder="08...">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex flex-row-reverse gap-3 border-t border-slate-200">
                    <button wire:click="store"
                        class="px-5 py-2.5 bg-pink-600 text-white rounded-xl text-sm font-bold hover:bg-pink-700 focus:outline-none shadow-lg shadow-pink-500/30 transition-all transform hover:-translate-y-0.5">{{ $supplierId ? 'Simpan Perubahan' : 'Simpan Data' }}</button>
                    <button wire:click="closeModal"
                        class="px-5 py-2.5 bg-white text-slate-700 border border-slate-300 rounded-xl text-sm font-bold hover:bg-slate-50 focus:outline-none transition-all">Batal</button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>