<div class="space-y-6 font-jakarta">

    <div
        class="sticky top-0 z-40 backdrop-blur-md bg-cyan-50/90 p-4 rounded-b-2xl shadow-sm border-b border-cyan-200 transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 mb-6">
        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">

            <div class="flex items-center gap-4 w-full xl:w-auto">
                <div class="p-2 bg-cyan-100 rounded-lg text-cyan-600 shadow-sm">
                    <i class="fas fa-hand-holding-usd text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-extrabold text-cyan-900 tracking-tight">Collection</h1>
                    <p class="text-xs text-cyan-700 font-medium mt-0.5">Data pembayaran pelanggan.</p>
                </div>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap gap-2 items-center w-full xl:w-auto justify-end">

                <div class="relative w-full sm:w-48">
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="pl-3 w-full border-white rounded-lg text-xs font-bold text-slate-700 focus:ring-cyan-500 py-2 shadow-sm placeholder-slate-400"
                        placeholder="No Bukti / Pelanggan...">
                </div>

                <div class="relative w-full sm:w-40" x-data="{ open: false, selected: @entangle('filterCabang').live }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full flex items-center justify-between bg-white border-white text-slate-700 px-3 py-2 rounded-lg text-xs font-bold shadow-sm hover:bg-cyan-50 transition-all">
                        <span class="truncate"
                            x-text="selected.length > 0 ? selected.length + ' Cabang' : 'Semua Cabang'"></span>
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
                            class="flex items-center px-2 py-1.5 hover:bg-cyan-50 rounded cursor-pointer transition-colors group">
                            <div class="w-4 h-4 rounded border flex items-center justify-center transition-colors mr-2"
                                :class="selected.includes('{{ $c }}') ? 'bg-cyan-500 border-cyan-500' : 'border-slate-300 bg-white group-hover:border-cyan-400'">
                                <i x-show="selected.includes('{{ $c }}')"
                                    class="fas fa-check text-white text-[9px]"></i>
                            </div>
                            <span class="text-xs text-slate-600 truncate"
                                :class="selected.includes('{{ $c }}') ? 'font-bold text-cyan-700' : ''">{{ $c }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="w-full sm:w-32">
                    <select wire:model.live="filterPenagih"
                        class="w-full border-white rounded-lg text-xs font-bold text-slate-700 focus:ring-cyan-500 py-2 shadow-sm cursor-pointer bg-white hover:bg-cyan-50">
                        <option value="">Semua Penagih</option>
                        @foreach($optPenagih as $p) <option value="{{ $p }}">{{ $p }}</option> @endforeach
                    </select>
                </div>

                <div class="hidden sm:block h-6 w-px bg-cyan-200 mx-1"></div>

                <button wire:click="resetFilter"
                    class="px-3 py-2 bg-white border border-cyan-200 text-cyan-600 rounded-lg text-xs font-bold hover:bg-cyan-50 shadow-sm"
                    title="Reset Filter"><i class="fas fa-undo"></i></button>

                <div class="flex items-center gap-1.5 p-1.5 bg-rose-50 border border-rose-100 rounded-lg shadow-sm">
                    <div class="hidden lg:block text-[9px] font-black text-rose-700 uppercase px-1">Hapus Periode:</div>
                    <input type="date" wire:model="deleteStartDate"
                        class="text-[10px] rounded border-rose-200 py-1 px-1.5 focus:ring-rose-500 bg-white font-bold text-slate-700">
                    <span class="text-rose-300 text-[10px] font-bold">s/d</span>
                    <input type="date" wire:model="deleteEndDate"
                        class="text-[10px] rounded border-rose-200 py-1 px-1.5 focus:ring-rose-500 bg-white font-bold text-slate-700">
                    <button
                        onclick="confirm('PERINGATAN: Semua data Pelunasan/Collection di periode ini akan dihapus permanen. Lanjutkan?') || event.stopImmediatePropagation()"
                        wire:click="deleteByPeriod"
                        class="px-2.5 py-1 bg-rose-600 text-white text-[10px] font-black rounded hover:bg-rose-700 shadow-sm flex items-center gap-1">
                        <i class="fas fa-trash-alt"></i> <span class="hidden sm:inline">HAPUS</span>
                    </button>
                </div>

                <button wire:click="openImportModal"
                    class="px-3 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white rounded-lg text-xs font-bold hover:from-cyan-700 hover:to-blue-700 shadow-md shadow-cyan-500/20 flex items-center gap-2">
                    <i class="fas fa-file-import"></i> <span class="hidden sm:inline">Import</span>
                </button>

                <div wire:loading
                    class="px-3 py-2 bg-white border border-cyan-200 text-cyan-600 rounded-lg shadow-sm flex items-center justify-center animate-pulse">
                    <i class="fas fa-circle-notch fa-spin"></i>
                </div>

            </div>
        </div>
    </div>

    <div wire:loading.class="opacity-50 pointer-events-none" class="transition-opacity duration-200">
        @if(isset($summary))
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
            <div
                class="bg-gradient-to-br from-cyan-500 to-blue-600 rounded-2xl p-4 text-white shadow-sm shadow-cyan-500/20 relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-cyan-100 text-[10px] font-bold uppercase tracking-wider mb-0.5">Total Uang Masuk</p>
                    <h3 class="text-xl font-extrabold tracking-tight">Rp
                        {{ number_format($summary['total_cair'], 0, ',', '.') }}</h3>
                </div>
                <i class="fas fa-money-bill-wave absolute right-3 top-3 text-white/20 text-5xl rotate-12"></i>
            </div>
            <div
                class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm flex items-center justify-between group hover:border-cyan-300 transition-colors">
                <div>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-0.5">Bukti Potong</p>
                    <h3 class="text-xl font-extrabold text-slate-800">
                        {{ number_format($summary['total_bukti'], 0, ',', '.') }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600"><i
                        class="fas fa-receipt text-lg"></i></div>
            </div>
            <div
                class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm flex items-center justify-between group hover:border-green-300 transition-colors">
                <div>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-0.5">Faktur Lunas</p>
                    <h3 class="text-xl font-extrabold text-slate-800">
                        {{ number_format($summary['total_faktur'], 0, ',', '.') }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center text-green-600"><i
                        class="fas fa-check-double text-lg"></i></div>
            </div>
        </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col h-[85vh] overflow-hidden">
            <div class="overflow-auto flex-1 w-full custom-scrollbar">
                <table class="text-xs text-left border-collapse whitespace-nowrap w-full">
                    <thead class="bg-slate-50 border-b border-slate-200 sticky top-0 z-10 shadow-sm">
                        <tr>
                            <th class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200">Tgl Bayar
                            </th>
                            <th class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200">No Bukti
                            </th>
                            <th
                                class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200 min-w-[200px]">
                                Pelanggan</th>
                            <th class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200">No
                                Invoice</th>
                            <th class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200">Penagih
                            </th>
                            <th
                                class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200 text-center">
                                Cabang</th>
                            <th
                                class="px-6 py-4 font-bold text-cyan-700 uppercase border-r border-slate-200 text-right bg-cyan-50/50">
                                Jml Bayar</th>
                            <th
                                class="px-6 py-4 font-bold text-slate-500 uppercase text-center bg-slate-50 sticky right-0 z-20 border-l border-slate-200">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($collections as $item)
                        <tr class="hover:bg-cyan-50/20 transition-colors group">
                            <td class="px-6 py-3 border-r border-slate-100 text-slate-600 font-medium">
                                {{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
                            <td class="px-6 py-3 border-r border-slate-100 font-mono text-cyan-700 font-bold">
                                {{ $item->receive_no }}</td>
                            <td class="px-6 py-3 border-r border-slate-100 font-bold text-slate-700 truncate max-w-[200px]"
                                title="{{ $item->outlet_name }}">{{ $item->outlet_name }}</td>
                            <td class="px-6 py-3 border-r border-slate-100 font-mono text-slate-600">
                                {{ $item->invoice_no }}</td>
                            <td class="px-6 py-3 border-r border-slate-100 text-slate-500">{{ $item->penagih ?: '-' }}
                            </td>
                            <td class="px-6 py-3 border-r border-slate-100 text-center">
                                <span
                                    class="px-2 py-0.5 rounded-full bg-slate-100 border border-slate-200 text-[10px] font-bold text-slate-600">{{ $item->cabang }}</span>
                            </td>
                            <td
                                class="px-6 py-3 border-r border-slate-100 text-right font-extrabold text-slate-800 bg-cyan-50/10">
                                {{ number_format($item->receive_amount, 0, ',', '.') }}</td>
                            <td
                                class="px-6 py-3 text-center sticky right-0 bg-white border-l border-slate-100 z-10 group-hover:bg-cyan-50/40">
                                <button wire:click="delete({{ $item->id }})"
                                    onclick="return confirm('Hapus pelunasan ini?') || event.stopImmediatePropagation()"
                                    class="text-slate-300 hover:text-red-500 transition-colors"><i
                                        class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-24 text-center text-slate-400 italic">Data pelunasan tidak
                                ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">{{ $collections->links() }}</div>
        </div>
    </div>

    @if($isImportOpen) @include('livewire.partials.import-modal', ['title' => 'Import Collection', 'color' => 'cyan'])
    @endif

</div>