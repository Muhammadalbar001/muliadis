<div class="space-y-6 font-jakarta">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Data Salesman</h1>
            <p class="text-sm text-slate-500">Kelola tim penjualan dan target mereka.</p>
        </div>

        <div class="flex gap-3">
            <div class="bg-white px-4 py-2 rounded-xl border border-indigo-100 shadow-sm flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <p class="text-[10px] text-slate-400 font-bold uppercase">Total Tim</p>
                    <p class="text-sm font-bold text-slate-800">{{ $sales->total() }} Orang</p>
                </div>
            </div>
        </div>
    </div>

    <div
        class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 flex flex-col sm:flex-row justify-between items-center gap-4">

        <div class="relative w-full sm:w-72">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-slate-400"></i>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text"
                class="block w-full pl-10 pr-3 py-2.5 border border-slate-200 rounded-xl leading-5 bg-slate-50 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-indigo-200 sm:text-sm transition-all"
                placeholder="Cari nama, kode, atau kota...">
        </div>

        <button wire:click="create"
            class="w-full sm:w-auto px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-200 transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2">
            <i class="fas fa-plus"></i> Tambah Salesman
        </button>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Info
                            Salesman</th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                            Wilayah (Kota)</th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Kontak
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse($sales as $item)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div
                                        class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white font-bold text-sm shadow-md">
                                        {{ substr($item->sales_name, 0, 1) }}
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-slate-900">{{ $item->sales_name }}</div>
                                    <div class="text-xs text-slate-500 font-mono">ID: {{ $item->sales_code ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-blue-50 text-blue-700 border border-blue-100">
                                <i class="fas fa-map-marker-alt mr-1.5 mt-0.5"></i> {{ $item->city ?? 'Nasional' }}
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-slate-600 flex flex-col gap-1">
                                <span class="flex items-center gap-2"><i
                                        class="fas fa-phone text-slate-400 text-xs"></i>
                                    {{ $item->phone ?? '-' }}</span>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div
                                class="flex justify-center gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                <button wire:click="edit({{ $item->id }})"
                                    class="p-2 rounded-lg text-indigo-600 hover:bg-indigo-50 transition-colors"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:click="delete({{ $item->id }})"
                                    wire:confirm="Yakin ingin menghapus salesman ini?"
                                    class="p-2 rounded-lg text-rose-600 hover:bg-rose-50 transition-colors"
                                    title="Hapus">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-user-slash text-3xl text-slate-400"></i>
                                </div>
                                <h3 class="text-slate-900 font-bold text-lg">Belum ada Salesman</h3>
                                <p class="text-slate-500 text-sm max-w-xs mx-auto mb-6">Data salesman yang Anda cari
                                    tidak ditemukan atau belum ditambahkan.</p>
                                <button wire:click="create"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-xs font-bold hover:bg-indigo-700 transition">
                                    <i class="fas fa-plus mr-1"></i> Tambah Data Baru
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
            {{ $sales->links() }}
        </div>
    </div>

    @include('livewire.master.sales-form')

</div>