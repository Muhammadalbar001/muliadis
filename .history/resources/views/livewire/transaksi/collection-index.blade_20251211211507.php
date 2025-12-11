<div class="space-y-6 font-jakarta">

    <div
        class="sticky top-0 z-40 backdrop-blur-md bg-white/90 p-4 rounded-b-2xl shadow-sm border-b border-slate-200 transition-all duration-300 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 mb-6">
        <div class="flex flex-col xl:flex-row gap-4 items-center justify-between">
            <div class="flex items-center gap-4 w-full xl:w-auto">
                <div>
                    <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">Collection / Pelunasan</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Data pembayaran piutang pelanggan.</p>
                </div>
            </div>

            <div class="flex flex-wrap sm:flex-nowrap gap-2 items-center w-full xl:w-auto justify-end">
                <div class="relative w-full sm:w-48">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                        <i class="fas fa-search text-slate-400 text-xs"></i>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        class="pl-8 w-full border-slate-200 rounded-lg text-xs font-bold text-slate-700 focus:ring-cyan-500 py-2 shadow-sm placeholder-slate-400 transition-all"
                        placeholder="No Bukti / Invoice...">
                </div>

                <div
                    class="flex items-center gap-1 bg-white border border-slate-200 rounded-lg px-2 py-1 shadow-sm h-[34px]">
                    <input type="date" wire:model.live="startDate"
                        class="border-none text-[10px] font-bold text-slate-700 focus:ring-0 p-0 bg-transparent w-20 cursor-pointer">
                    <span class="text-slate-300 text-[10px]">-</span>
                    <input type="date" wire:model.live="endDate"
                        class="border-none text-[10px] font-bold text-slate-700 focus:ring-0 p-0 bg-transparent w-20 cursor-pointer">
                </div>

                <div class="w-full sm:w-36">
                    <select wire:model.live="filterCabang"
                        class="w-full border-slate-200 rounded-lg text-xs font-bold text-slate-700 focus:ring-cyan-500 py-2 shadow-sm cursor-pointer bg-white hover:border-cyan-300 transition-colors">
                        <option value="">Semua Cabang</option>
                        @foreach($optCabang as $c) <option value="{{ $c }}">{{ $c }}</option> @endforeach
                    </select>
                </div>

                <div class="hidden sm:block h-6 w-px bg-slate-300 mx-1"></div>

                <button wire:click="resetFilter"
                    class="px-3 py-2 bg-white border border-rose-200 text-rose-600 rounded-lg text-xs font-bold hover:bg-rose-50 shadow-sm transition-all flex items-center gap-2"
                    title="Reset Filter">
                    <i class="fas fa-undo"></i>
                </button>

                <button wire:click="openImportModal"
                    class="px-3 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white rounded-lg text-xs font-bold hover:from-cyan-700 hover:to-blue-700 shadow-md shadow-cyan-500/20 transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
                    <i class="fas fa-file-import"></i> <span class="hidden sm:inline">Import</span>
                </button>

                <div wire:loading class="text-cyan-600 ml-1"><i class="fas fa-circle-notch fa-spin"></i></div>
            </div>
        </div>
    </div>

    @if(isset($summary))
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div
            class="bg-gradient-to-br from-cyan-500 to-blue-600 rounded-2xl p-5 text-white shadow-lg shadow-cyan-500/20 relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-cyan-100 text-xs font-bold uppercase tracking-wider mb-1">Total Pelunasan Masuk</p>
                <h3 class="text-2xl font-extrabold tracking-tight">Rp
                    {{ number_format($summary['total_cair'], 0, ',', '.') }}</h3>
            </div>
            <i class="fas fa-money-bill-wave absolute right-4 top-4 text-white/20 text-6xl rotate-12"></i>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-slate-400 text-xs font-bold uppercase mb-1">Total Bukti Potong</p>
                <h3 class="text-2xl font-extrabold text-slate-800">
                    {{ number_format($summary['total_bukti'], 0, ',', '.') }}</h3>
            </div>
            <i class="fas fa-receipt text-indigo-200 text-4xl"></i>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-slate-400 text-xs font-bold uppercase mb-1">Faktur Lunas</p>
                <h3 class="text-2xl font-extrabold text-slate-800">
                    {{ number_format($summary['total_faktur'], 0, ',', '.') }}</h3>
            </div>
            <i class="fas fa-check-double text-green-200 text-4xl"></i>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col h-[95vh] overflow-hidden">
        <div class="overflow-auto flex-1 w-full custom-scrollbar">
            <table class="text-xs text-left border-collapse whitespace-nowrap w-full">
                <thead class="bg-slate-50 border-b border-slate-200 sticky top-0 z-10 shadow-sm">
                    <tr>
                        <th class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200">Tgl Bayar
                        </th>
                        <th class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200">No Bukti</th>
                        <th
                            class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200 min-w-[200px]">
                            Pelanggan</th>
                        <th class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200">No Invoice
                        </th>
                        <th class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200">Penagih</th>
                        <th class="px-6 py-4 font-bold text-slate-500 uppercase border-r border-slate-200 text-center">
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
                        <td
                            class="px-6 py-3 border-r border-slate-100 font-mono text-cyan-700 font-bold group-hover:text-cyan-800">
                            {{ $item->receive_no }}</td>
                        <td class="px-6 py-3 border-r border-slate-100 font-bold text-slate-700 truncate max-w-[200px]"
                            title="{{ $item->outlet_name }}">{{ $item->outlet_name }}</td>

                        <td class="px-6 py-3 border-r border-slate-100 font-mono text-slate-600">{{ $item->invoice_no }}
                        </td>
                        <td class="px-6 py-3 border-r border-slate-100 text-slate-500">{{ $item->penagih ?: '-' }}</td>

                        <td class="px-6 py-3 border-r border-slate-100 text-center">
                            <span
                                class="px-2 py-0.5 rounded-full bg-slate-100 border border-slate-200 text-[10px] font-bold text-slate-600">{{ $item->cabang }}</span>
                        </td>
                        <td
                            class="px-6 py-3 border-r border-slate-100 text-right font-extrabold text-slate-800 bg-cyan-50/10 group-hover:bg-cyan-50/20">
                            {{ number_format($item->receive_amount, 0, ',', '.') }}
                        </td>
                        <td
                            class="px-6 py-3 text-center sticky right-0 bg-white group-hover:bg-cyan-50/20 transition-colors">
                            <button wire:click="delete({{ $item->id }})"
                                onclick="return confirm('Hapus baris ini?') || event.stopImmediatePropagation()"
                                class="text-slate-300 hover:text-red-500 transition-colors"><i
                                    class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8"
                            class="px-6 py-24 text-center text-slate-400 flex flex-col items-center justify-center">
                            <i class="fas fa-wallet text-4xl mb-2 text-slate-200"></i>
                            <p>Tidak ada data pembayaran.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">{{ $collections->links() }}</div>
    </div>

    @if($isImportOpen)
    @include('livewire.partials.import-modal', ['title' => 'Import Collection', 'color' => 'cyan'])
    @endif

</div>