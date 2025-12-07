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
        // --- SMART DATE LOGIC ---
        // Cari tanggal transaksi terakhir yang ada di database
        $lastTrx = Penjualan::max('tgl_penjualan');

        if ($lastTrx) {
            // Jika ada data (misal 2025), set filter ke bulan tersebut
            $date = Carbon::parse($lastTrx);
            $this->startDate = $date->copy()->startOfMonth()->format('Y-m-d');
            $this->endDate   = $date->copy()->endOfMonth()->format('Y-m-d');
        } else {
            // Jika kosong, pakai tanggal hari ini
            $this->startDate = date('Y-m-01');
            $this->endDate   = date('Y-m-d');
        }
    }

    public function render()
    {
        // 1. DATA KARTU ATAS (SUMMARY)
        $salesSum = $this->queryPenjualan()->sum(DB::raw('CAST(total_grand AS DECIMAL(20,2))'));
        $returSum = $this->queryRetur()->sum(DB::raw('CAST(total_grand AS DECIMAL(20,2))'));
        
        // AR: Untuk KPI, kita ambil total tagihan yg terbentuk di periode ini
        // (Bukan saldo sisa, agar apples-to-apples dengan Sales)
        $arSum      = $this->queryAR()->sum(DB::raw('CAST(total_nilai AS DECIMAL(20,2))')); 
        $collSum    = $this->queryCollection()->sum(DB::raw('CAST(receive_amount AS DECIMAL(20,2))'));
        
        $persenRetur = $salesSum > 0 ? ($returSum / $salesSum) * 100 : 0;

        // 2. DATA GRAFIK
        $chartDates = $this->getDatesRange();
        
        // Ambil data harian
        $dailySales = $this->getDailySum(Penjualan::query(), 'tgl_penjualan', 'total_grand');
        $dailyRetur = $this->getDailySum(Retur::query(), 'tgl_retur', 'total_grand');
        $dailyAR    = $this->getDailySum(AccountReceivable::query(), 'tgl_penjualan', 'total_nilai');
        $dailyColl  = $this->getDailySum(Collection::query(), 'tanggal', 'receive_amount');

        // Mapping Data agar Sinkron dengan Tanggal
        $dataSales = []; $dataRetur = [];
        $dataAR = [];    $dataColl = [];

        foreach ($chartDates as $date) {
            // Pakai (float) agar terbaca sebagai angka oleh Grafik JS
            $dataSales[] = (float) ($dailySales[$date] ?? 0);
            $dataRetur[] = (float) ($dailyRetur[$date] ?? 0);
            $dataAR[]    = (float) ($dailyAR[$date] ?? 0);
            $dataColl[]  = (float) ($dailyColl[$date] ?? 0);
        }

        // 3. TOP 10 PRODUK & CUSTOMER
        $topProduk = $this->queryPenjualan()
            ->selectRaw("nama_item, SUM(CAST(qty AS DECIMAL(20,2))) as total_qty")
            ->groupBy('nama_item')
            ->orderByDesc('total_qty')
            ->limit(10)
            ->get();

        $topCustomer = $this->queryPenjualan()
            ->selectRaw("nama_pelanggan, SUM(CAST(total_grand AS DECIMAL(20,2))) as total_beli")
            ->groupBy('nama_pelanggan')
            ->orderByDesc('total_beli')
            ->limit(10)
            ->get();

        // 4. OPSI FILTER
        $optCabang = Cache::remember('dash_cabang', 3600, fn() => Penjualan::select('cabang')->distinct()->whereNotNull('cabang')->pluck('cabang'));
        $optSales  = Cache::remember('dash_sales', 3600, fn() => Penjualan::select('sales_name')->distinct()->whereNotNull('sales_name')->pluck('sales_name'));

        return view('livewire.dashboard-index', compact(
            'salesSum', 'returSum', 'arSum', 'collSum', 'persenRetur',
            'chartDates', 'dataSales', 'dataRetur', 'dataAR', 'dataColl',
            'topProduk', 'topCustomer',
            'optCabang', 'optSales'
        ))->layout('layouts.app', ['header' => 'Dashboard Executive']);
    }

    // --- HELPER FUNCTIONS ---

    private function getDailySum($query, $dateCol, $sumCol)
    {
        return $this->baseFilter($query, $dateCol)
            ->selectRaw("DATE_FORMAT($dateCol, '%Y-%m-%d') as tgl, SUM(CAST($sumCol AS DECIMAL(20,2))) as total")
            ->groupBy('tgl')
            ->pluck('total', 'tgl')
            ->toArray();
    }
    
    private function baseFilter($query, $dateCol) {
        return $query->whereBetween($dateCol, [$this->startDate, $this->endDate])
                     ->when($this->filterCabang != 'all', fn($q) => $q->where('cabang', $this->filterCabang))
                     ->when($this->filterSales != 'all' && $this->hasColumn($query, 'sales_name'), fn($q) => $q->where('sales_name', $this->filterSales));
    }

    private function queryPenjualan() { return $this->baseFilter(Penjualan::query(), 'tgl_penjualan'); }
    private function queryRetur()     { return $this->baseFilter(Retur::query(), 'tgl_retur'); }
    private function queryAR()        { return $this->baseFilter(AccountReceivable::query(), 'tgl_penjualan'); }
    private function queryCollection(){ return $this->baseFilter(Collection::query(), 'tanggal'); }

    private function hasColumn($query, $col) {
        // Cek sederhana apakah model punya kolom sales_name (untuk menghindari error di tabel yg mungkin beda nama kolom)
        // Disini kita asumsi semua tabel transaksi utama punya 'sales_name' sesuai migrasi terakhir.
        return true; 
    }

    private function getDatesRange() {
        $dates = [];
        try {
            $current = Carbon::parse($this->startDate);
            $end = Carbon::parse($this->endDate);
            
            // Safety: Jangan loop lebih dari 60 hari agar grafik tidak hancur
            if ($current->diffInDays($end) > 60) {
                $current = $end->copy()->subDays(60);
            }

            while ($current <= $end) {
                $dates[] = $current->format('Y-m-d');
                $current->addDay();
            }
        } catch (\Exception $e) {
            return [];
        }
        return $dates;
    }
}