<div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col h-[75vh]">
    <div class="overflow-auto flex-1 w-full custom-scrollbar">
        <table class="text-xs text-left border-collapse whitespace-nowrap min-w-max">

            <thead class="text-gray-600 uppercase bg-gray-100 font-bold sticky top-0 z-20 shadow-sm">
                <tr>
                    <th class="px-4 py-3 text-center border-b border-r bg-gray-100 sticky left-0 z-30 w-12">No</th>
                    <th class="px-4 py-3 text-center border-b border-r bg-gray-100 sticky left-12 z-30 w-24">Aksi</th>
                    <th class="px-4 py-3 border-b border-r bg-gray-100 sticky left-36 z-30 w-64 shadow-md">Nama Produk
                    </th>

                    <th class="px-4 py-3 border-b border-r min-w-[100px]">Cabang</th>
                    <th class="px-4 py-3 border-b border-r min-w-[100px]">C-Code</th>
                    <th class="px-4 py-3 border-b border-r min-w-[100px]">SKU</th>
                    <th class="px-4 py-3 border-b border-r min-w-[150px]">Kategori</th>
                    <th class="px-4 py-3 border-b border-r min-w-[100px]">Expired Date</th>

                    <th class="px-4 py-3 border-b border-r bg-blue-50 text-blue-800 text-right">Stok (All)</th>
                    <th class="px-4 py-3 border-b border-r text-center">OUM</th>

                    <th class="px-4 py-3 border-b border-r text-right">Good</th>
                    <th class="px-4 py-3 border-b border-r text-right">Good Konv</th>
                    <th class="px-4 py-3 border-b border-r text-right">KTN</th>
                    <th class="px-4 py-3 border-b border-r text-right">Good Amount</th>

                    <th class="px-4 py-3 border-b border-r text-right">Avg 3M (OUM)</th>
                    <th class="px-4 py-3 border-b border-r text-right">Avg 3M (KTN)</th>
                    <th class="px-4 py-3 border-b border-r text-right">Avg 3M (Value)</th>
                    <th class="px-4 py-3 border-b border-r">Not Move 3M</th>

                    <th class="px-4 py-3 border-b border-r bg-red-50 text-red-800 text-right">Bad</th>
                    <th class="px-4 py-3 border-b border-r text-right">Bad Konv</th>
                    <th class="px-4 py-3 border-b border-r text-right">Bad KTN</th>
                    <th class="px-4 py-3 border-b border-r text-right">Bad Amount</th>

                    <th class="px-4 py-3 border-b border-r text-right bg-gray-50">Wrh 1</th>
                    <th class="px-4 py-3 border-b border-r text-right bg-gray-50">Wrh 1 Konv</th>
                    <th class="px-4 py-3 border-b border-r text-right bg-gray-50">Wrh 1 Amt</th>

                    <th class="px-4 py-3 border-b border-r text-right">Wrh 2</th>
                    <th class="px-4 py-3 border-b border-r text-right">Wrh 2 Konv</th>
                    <th class="px-4 py-3 border-b border-r text-right">Wrh 2 Amt</th>

                    <th class="px-4 py-3 border-b border-r text-right bg-gray-50">Wrh 3</th>
                    <th class="px-4 py-3 border-b border-r text-right bg-gray-50">Wrh 3 Konv</th>
                    <th class="px-4 py-3 border-b border-r text-right bg-gray-50">Wrh 3 Amt</th>

                    <th class="px-4 py-3 border-b border-r">Good Storage</th>
                    <th class="px-4 py-3 border-b border-r text-right">Sell/Week</th>
                    <th class="px-4 py-3 border-b border-r">Blank Field</th>
                    <th class="px-4 py-3 border-b border-r">Empty Field</th>
                    <th class="px-4 py-3 border-b border-r text-right">Min</th>
                    <th class="px-4 py-3 border-b border-r text-right">Re Qty</th>
                    <th class="px-4 py-3 border-b border-r">Expired Info</th>

                    <th class="px-4 py-3 border-b border-r text-right bg-emerald-50 text-emerald-800">Buy Price</th>
                    <th class="px-4 py-3 border-b border-r text-right">Buy Disc</th>
                    <th class="px-4 py-3 border-b border-r text-right">Buy in KTN</th>
                    <th class="px-4 py-3 border-b border-r text-right">Avg Price</th>
                    <th class="px-4 py-3 border-b border-r text-right">Total</th>
                    <th class="px-4 py-3 border-b border-r text-right">UP</th>
                    <th class="px-4 py-3 border-b border-r text-right">Fix</th>
                    <th class="px-4 py-3 border-b border-r text-right">PPN</th>
                    <th class="px-4 py-3 border-b border-r text-right">Fix Exc PPN</th>
                    <th class="px-4 py-3 border-b border-r text-right bg-yellow-50">Margin</th>
                    <th class="px-4 py-3 border-b border-r text-right bg-yellow-50">% Margin</th>
                    <th class="px-4 py-3 border-b border-r">Order No</th>

                    <th class="px-4 py-3 border-b border-r">Supplier</th>
                    <th class="px-4 py-3 border-b border-r">Mother SKU</th>
                    <th class="px-4 py-3 border-b border-r">Last Supplier</th>
                    <th class="px-4 py-3 border-b border-r">Divisi</th>
                    <th class="px-4 py-3 border-b border-r">Unique ID</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($produks as $index => $item)
                <tr class="hover:bg-indigo-50 transition-colors {{ $item->is_duplicate ? 'bg-red-50' : '' }}">

                    <td class="px-4 py-2 text-center text-gray-500 border-r bg-inherit sticky left-0 z-10 font-mono">
                        {{ $produks->firstItem() + $index }}
                    </td>
                    <td class="px-4 py-2 text-center border-r bg-inherit sticky left-12 z-10">
                        <div class="flex justify-center gap-2">
                            <button wire:click="edit({{ $item->id }})" class="text-blue-600 hover:text-blue-800"
                                title="Edit"><i class="fas fa-edit"></i></button>
                            <button wire:click="delete({{ $item->id }})"
                                @click="$dispatch('confirm-delete', { method: 'deleteProduk', id: {{ $item->id }} })"
                                class="text-red-500 hover:text-red-700" title="Hapus"><i
                                    class="fas fa-trash-alt"></i></button>
                        </div>
                    </td>
                    <td class="px-4 py-2 font-bold text-gray-800 border-r bg-inherit sticky left-36 z-10 shadow-md truncate max-w-[250px]"
                        title="{{ $item->name_item }}">
                        {{ $item->name_item }}
                        @if($item->is_duplicate)
                        <span class="ml-2 text-[10px] bg-red-100 text-red-600 px-1 rounded">DUPLIKAT</span>
                        @endif
                    </td>

                    <td class="px-4 py-2 border-r text-indigo-600 font-medium">{{ $item->cabang }}</td>
                    <td class="px-4 py-2 border-r">{{ $item->ccode }}</td>
                    <td class="px-4 py-2 border-r font-mono font-bold">{{ $item->sku }}</td>
                    <td class="px-4 py-2 border-r">{{ $item->kategori }}</td>
                    <td class="px-4 py-2 border-r">{{ $item->expired_date }}</td>

                    <td
                        class="px-4 py-2 border-r text-right font-bold {{ $item->stok == 0 ? 'text-red-500' : 'text-green-600' }}">
                        {{ $item->stok }}</td>
                    <td class="px-4 py-2 border-r text-center">{{ $item->oum }}</td>

                    <td class="px-4 py-2 border-r text-right">{{ $item->good }}</td>
                    <td class="px-4 py-2 border-r text-right">{{ $item->good_konversi }}</td>
                    <td class="px-4 py-2 border-r text-right">{{ $item->ktn }}</td>
                    <td class="px-4 py-2 border-r text-right">{{ $item->good_amount }}</td>

                    <td class="px-4 py-2 border-r text-right">{{ $item->avg_3m_in_oum }}</td>
                    <td class="px-4 py-2 border-r text-right">{{ $item->avg_3m_in_ktn }}</td>
                    <td class="px-4 py-2 border-r text-right">{{ $item->avg_3m_in_value }}</td>
                    <td class="px-4 py-2 border-r">{{ $item->not_move_3m }}</td>

                    <td class="px-4 py-2 border-r text-right text-red-500 bg-red-50/50">{{ $item->bad }}</td>
                    <td class="px-4 py-2 border-r text-right">{{ $item->bad_konversi }}</td>
                    <td class="px-4 py-2 border-r text-right">{{ $item->bad_ktn }}</td>
                    <td class="px-4 py-2 border-r text-right">{{ $item->bad_amount }}</td>

                    <td class="px-4 py-2 border-r text-right">{{ $item->wrh1 }}</td>
                    <td class="px-4 py-2 border-r text-right">{{ $item->wrh1_konversi }}</td>
                    <td class="px-4 py-2 border-r text-right">{{ $item->wrh1_amount }}</td>

                    <td class="px-4 py-2 border-r text-right">{{ $item->wrh2 }}</td>
                    <td class="px-4 py-2 border-r text-right">{{ $item->wrh2_konversi }}</td>
                    <td class="px-4 py-2 border-r text-right">{{ $item->wrh2_amount }}</td>

                    <td class="px-4 py-2 border-r text-right">{{ $item->wrh3 }}</td>
                    <td class="px-4 py-2 border-r text-right">{{ $item->wrh3_konversi }}</td>
                    <td class="px-4 py-2 border-r text-right">{{ $item->wrh3_amount }}</td>

                    <td class="px-4 py-2 border-r">{{ $item->good_storage }}</td>
                    <td class="px-4 py-2 border-r text-right">{{ $item->sell_per_week }}</td>
                    <td class="px-4 py-2 border-r">{{ $item->blank_field }}</td>
                    <td class="px-4 py-2 border-r">{{ $item->empty_field }}</td>
                    <td class="px-4 py-2 border-r text-right">{{ $item->min }}</td>
                    <td class="px-4 py-2 border-r text-right font-bold text-orange-600">{{ $item->re_qty }}</td>
                    <td class="px-4 py-2 border-r">{{ $item->expired_info }}</td>

                    <td class="px-4 py-2 border-r text-right">{{ $item->buy }}</td>
                    <td class="px-4 py-2 border-r text-right">{{ $item->buy_disc }}</td>
                    <td class="px-4 py-2 border-r text-right">{{ $item->buy_in_ktn }}</td>
                    <td class="px-4 py-2 border-r text-right">{{ $item->avg }}</td>
                    <td class="px-4 py-2 border-r text-right font-bold">{{ $item->total }}</td>

                    <td class="px-4 py-2 border-r text-right">{{ $item->up }}</td>
                    <td class="px-4 py-2 border-r text-right">{{ $item->fix }}</td>
                    <td class="px-4 py-2 border-r text-right">{{ $item->ppn }}</td>
                    <td class="px-4 py-2 border-r text-right">{{ $item->fix_exc_ppn }}</td>
                    <td class="px-4 py-2 border-r text-right font-bold text-emerald-600">{{ $item->margin }}</td>
                    <td class="px-4 py-2 border-r text-right">{{ $item->percent_margin }}</td>
                    <td class="px-4 py-2 border-r">{{ $item->order_no }}</td>

                    <td class="px-4 py-2 border-r text-purple-600 font-medium">{{ $item->supplier }}</td>
                    <td class="px-4 py-2 border-r">{{ $item->mother_sku }}</td>
                    <td class="px-4 py-2 border-r">{{ $item->last_supplier }}</td>
                    <td class="px-4 py-2 border-r">{{ $item->divisi }}</td>
                    <td class="px-4 py-2 border-r text-xs text-gray-400">{{ $item->unique_id }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="55" class="px-6 py-12 text-center text-gray-400">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-box-open text-4xl mb-3 text-gray-300"></i>
                            <p class="text-sm font-medium">Tidak ada data produk ditemukan.</p>
                            <p class="text-xs">Pastikan filter sesuai atau import data baru.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-4 py-3 border-t bg-gray-50">
        {{ $produks->links() }}
    </div>
</div>