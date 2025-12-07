<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Transaksi\Penjualan;
use App\Models\Transaksi\Retur;
use App\Models\Keuangan\AccountReceivable;
use App\Models\Keuangan\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class DashboardIndex extends Component
{
    // Filter
    public $startDate;
    public $endDate;
    public $filterCabang = 'all';
    public $filterSales = 'all';

    public function mount()
    {
        $this->startDate = date('Y-m-01');
        $this->endDate = date('Y-m-d');
    }

    public function render()
    {
        // 1. DATA KARTU ATAS (SUMMARY)
        $salesSum = $this->queryPenjualan()->sum(DB::raw('CAST(total_grand AS DECIMAL(20,2))'));
        $returSum = $this->queryRetur()->sum(DB::raw('CAST(total_grand AS DECIMAL(20,2))'));
        
        // AR: Sisa Piutang saat ini (Realtime balance)
        // Kita tidak filter tanggal untuk sisa piutang karena ini akumulasi, 
        // tapi user mungkin mau lihat AR yg terbentuk di periode ini.
        // Untuk kartu KPI, kita ambil Total Tagihan di periode ini vs Sisa-nya.
        $arSum      = $this->queryAR()->sum(DB::raw('CAST(nilai AS DECIMAL(20,2))')); // Sisa
        $collSum    = $this->queryCollection()->sum(DB::raw('CAST(receive_amount AS DECIMAL(20,2))'));
        
        $persenRetur = $salesSum > 0 ? ($returSum / $salesSum) * 100 : 0;

        // 2. DATA GRAFIK: PENJUALAN VS RETUR (Harian)
        $chartDates = $this->getDatesRange();
        
        $dailySales = $this->queryPenjualan()
            ->selectRaw("DATE_FORMAT(tgl_penjualan, '%Y-%m-%d') as tgl, SUM(CAST(total_grand AS DECIMAL(20,2))) as total")
            ->groupBy('tgl')->pluck('total', 'tgl')->toArray();

        $dailyRetur = $this->queryRetur()
            ->selectRaw("DATE_FORMAT(tgl_retur, '%Y-%m-%d') as tgl, SUM(CAST(total_grand AS DECIMAL(20,2))) as total")
            ->groupBy('tgl')->pluck('total', 'tgl')->toArray();

        // 3. DATA GRAFIK: AR (Tagihan Baru) VS COLLECTION (Bayar)
        $dailyAR = $this->queryAR()
            ->selectRaw("DATE_FORMAT(tgl_penjualan, '%Y-%m-%d') as tgl, SUM(CAST(total_nilai AS DECIMAL(20,2))) as total")
            ->groupBy('tgl')->pluck('total', 'tgl')->toArray();

        $dailyColl = $this->queryCollection()
            ->selectRaw("DATE_FORMAT(tanggal, '%Y-%m-%d') as tgl, SUM(CAST(receive_amount AS DECIMAL(20,2))) as total")
            ->groupBy('tgl')->pluck('total', 'tgl')->toArray();

        // Normalisasi Data Grafik (Agar tanggalnya sinkron)
        $dataSales = []; $dataRetur = [];
        $dataAR = [];    $dataColl = [];

        foreach ($chartDates as $date) {
            $dataSales[] = $dailySales[$date] ?? 0;
            $dataRetur[] = $dailyRetur[$date] ?? 0;
            $dataAR[]    = $dailyAR[$date] ?? 0;
            $dataColl[]  = $dailyColl[$date] ?? 0;
        }

        // 4. TOP 10 PRODUK & CUSTOMER
        $topProduk = $this->queryPenjualan()
            ->selectRaw("nama_item, SUM(CAST(qty AS DECIMAL(20,2))) as total_qty, SUM(CAST(total_grand AS DECIMAL(20,2))) as total_val")
            ->groupBy('nama_item')
            ->orderByDesc('total_qty') // Ranking by Qty
            ->limit(10)
            ->get();

        $topCustomer = $this->queryPenjualan()
            ->selectRaw("nama_pelanggan, SUM(CAST(total_grand AS DECIMAL(20,2))) as total_beli")
            ->groupBy('nama_pelanggan')
            ->orderByDesc('total_beli') // Ranking by Value
            ->limit(10)
            ->get();

        // 5. OPSI FILTER
        $optCabang = Cache::remember('dash_cabang', 3600, fn() => Penjualan::select('cabang')->distinct()->pluck('cabang'));
        $optSales  = Cache::remember('dash_sales', 3600, fn() => Penjualan::select('sales_name')->distinct()->pluck('sales_name'));

        return view('livewire.dashboard-index', compact(
            'salesSum', 'returSum', 'arSum', 'collSum', 'persenRetur',
            'chartDates', 'dataSales', 'dataRetur', 'dataAR', 'dataColl',
            'topProduk', 'topCustomer',
            'optCabang', 'optSales'
        ))->layout('layouts.app', ['header' => 'Executive Dashboard']);
    }

    // --- HELPER QUERIES ---
    
    private function baseFilter($query, $dateCol) {
        return $query->whereBetween($dateCol, [$this->startDate, $this->endDate])
                     ->when($this->filterCabang != 'all', fn($q) => $q->where('cabang', $this->filterCabang))
                     ->when($this->filterSales != 'all', fn($q) => $q->where('sales_name', $this->filterSales)); // Asumsi kolom sales_name ada di semua tabel (sesuaikan jika beda)
    }

    private function queryPenjualan() { return $this->baseFilter(Penjualan::query(), 'tgl_penjualan'); }
    private function queryRetur()     { return $this->baseFilter(Retur::query(), 'tgl_retur'); }
    private function queryAR()        { return $this->baseFilter(AccountReceivable::query(), 'tgl_penjualan'); }
    private function queryCollection(){ 
        // Collection kadang nama kolom salesnya beda/tidak ada, sesuaikan logic filter sales disini
        $q = Collection::query()->whereBetween('tanggal', [$this->startDate, $this->endDate])
                ->when($this->filterCabang != 'all', fn($q) => $q->where('cabang', $this->filterCabang));
        
        if($this->filterSales != 'all') {
            $q->where('sales_name', $this->filterSales); // Pastikan kolom sales_name ada di collection
        }
        return $q;
    }

    private function getDatesRange() {
        $dates = [];
        $current = Carbon::parse($this->startDate);
        $end = Carbon::parse($this->endDate);

        while ($current <= $end) {
            $dates[] = $current->format('Y-m-d');
            $current->addDay();
        }
        return $dates;
    }
}