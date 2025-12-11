<div class="space-y-6 font-jakarta">

    <div
        class="sticky top-0 z-40 backdrop-blur-md bg-indigo-50/90 p-4 rounded-b-2xl shadow-sm border-b border-indigo-200 transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 mb-6">
        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">

            <div class="flex items-center gap-4 w-full xl:w-auto">
                <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600 shadow-sm">
                    <i class="fas fa-box text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold text-indigo-900 tracking-tight">Master Produk</h1>
                    <p class="text-xs text-indigo-600 font-medium mt-0.5">Kelola katalog barang & stok.</p>
                </div>
                <div
                    class="hidden md:flex px-3 py-1 bg-white text-indigo-600 rounded-lg text-[10px] font-bold border border-indigo-100 items-center gap-2 shadow-sm">
                    <i class="fas fa-cubes"></i> {{ $produks->total() }} SKU
                </div>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap gap-2 items-center w-full xl:w-auto justify-end">
                <div class="relative w-full sm:w-48">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                        <i class="fas fa-search text-slate-400 text-xs"></i>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="pl-8 w-full border-white rounded-lg text-xs font-bold text-slate-700 focus:ring-indigo-500 py-2 shadow-sm placeholder-slate-400 transition-all"
                        placeholder="Cari Nama / SKU...">
                </div>

                <div class="relative w-full sm:w-36" x-data="{ open: false }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full flex items-center justify-between bg-white border-white text-slate-700 px-3 py-2 rounded-lg text-xs font-bold shadow-sm hover:bg-indigo-50 transition-all">
                        <span
                            class="truncate">{{ empty($filterCabang) ? 'Semua Cabang' : count($filterCabang).' Dipilih' }}</span>
                        <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform"
                            :class="{'rotate-180': open}"></i>
                    </button>
                    <div x-show="open" x-transition
                        class="absolute z-50 mt-1 w-full bg-white border border-slate-200 rounded-lg shadow-xl p-2 max-h-48 overflow-y-auto"
                        style="display: none;">
                        @foreach($optCabang as $c)
                        <label
                            class="flex items-center px-2 py-1.5 hover:bg-indigo-50 rounded cursor-pointer transition-colors">
                            <input type="checkbox" value="{{ $c }}" wire:model.live="filterCabang"
                                class="rounded border-slate-300 text-indigo-600 mr-2 h-3 w-3">
                            <span class="text-xs text-slate-600">{{ $c }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="relative w-full sm:w-48" x-data="{ 
                    open: false, 
                    search: '',
                    selected: @entangle('filterSupplier') 
                }">
                    <button @click="open = !open; $nextTick(() => $refs.searchInput.focus())"
                        @click.outside="open = false"
                        class="w-full flex items-center justify-between bg-white border border-slate-200 text-slate-700 px-3 py-2 rounded-lg text-xs font-bold shadow-sm hover:border-emerald-300 transition-all">
                        <span class="truncate" x-text="selected ? selected : 'Semua Supplier'"></span>
                        <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform"
                            :class="{'rotate-180': open}"></i>
                    </button>

                    <div x-show="open" x-transition
                        class="absolute z-50 mt-1 w-64 bg-white border border-slate-200 rounded-lg shadow-xl overflow-hidden p-2"
                        style="display: none;">

                        <div class="relative mb-2">
                            <i class="fas fa-search absolute left-2.5 top-2.5 text-slate-400 text-xs"></i>
                            <input x-ref="searchInput" x-model="search" type="text"
                                class="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-xs text-slate-700 focus:ring-emerald-500 focus:border-emerald-500 placeholder-slate-400"
                                placeholder="Cari Supplier...">
                        </div>

                        <div class="max-h-48 overflow-y-auto custom-scrollbar space-y-1">
                            <div @click="selected = ''; open = false; search = ''"
                                class="px-2 py-1.5 rounded-md cursor-pointer text-xs font-medium hover:bg-emerald-50 hover:text-emerald-700 flex items-center justify-between"
                                :class="selected === '' ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600'">
                                <span>Semua Supplier</span>
                                <i x-show="selected === ''" class="fas fa-check text-[10px]"></i>
                            </div>

                            @foreach($optSupplier as $s)
                            <div x-show="'{{ strtolower($s) }}'.includes(search.toLowerCase())"
                                @click="selected = '{{ $s }}'; open = false; search = ''"
                                class="px-2 py-1.5 rounded-md cursor-pointer text-xs font-medium hover:bg-emerald-50 hover:text-emerald-700 flex items-center justify-between transition-colors"
                                :class="selected === '{{ $s }}' ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600'">
                                <span class="truncate">{{ $s }}</span>
                                <i x-show="selected === '{{ $s }}'" class="fas fa-check text-[10px]"></i>
                            </div>
                            @endforeach

                            <div x-show="$el.querySelectorAll('div[x-show]').length === 0"
                                class="px-2 py-2 text-center text-xs text-slate-400 italic">
                                Tidak ditemukan
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hidden sm:block h-6 w-px bg-indigo-200 mx-1"></div>

                <button wire:click="openImportModal"
                    class="px-3 py-2 bg-indigo-600 text-white rounded-lg text-xs font-bold hover:bg-indigo-700 shadow-md shadow-indigo-500/20 transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
                    <i class="fas fa-file-excel"></i> <span class="hidden sm:inline">Import</span>
                </button>

                <div wire:loading class="text-indigo-600 ml-1"><i class="fas fa-circle-notch fa-spin"></i></div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col h-[85vh] overflow-hidden">
        <div class="overflow-auto flex-1 w-full custom-scrollbar">
            @include('livewire.master.partials.table-produk')
        </div>
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">{{ $produks->links() }}</div>
    </div>

    @if($isImportOpen)
    @include('livewire.partials.import-modal', ['title' => 'Import Data Produk', 'color' => 'indigo'])
    @endif

</div>