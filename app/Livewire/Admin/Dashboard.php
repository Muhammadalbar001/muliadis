<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Master\SalesTarget;
use App\Models\Master\Sales; // Pastikan Model Sales di-import
use App\Models\Transaksi\Penjualan;
use App\Models\Transaksi\Retur;
use App\Models\Keuangan\AccountReceivable;
use App\Models\Keuangan\Collection;
use App\Models\Master\Produk; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class Dashboard extends Component
{
    // Filter Global
    public $startDate;
    public $endDate;
    public $filterCabang = [];
    public $filterSales = [];
    
    // Filter Spesifik Widget (Multi Select)
    public $filterSupplierTopProduk = []; 
    public $filterSalesTopCust = [];      
    public $filterKategoriTopSupp = [];   

    public function mount()
    {
        $this->startDate = date('Y-m-01');
        $this->endDate   = date('Y-m-d');
    }

    public function applyFilter()
    {
        $this->dispatch('update-charts', data: $this->chartData);
    }

    public function updatedFilterSupplierTopProduk() { $this->dispatch('update-charts', data: $this->chartData); }
    public function updatedFilterSalesTopCust()      { $this->dispatch('update-charts', data: $this->chartData); }
    public function updatedFilterKategoriTopSupp()   { $this->dispatch('update-charts', data: $this->chartData); }

    private function baseFilter($query, $dateCol) {
        return $query->whereDate($dateCol, '>=', $this->startDate)
                     ->whereDate($dateCol, '<=', $this->endDate)
                     ->when(!empty($this->filterCabang), fn($q) => $q->whereIn('cabang', $this->filterCabang))
                     ->when(!empty($this->filterSales), fn($q) => $q->whereIn('sales_name', $this->filterSales));
    }

    #[Computed]
    public function kpiStats()
    {
        $key = 'kpi-' . md5(json_encode([$this->startDate, $this->endDate, $this->filterCabang, $this->filterSales]));
        
        return Cache::remember($key, 60 * 10, function () {
            // Gunakan CAST agar string angka terbaca benar
            $salesSum = $this->baseFilter(Penjualan::query(), 'tgl_penjualan')->sum(DB::raw('CAST(total_grand AS DECIMAL(20,2))'));
            $returSum = $this->baseFilter(Retur::query(), 'tgl_retur')->sum(DB::raw('CAST(total_grand AS DECIMAL(20,2))'));
            $arSum    = $this->baseFilter(AccountReceivable::query(), 'tgl_penjualan')->sum(DB::raw('CAST(nilai AS DECIMAL(20,2))'));
            $collSum  = $this->baseFilter(Collection::query(), 'tanggal')->sum(DB::raw('CAST(receive_amount AS DECIMAL(20,2))'));
            
            $persenRetur = $salesSum > 0 ? ($returSum / $salesSum) * 100 : 0;
            $totalOa     = $this->baseFilter(Penjualan::query(), 'tgl_penjualan')->distinct('kode_pelanggan')->count();
            $totalEc     = $this->baseFilter(Penjualan::query(), 'tgl_penjualan')->distinct('trans_no')->count();

            return compact('salesSum', 'returSum', 'arSum', 'collSum', 'persenRetur', 'totalOa', 'totalEc');
        });
    }

    #[Computed]
    public function chartData()
    {
        // NO CACHE agar Target Real-time
        $dates = [];
        $start = $this->startDate ? Carbon::parse($this->startDate) : Carbon::now()->startOfMonth();
        $end   = $this->endDate ? Carbon::parse($this->endDate) : Carbon::now();
        
        $c = $start->copy();
        $limit = 0; 
        while ($c <= $end && $limit < 366) { 
            $dates[] = $c->format('Y-m-d'); 
            $c->addDay();
            $limit++;
        }

        // --- Data Trend Harian ---
        $dailySales = $this->baseFilter(Penjualan::query(), 'tgl_penjualan')
            ->selectRaw("DATE(tgl_penjualan) as tgl, SUM(CAST(total_grand AS DECIMAL(20,2))) as total") // Pakai CAST
            ->groupBy('tgl')->pluck('total', 'tgl');
            
        $dailyRetur = $this->baseFilter(Retur::query(), 'tgl_retur')
            ->selectRaw("DATE(tgl_retur) as tgl, SUM(CAST(total_grand AS DECIMAL(20,2))) as total")
            ->groupBy('tgl')->pluck('total', 'tgl');
            
        $dailyAR = $this->baseFilter(AccountReceivable::query(), 'tgl_penjualan')
            ->selectRaw("DATE(tgl_penjualan) as tgl, SUM(CAST(total_nilai AS DECIMAL(20,2))) as total")
            ->groupBy('tgl')->pluck('total', 'tgl');
            
        $dailyColl = $this->baseFilter(Collection::query(), 'tanggal')
            ->selectRaw("DATE(tanggal) as tgl, SUM(CAST(receive_amount AS DECIMAL(20,2))) as total")
            ->groupBy('tgl')->pluck('total', 'tgl');

        $dSales = []; $dRetur = []; $dAR = []; $dColl = [];
        foreach ($dates as $d) {
            $dSales[] = (float)($dailySales[$d] ?? 0);
            $dRetur[] = (float)($dailyRetur[$d] ?? 0);
            $dAR[]    = (float)($dailyAR[$d] ?? 0);
            $dColl[]  = (float)($dailyColl[$d] ?? 0);
        }

        // --- Logic Ranking (Filter Spesifik) ---
        $qTopProd = $this->baseFilter(Penjualan::query(), 'tgl_penjualan');
        if (!empty($this->filterSupplierTopProduk)) { 
            $qTopProd->whereIn('supplier', $this->filterSupplierTopProduk); 
        }
        $topProduk = $qTopProd->selectRaw("nama_item, SUM(CAST(qty AS DECIMAL(20,2))) as total") // Pakai CAST qty
            ->groupBy('nama_item')->orderByDesc('total')->limit(10)->get();

        $qTopCust = $this->baseFilter(Penjualan::query(), 'tgl_penjualan');
        if (!empty($this->filterSalesTopCust)) { 
            $qTopCust->whereIn('sales_name', $this->filterSalesTopCust); 
        }
        $topCustomer = $qTopCust->selectRaw("nama_pelanggan, SUM(CAST(total_grand AS DECIMAL(20,2))) as total")
            ->groupBy('nama_pelanggan')->orderByDesc('total')->limit(10)->get();

        $qTopSupp = $this->baseFilter(Penjualan::query(), 'tgl_penjualan');
        if (!empty($this->filterKategoriTopSupp)) {
            $itemsInCat = Produk::whereIn('kategori', $this->filterKategoriTopSupp)->pluck('name_item');
            $qTopSupp->whereIn('nama_item', $itemsInCat);
        }
        $topSupplier = $qTopSupp->selectRaw("supplier, SUM(CAST(total_grand AS DECIMAL(20,2))) as total")
            ->groupBy('supplier')->orderByDesc('total')->limit(10)->get();

        // --- Logic Sales Performance (NEW) ---
        $salesPerf = $this->getSalesmanPerformance();

        return [
            'dates'         => $dates,
            'trend_sales'   => $dSales,
            'trend_retur'   => $dRetur,
            'trend_ar'      => $dAR,
            'trend_coll'    => $dColl,
            'top_produk_lbl'=> $topProduk->pluck('nama_item'),
            'top_produk_val'=> $topProduk->pluck('total'),
            'top_cust_lbl'  => $topCustomer->pluck('nama_pelanggan'),
            'top_cust_val'  => $topCustomer->pluck('total'),
            'top_supp_lbl'  => $topSupplier->pluck('supplier'),
            'top_supp_val'  => $topSupplier->pluck('total'),
            
            // Sales Data Terhubung
            'sales_details' => $salesPerf['details'],      
            'total_target'  => $salesPerf['total_target'], 
            'total_real'    => $salesPerf['total_real'],   
            'sales_names'   => $salesPerf['chart_names'],  
            'sales_real'    => $salesPerf['chart_real'],   
            'sales_target'  => $salesPerf['chart_target'], 
        ];
    }

    private function getSalesmanPerformance()
{
    // 1. Ambil Master Sales Aktif
    // Pastikan kita mengambil sales_code untuk mencocokkan dengan penjualan
    $activeSales = \App\Models\Master\Sales::where('status', 'Active')->get(); 
    $salesIds = $activeSales->pluck('id')->toArray();
    $salesCodes = $activeSales->pluck('sales_code')->toArray();

    // 2. Ambil Target (Multi-Month Logic)
    $start = $this->startDate ? \Carbon\Carbon::parse($this->startDate) : \Carbon\Carbon::now()->startOfMonth();
    $end   = $this->endDate ? \Carbon\Carbon::parse($this->endDate) : \Carbon\Carbon::now();

    $targets = \App\Models\Master\SalesTarget::whereIn('sales_id', $salesIds)
        ->where(function($q) use ($start, $end) {
             $q->where(function($sub) use ($start) {
                 $sub->where('year', '>', $start->year)
                     ->orWhere(function($s) use ($start) {
                         $s->where('year', $start->year)->where('month', '>=', $start->month);
                     });
             })->where(function($sub) use ($end) {
                 $sub->where('year', '<', $end->year)
                     ->orWhere(function($s) use ($end) {
                         $s->where('year', $end->year)->where('month', '<=', $end->month);
                     });
             });
        })->get();

    // Map Target: sales_id => total target rupiah
    $targetMap = [];
    foreach ($targets as $t) {
        if (!isset($targetMap[$t->sales_id])) $targetMap[$t->sales_id] = 0;
        $targetMap[$t->sales_id] += (float) $t->target_ims;
    }

    // 3. Ambil Realisasi Penjualan
    // Kuncinya: CAST total_grand ke decimal karena di migrasi tipenya string
    $realMap = \App\Models\Transaksi\Penjualan::query()
        ->whereDate('tgl_penjualan', '>=', $this->startDate)
        ->whereDate('tgl_penjualan', '<=', $this->endDate)
        ->whereIn('kode_sales', $salesCodes) // Menggunakan kode_sales dari tabel penjualans
        ->when(!empty($this->filterCabang), fn($q) => $q->whereIn('cabang', $this->filterCabang))
        ->selectRaw("kode_sales, SUM(CAST(total_grand AS DECIMAL(20,2))) as total")
        ->groupBy('kode_sales')
        ->pluck('total', 'kode_sales')
        ->toArray();

    // 4. Proses Penggabungan (Leaderboard & Kartu)
    $details = [];
    $totalTargetGlobal = 0;
    $totalRealGlobal = 0;

    foreach ($activeSales as $sales) {
        $id = $sales->id;
        $code = $sales->sales_code; // Kode dari master
        
        $target = $targetMap[$id] ?? 0;
        $real = (float) ($realMap[$code] ?? 0); // Cocokkan dengan realMap yang kuncinya adalah kode_sales penjualan

        // Hitung total untuk kartu KPI
        $totalTargetGlobal += $target;
        $totalRealGlobal += $real;

        $persen = $target > 0 ? ($real / $target) * 100 : ($real > 0 ? 100 : 0);

        $details[] = [
            'name'   => $sales->sales_name,
            'real'   => $real,
            'target' => $target,
            'persen' => $persen,
            'gap'    => $real - $target
        ];
    }

    // 5. Sorting Leaderboard: Achievement (%) Tertinggi ke Terendah
    usort($details, fn($a, $b) => $b['persen'] <=> $a['persen']);

    // 6. Data Chart: Ambil Top 10 berdasarkan Realisasi (Bukan Persen)
    $chartDataRaw = $details;
    usort($chartDataRaw, fn($a, $b) => $b['real'] <=> $a['real']); 
    $chartDataLimit = array_slice($chartDataRaw, 0, 10); 

    return [
        'chart_names'  => array_column($chartDataLimit, 'name'),
        'chart_real'   => array_column($chartDataLimit, 'real'),
        'chart_target' => array_column($chartDataLimit, 'target'),
        'details'      => $details, // Full list untuk tabel leaderboard
        'total_target' => $totalTargetGlobal,
        'total_real'   => $totalRealGlobal
    ];
}

    public function render()
    {
        $optCabang = Cache::remember('dash_cabang', 3600, fn() => Penjualan::select('cabang')->distinct()->whereNotNull('cabang')->pluck('cabang'));
        $optSales  = Cache::remember('dash_sales', 3600, fn() => Sales::select('sales_name')->where('status', 'Active')->orderBy('sales_name')->pluck('sales_name')); // Ambil dari Master Sales
        
        $optSupplierList = Cache::remember('dash_opt_supp', 3600, fn() => 
            Produk::select('supplier')->whereNotNull('supplier')->distinct()->orderBy('supplier')->pluck('supplier')
        );

        $optKategoriList = Cache::remember('dash_opt_cat', 3600, fn() => 
            Produk::select('kategori')->whereNotNull('kategori')->where('kategori','!=','')->distinct()->orderBy('kategori')->pluck('kategori')
        );

        $stats = $this->kpiStats;
        $chartData = $this->chartData;

        return view('livewire.admin.dashboard', array_merge(
            $stats, 
            compact('optCabang', 'optSales', 'chartData', 'optSupplierList', 'optKategoriList')
        ))->layout('layouts.app', ['header' => 'Executive Dashboard']);
    }

    public function formatCompact($val)
    {
        if ($val >= 1000000000) { return number_format($val / 1000000000, 2, ',', '.') . ' M'; 
        } elseif ($val >= 1000000) { return number_format($val / 1000000, 1, ',', '.') . ' Jt'; 
        } else { return number_format($val, 0, ',', '.'); }
    }
}