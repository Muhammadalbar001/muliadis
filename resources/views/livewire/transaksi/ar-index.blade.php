<div class="space-y-6 font-jakarta">

    <div
        class="sticky top-0 z-40 backdrop-blur-md bg-orange-50/90 p-4 rounded-b-2xl shadow-sm border-b border-orange-200 transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 mb-6">
        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">

            <div class="flex items-center gap-4 w-full xl:w-auto">
                <div class="p-2 bg-orange-100 rounded-lg text-orange-600"><i
                        class="fas fa-file-invoice-dollar text-xl"></i></div>
                <div>
                    <h1 class="text-xl font-extrabold text-orange-900 tracking-tight">Monitoring Piutang</h1>
                    <p class="text-xs text-orange-600 font-medium mt-0.5">Daftar tagihan outstanding.</p>
                </div>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap gap-2 items-center w-full xl:w-auto justify-end">

                <div class="relative w-full sm:w-48">
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="pl-3 w-full border-white rounded-lg text-xs font-bold text-slate-700 focus:ring-orange-500 py-2 shadow-sm placeholder-slate-400"
                        placeholder="No Invoice / Pelanggan...">
                </div>

                <div class="relative w-full sm:w-40" x-data="{ open: false, selected: @entangle('filterCabang').live }">
                    <button @click="open = !open" @click.outside="open = false"
                        class="w-full flex items-center justify-between bg-white border-white text-slate-700 px-3 py-2 rounded-lg text-xs font-bold shadow-sm hover:bg-orange-50 transition-all">
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
                            class="flex items-center px-2 py-1.5 hover:bg-orange-50 rounded cursor-pointer transition-colors group">
                            <div class="w-4 h-4 rounded border flex items-center justify-center transition-colors mr-2"
                                :class="selected.includes('{{ $c }}') ? 'bg-orange-500 border-orange-500' : 'border-slate-300 bg-white group-hover:border-orange-400'">
                                <i x-show="selected.includes('{{ $c }}')"
                                    class="fas fa-check text-white text-[9px]"></i>
                            </div>
                            <span class="text-xs text-slate-600 truncate"
                                :class="selected.includes('{{ $c }}') ? 'font-bold text-orange-700' : ''">{{ $c }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="w-full sm:w-32">
                    <select wire:model.live="filterUmur"
                        class="w-full border-white rounded-lg text-xs font-bold text-slate-700 focus:ring-orange-500 py-2 shadow-sm cursor-pointer bg-white hover:bg-orange-50">
                        <option value="">Semua Status</option>
                        <option value="lancar">Lancar (<=30)< /option>
                        <option value="macet">Macet (>30)</option>
                    </select>
                </div>

                <div class="hidden sm:block h-6 w-px bg-orange-200 mx-1"></div>

                <button wire:click="resetFilter"
                    class="px-3 py-2 bg-white border border-orange-200 text-orange-600 rounded-lg text-xs font-bold hover:bg-orange-50 shadow-sm"
                    title="Reset"><i class="fas fa-undo"></i></button>

                <button wire:click="openDeleteDateModal"
                    class="px-3 py-2 bg-white border border-rose-200 text-rose-600 rounded-lg text-xs font-bold hover:bg-rose-50 shadow-sm flex items-center gap-2"
                    title="Hapus per Tanggal">
                    <i class="fas fa-trash-alt"></i> <span class="hidden xl:inline">Hapus Tgl</span>
                </button>

                <button wire:click="openImportModal"
                    class="px-3 py-2 bg-gradient-to-r from-orange-500 to-amber-600 text-white rounded-lg text-xs font-bold hover:from-orange-600 hover:to-amber-700 shadow-md shadow-orange-500/20 flex items-center gap-2">
                    <i class="fas fa-file-invoice-dollar"></i> <span class="hidden sm:inline">Import</span>
                </button>

                <div wire:loading
                    class="px-3 py-2 bg-white border border-orange-200 text-orange-600 rounded-lg shadow-sm flex items-center justify-center animate-pulse">
                    <i class="fas fa-circle-notch fa-spin"></i>
                </div>

            </div>
        </div>
    </div>

    <div wire:loading.class="opacity-50 pointer-events-none" class="transition-opacity duration-200">
        @if(isset($summary))
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-2">
            <div
                class="bg-gradient-to-br from-orange-500 to-amber-600 rounded-2xl p-4 text-white shadow-sm shadow-orange-500/20 relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-orange-100 text-[10px] font-bold uppercase tracking-wider mb-0.5">Total Sisa Piutang
                    </p>
                    <h3 class="text-xl font-extrabold tracking-tight">Rp
                        {{ number_format($summary['total_piutang'], 0, ',', '.') }}</h3>
                </div>
                <i class="fas fa-hand-holding-usd absolute right-3 top-3 text-white/20 text-5xl rotate-12"></i>
            </div>
            <div
                class="bg-white rounded-2xl p-4 border border-red-100 shadow-sm flex items-center justify-between group hover:border-red-300">
                <div>
                    <p class="text-red-500 text-[10px] font-bold uppercase tracking-wider mb-0.5">Macet (>30 Hari)</p>
                    <h3 class="text-xl font-extrabold text-red-600">Rp
                        {{ number_format($summary['total_macet'], 0, ',', '.') }}</h3>
                    <span
                        class="text-[9px] text-red-500 font-bold bg-red-50 px-1.5 py-0.5 rounded border border-red-100">Perlu
                        Follow Up</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-red-500"><i
                        class="fas fa-bell text-lg"></i></div>
            </div>
            <div
                class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm flex items-center justify-between group hover:border-orange-300">
                <div>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-0.5">Outstanding Inv</p>
                    <h3 class="text-xl font-extrabold text-slate-800">
                        {{ number_format($summary['total_faktur'], 0, ',', '.') }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500"><i
                        class="fas fa-file-invoice text-lg"></i></div>
            </div>
        </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col h-[85vh] overflow-hidden">
            <div class="overflow-auto flex-1 w-full custom-scrollbar">
                <table class="text-xs text-left border-collapse whitespace-nowrap w-full">
                    <thead class="bg-slate-50 border-b border-slate-200 sticky top-0 z-10 shadow-sm">
                        <tr>
                            <th class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200">Tgl
                                Faktur
                            </th>
                            <th class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200">No
                                Invoice
                            </th>
                            <th
                                class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200 min-w-[200px]">
                                Pelanggan</th>
                            <th class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200">Salesman
                            </th>
                            <th class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200">Jatuh
                                Tempo
                            </th>
                            <th
                                class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200 text-center">
                                Umur</th>
                            <th
                                class="px-6 py-4 font-bold text-orange-700 uppercase border-r border-slate-200 text-right bg-orange-50/50">
                                Sisa Piutang</th>
                            <th
                                class="px-6 py-4 font-bold text-slate-500 uppercase text-center bg-slate-50 sticky right-0 z-20 border-l border-slate-200">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse($ars as $item)
                        <tr class="hover:bg-orange-50/20 transition-colors group">
                            <td class="px-6 py-3 border-r border-slate-100 text-slate-600 font-medium">
                                {{ date('d/m/Y', strtotime($item->tgl_penjualan)) }}</td>
                            <td class="px-6 py-3 border-r border-slate-100 font-mono text-orange-700 font-bold">
                                {{ $item->no_penjualan }}</td>
                            <td class="px-6 py-3 border-r border-slate-100 font-bold text-slate-700 truncate max-w-[250px]"
                                title="{{ $item->pelanggan_name }}">{{ $item->pelanggan_name }}</td>
                            <td class="px-6 py-3 border-r border-slate-100 text-slate-500">{{ $item->sales_name }}</td>
                            <td class="px-6 py-3 border-r border-slate-100 text-slate-600">
                                {{ $item->jatuh_tempo ? date('d/m/Y', strtotime($item->jatuh_tempo)) : '-' }}</td>
                            <td class="px-6 py-3 border-r border-slate-100 text-center">
                                @php $umur = $item->umur_piutang; @endphp
                                @if($umur > 30) <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-600 border border-red-200 shadow-sm">{{ $umur }}
                                    Hari</span>
                                @elseif($umur > 15) <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-600 border border-amber-200">{{ $umur }}
                                    Hari</span>
                                @else <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-600 border border-emerald-200">{{ $umur }}
                                    Hari</span>
                                @endif
                            </td>
                            <td
                                class="px-6 py-3 border-r border-slate-100 text-right font-extrabold text-slate-800 bg-orange-50/10">
                                {{ number_format($item->nilai, 0, ',', '.') }}</td>
                            <td
                                class="px-6 py-3 text-center sticky right-0 bg-white border-l border-slate-100 z-10 group-hover:bg-orange-50/40">
                                <button wire:click="delete({{ $item->id }})"
                                    onclick="return confirm('Hapus?') || event.stopImmediatePropagation()"
                                    class="text-slate-300 hover:text-red-500"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-24 text-center text-slate-400">Tidak ada data.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">{{ $ars->links() }}</div>
        </div>
    </div>

    @if($isImportOpen) @include('livewire.partials.import-modal', ['title' => 'Import AR', 'color' => 'orange']) @endif

    @if($isDeleteDateOpen)
    <div class="fixed inset-0 z-[70] overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity"
                wire:click="closeDeleteDateModal"></div>
            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full border border-white/20">
                <div class="bg-rose-50 px-6 py-4 border-b border-rose-100 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center text-rose-600"><i
                            class="fas fa-exclamation-triangle text-lg"></i></div>
                    <div>
                        <h3 class="text-lg font-bold text-rose-700">Hapus Data Harian</h3>
                        <p class="text-rose-500 text-xs">Piutang per tanggal faktur akan dihapus.</p>
                    </div>
                </div>
                <div class="px-6 py-6 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1.5 ml-1">Pilih Tanggal
                            Faktur</label>
                        <input type="date" wire:model="deleteDateInput"
                            class="w-full pl-4 pr-4 py-2.5 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500 font-bold text-slate-700">
                        @error('deleteDateInput') <span
                            class="text-rose-500 text-xs font-bold mt-1 block ml-1">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex justify-end gap-3 border-t border-slate-200">
                    <button wire:click="closeDeleteDateModal"
                        class="px-4 py-2 bg-white border border-slate-300 rounded-xl text-sm font-bold hover:bg-slate-50">Batal</button>
                    <button wire:click="deleteByDate"
                        class="px-4 py-2 bg-rose-600 text-white rounded-xl text-sm font-bold hover:bg-rose-700">Hapus
                        Permanen</button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>