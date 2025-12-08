<div class="space-y-6 font-jakarta">

    <div
        class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex flex-col md:flex-row justify-between items-end gap-4">

        <div class="w-full md:w-1/2">
            <h2 class="text-lg font-bold text-slate-800 mb-1">Daftar Produk</h2>
            <p class="text-xs text-slate-500 mb-3">Kelola data master barang dagangan.</p>
            <div class="relative">
                <input wire:model.live.debounce.300ms="search" type="text"
                    class="pl-10 pr-4 py-2.5 w-full border-slate-200 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 placeholder-slate-400 bg-slate-50"
                    placeholder="Cari Kode Item atau Nama Produk...">
                <i class="fas fa-search absolute left-3.5 top-3 text-slate-400 text-sm"></i>
            </div>
        </div>

        <div class="flex gap-2">
            <button wire:click="openImportModal"
                class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-sm transition-all transform hover:-translate-y-0.5 hover:shadow-indigo-200">
                <i class="fas fa-file-import mr-2"></i> Import Excel
            </button>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-slate-500 uppercase bg-slate-50 font-bold border-b border-slate-100 text-xs">
                    <tr>
                        <th class="px-6 py-4 w-16 text-center">No</th>
                        <th class="px-6 py-4">Kode Item (SKU)</th>
                        <th class="px-6 py-4">Nama Produk</th>
                        <th class="px-6 py-4">Satuan</th>
                        <th class="px-6 py-4 text-right">Harga Beli</th>
                        <th class="px-6 py-4 text-right">Harga Jual</th>
                        <th class="px-6 py-4 text-center">Margin</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    {{-- PERBAIKAN: Ubah $produk menjadi $produks --}}
                    @forelse($produks as $index => $item)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-6 py-3 text-center text-slate-400 text-xs">{{ $produks->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-3 font-mono text-xs text-indigo-600 font-bold">{{ $item->sku }}</td>
                        {{-- Sesuaikan dengan nama kolom DB: sku --}}
                        <td class="px-6 py-3 font-bold text-slate-700">{{ $item->name_item }}</td>
                        {{-- Sesuaikan dengan nama kolom DB: name_item --}}
                        <td class="px-6 py-3 text-slate-500 text-xs uppercase bg-slate-50 rounded-lg px-2 w-fit">
                            {{ $item->oum }}</td> {{-- Sesuaikan dengan nama kolom DB: oum --}}
                        <td class="px-6 py-3 text-right text-slate-500 font-mono">
                            {{ number_format($item->buy, 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-right font-bold text-slate-700 font-mono">
                            {{ number_format($item->sell, 0, ',', '.') }}</td>
                        {{-- Sesuaikan dengan nama kolom DB: sell --}}
                        <td class="px-6 py-3 text-center">
                            @php
                            $harga_jual = $item->sell ?? 0;
                            $harga_beli = $item->buy ?? 0;
                            $margin = $harga_jual - $harga_beli;
                            $persen = $harga_beli > 0 ? ($margin / $harga_beli) * 100 : 0;
                            @endphp
                            <span class="text-xs font-bold {{ $margin > 0 ? 'text-emerald-600' : 'text-red-500' }}">
                                {{ number_format($persen, 1) }}%
                            </span>
                        </td>
                        <td class="px-6 py-3 text-center">
                            <button wire:click="delete({{ $item->id }})"
                                @click="$dispatch('confirm-delete', { method: 'deleteProduk', id: {{ $item->id }} })"
                                class="text-slate-300 hover:text-red-500 transition-colors p-2 rounded-full hover:bg-red-50"
                                title="Hapus Produk">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-slate-400 bg-slate-50/50">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-box-open text-4xl mb-3 text-slate-300"></i>
                                <p class="text-sm font-medium">Belum ada data produk.</p>
                                <p class="text-xs mt-1 text-slate-400">Silakan import data dari Excel.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
            {{ $produks->links() }}
        </div>
    </div>

    @if($isImportOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

            <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity backdrop-blur-sm"
                wire:click="closeImportModal"></div>

            <div
                class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">

                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-file-excel text-indigo-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-bold text-slate-900">Import Data Produk</h3>
                            <div class="mt-2">
                                <p class="text-sm text-slate-500 mb-4">Upload file Excel (.xlsx).</p>

                                <div
                                    class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-xl hover:bg-slate-50 cursor-pointer relative">
                                    <div class="space-y-1 text-center">
                                        <i class="fas fa-cloud-upload-alt text-4xl text-slate-400"></i>
                                        <div class="flex text-sm text-slate-600 justify-center mt-2">
                                            <label
                                                class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                                                <span>Pilih File</span>
                                                <input wire:model="file" type="file" class="sr-only">
                                            </label>
                                        </div>
                                    </div>
                                    @if($file)
                                    <div
                                        class="absolute inset-0 bg-indigo-50 bg-opacity-90 flex flex-col items-center justify-center rounded-xl">
                                        <span
                                            class="text-sm font-bold text-slate-700">{{ $file->getClientOriginalName() }}</span>
                                    </div>
                                    @endif
                                </div>

                                <div wire:loading wire:target="import" class="w-full mt-2 text-center">
                                    <span class="text-xs text-green-600 font-bold animate-pulse">Sedang memproses
                                        data...</span>
                                </div>
                                @error('file') <div class="mt-2 text-red-500 text-xs">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                    <button wire:click="import" wire:loading.attr="disabled"
                        class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">
                        Mulai Import
                    </button>
                    <button wire:click="closeImportModal"
                        class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 sm:mt-0 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>