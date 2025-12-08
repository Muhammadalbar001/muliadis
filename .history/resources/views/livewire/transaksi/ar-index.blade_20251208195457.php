<div class="space-y-6 font-jakarta">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Input Data Piutang (AR)</h2>
            <p class="text-sm text-slate-500">Daftar tagihan toko yang belum terbayar (Outstanding).</p>
        </div>
        <div class="flex gap-2">
            <button wire:click="openImportModal"
                class="inline-flex items-center px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-bold rounded-xl shadow-sm transition-all transform hover:-translate-y-0.5">
                <i class="fas fa-file-invoice-dollar mr-2"></i> Import AR
            </button>
        </div>
    </div>

    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2 relative">
                <i class="fas fa-search absolute left-3 top-3 text-slate-400"></i>
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="pl-10 w-full border-slate-200 rounded-xl text-sm focus:ring-orange-500"
                    placeholder="Cari No Invoice atau Nama Pelanggan...">
            </div>
            <div>
                <select wire:model.live="filterUmur"
                    class="w-full border-slate-200 rounded-xl text-xs focus:ring-orange-500">
                    <option value="">Semua Status Umur</option>
                    <option value="lancar">✅ Lancar (<= 30 Hari)</option>
                    <option value="macet">⚠️ Macet (> 30 Hari)</option>
                </select>
            </div>
            <div>
                <select wire:model.live="filterCabang"
                    class="w-full border-slate-200 rounded-xl text-xs focus:ring-orange-500">
                    <option value="">Semua Cabang</option>
                    @foreach($optCabang ?? [] as $c) <option value="{{ $c }}">{{ $c }}</option> @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 flex flex-col h-[70vh]">
        <div class="overflow-auto flex-1 w-full custom-scrollbar">
            <table class="text-xs text-left border-collapse whitespace-nowrap w-full">
                <thead
                    class="text-slate-500 uppercase bg-slate-50 font-bold border-b border-slate-200 sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-3 border-r">Cabang</th>
                        <th class="px-4 py-3 border-r">No Invoice</th>
                        <th class="px-4 py-3 border-r">Tgl Faktur</th>
                        <th class="px-4 py-3 border-r min-w-[200px]">Nama Pelanggan</th>
                        <th class="px-4 py-3 border-r">Salesman</th>

                        <th class="px-4 py-3 border-r text-center">Jatuh Tempo</th>
                        <th class="px-4 py-3 border-r text-center">Umur</th>

                        <th class="px-4 py-3 text-right bg-orange-50 text-orange-900">Sisa Tagihan</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($ars as $item)
                    <tr class="hover:bg-orange-50/30 transition-colors">

                        <td class="px-4 py-2 border-r text-orange-600 font-bold">{{ $item->cabang }}</td>
                        <td class="px-4 py-2 border-r font-mono text-slate-700 font-bold">{{ $item->no_penjualan }}</td>
                        <td class="px-4 py-2 border-r text-slate-500">
                            {{ $item->tgl_penjualan ? date('d/m/Y', strtotime($item->tgl_penjualan)) : '-' }}
                        </td>

                        <td class="px-4 py-2 border-r font-medium text-slate-800 truncate max-w-[200px]"
                            title="{{ $item->pelanggan_name }}">
                            {{ $item->pelanggan_name }}
                        </td>
                        <td class="px-4 py-2 border-r text-slate-500">{{ $item->sales_name }}</td>

                        @php
                        $isOverdue = $item->jatuh_tempo && \Carbon\Carbon::parse($item->jatuh_tempo)->isPast();
                        @endphp
                        <td class="px-4 py-2 border-r text-center {{ $isOverdue ? 'text-red-600 font-bold' : '' }}">
                            {{ $item->jatuh_tempo ? date('d/m/Y', strtotime($item->jatuh_tempo)) : '-' }}
                        </td>

                        <td class="px-4 py-2 border-r text-center">
                            @php $umur = (int)($item->umur_piutang ?? 0); @endphp
                            <span
                                class="px-2 py-0.5 rounded text-[10px] font-bold text-white {{ $umur > 30 ? 'bg-red-500' : 'bg-green-500' }}">
                                {{ $umur }} Hari
                            </span>
                        </td>

                        <td class="px-4 py-2 border-r text-right font-bold text-slate-800">
                            Rp {{ number_format((float)$item->nilai, 0, ',', '.') }}
                        </td>

                        <td class="px-4 py-2 text-center">
                            <button wire:click="delete({{ $item->id }})"
                                @click="$dispatch('confirm-delete', { method: 'deleteAr', id: {{ $item->id }} })"
                                class="text-slate-300 hover:text-red-500 transition-colors">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-money-check-alt text-3xl mb-3 text-orange-200"></i>
                                <p>Tidak ada tagihan outstanding.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t bg-slate-50">
            {{ $ars->links() }}
        </div>
    </div>

    @if($isImportOpen) @include('livewire.partials.import-modal', ['title'=>'Import Data Piutang', 'color'=>'orange'])
    @endif
</div>