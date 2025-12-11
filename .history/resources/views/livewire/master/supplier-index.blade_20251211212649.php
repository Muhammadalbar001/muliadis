<div class="space-y-6 font-jakarta">

    <div
        class="sticky top-0 z-40 backdrop-blur-md bg-pink-50/90 p-4 rounded-b-2xl shadow-sm border-b border-pink-200 transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 mb-6">
        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">

            <div class="flex items-center gap-4 w-full xl:w-auto">
                <div class="p-2 bg-pink-100 rounded-lg text-pink-600 shadow-sm">
                    <i class="fas fa-truck text-xl"></i>
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

                <div class="w-full sm:w-40">
                    <select wire:model.live="filterCabang"
                        class="w-full border-white rounded-lg text-xs font-bold text-slate-700 focus:ring-pink-500 py-2 shadow-sm cursor-pointer bg-white hover:bg-pink-50 transition-colors">
                        <option value="">Semua Cabang</option>
                        @foreach($optCabang as $c) <option value="{{ $c }}">{{ $c }}</option> @endforeach
                    </select>
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

                <div wire:loading class="text-pink-600 ml-1"><i class="fas fa-circle-notch fa-spin"></i></div>
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
    @endif

</div>