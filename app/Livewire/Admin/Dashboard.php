<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Master\SalesTarget;
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
    
    // --- UBAH JADI ARRAY UNTUK MULTI SELECT ---
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

    // Listener tetap sama
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
        
        return Cache::remember($key, 60 * 60, function () {
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
        $key = 'chart-' . md5(json_encode([
            $this->startDate, $this->endDate, $this->filterCabang, $this->filterSales,
            $this->filterSupplierTopProduk, $this->filterSalesTopCust, $this->filterKategoriTopSupp
        ]));

        return Cache::remember($key, 60 * 60, function () {
            // ... (Logic Dates & Trend tetap sama, copy paste dari sebelumnya jika perlu, atau biarkan code existing) ...
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

            $dailySales = $this->baseFilter(Penjualan::query(), 'tgl_penjualan')->selectRaw("DATE(tgl_penjualan) as tgl, SUM(total_grand) as total")->groupBy('tgl')->pluck('total', 'tgl');
            $dailyRetur = $this->baseFilter(Retur::query(), 'tgl_retur')->selectRaw("DATE(tgl_retur) as tgl, SUM(total_grand) as total")->groupBy('tgl')->pluck('total', 'tgl');
            $dailyAR    = $this->baseFilter(AccountReceivable::query(), 'tgl_penjualan')->selectRaw("DATE(tgl_penjualan) as tgl, SUM(total_nilai) as total")->groupBy('tgl')->pluck('total', 'tgl');
            $dailyColl  = $this->baseFilter(Collection::query(), 'tanggal')->selectRaw("DATE(tanggal) as tgl, SUM(receive_amount) as total")->groupBy('tgl')->pluck('total', 'tgl');

            $dSales = []; $dRetur = []; $dAR = []; $dColl = [];
            foreach ($dates as $d) {
                $dSales[] = (float)($dailySales[$d] ?? 0);
                $dRetur[] = (float)($dailyRetur[$d] ?? 0);
                $dAR[]    = (float)($dailyAR[$d] ?? 0);
                $dColl[]  = (float)($dailyColl[$d] ?? 0);
            }

            // --- 1. TOP PRODUK (Updated: whereIn) ---
            $qTopProd = $this->baseFilter(Penjualan::query(), 'tgl_penjualan');
            if (!empty($this->filterSupplierTopProduk)) {
                $qTopProd->whereIn('supplier', $this->filterSupplierTopProduk); // GUNAKAN WHERE IN
            }
            $topProduk = $qTopProd->selectRaw("nama_item, SUM(qty) as total")->groupBy('nama_item')->orderByDesc('total')->limit(10)->get();

            // --- 2. TOP CUSTOMER (Updated: whereIn) ---
            $qTopCust = $this->baseFilter(Penjualan::query(), 'tgl_penjualan');
            if (!empty($this->filterSalesTopCust)) {
                $qTopCust->whereIn('sales_name', $this->filterSalesTopCust); // GUNAKAN WHERE IN
            }
            $topCustomer = $qTopCust->selectRaw("nama_pelanggan, SUM(total_grand) as total")->groupBy('nama_pelanggan')->orderByDesc('total')->limit(10)->get();

            // --- 3. TOP SUPPLIER (Updated: whereIn) ---
            $qTopSupp = $this->baseFilter(Penjualan::query(), 'tgl_penjualan');
            if (!empty($this->filterKategoriTopSupp)) {
                $itemsInCat = Produk::whereIn('kategori', $this->filterKategoriTopSupp)->pluck('name_item'); // GUNAKAN WHERE IN
                $qTopSupp->whereIn('nama_item', $itemsInCat);
            }
            $topSupplier = $qTopSupp->selectRaw("supplier, SUM(total_grand) as total")->groupBy('supplier')->orderByDesc('total')->limit(10)->get();

            $salesPerf = $this->getSalesmanPerformance();

            return [
                'dates'         => $dates,
                'trend_sales'   => $dSales,
                'trend_retur'   => $dRetur,
                'trend_ar'      => $dAR,
                'trend_coll'    => $dColl,
                'top_produk_lbl' => $topProduk->pluck('nama_item'),
                'top_produk_val' => $topProduk->pluck('total'),
                'top_cust_lbl'   => $topCustomer->pluck('nama_pelanggan'),
                'top_cust_val'   => $topCustomer->pluck('total'),
                'top_supp_lbl'   => $topSupplier->pluck('supplier'),
                'top_supp_val'   => $topSupplier->pluck('total'),
                'sales_names'   => $salesPerf['names'],
                'sales_real'    => $salesPerf['real'],
                'sales_target'  => $salesPerf['target'],
            ];
        });
    }

    private function getSalesmanPerformance()
    {
        $realSales = $this->baseFilter(Penjualan::query(), 'tgl_penjualan')
            ->selectRaw("kode_sales, MAX(sales_name) as sales_name, SUM(total_grand) as total")
            ->whereNotNull('kode_sales')
            ->groupBy('kode_sales')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
        
        $codes = $realSales->pluck('kode_sales')->toArray();
        $names = $realSales->pluck('sales_name')->toArray();
        $start = $this->startDate ? Carbon::parse($this->startDate) : Carbon::now();
        
        $targets = SalesTarget::with('sales')
            ->where('year', $start->year)
            ->where('month', $start->month)
            ->whereHas('sales', fn($q) => $q->whereIn('sales_code', $codes)) 
            ->get();

        $targetMap = $targets->mapWithKeys(function ($item) {
            if ($item->sales) {
                return [$item->sales->sales_code => (float) $item->target_ims];
            }
            return [];
        });

        $dataTarget = [];
        foreach($realSales as $row) {
            $code = $row->kode_sales;
            $dataTarget[] = $targetMap[$code] ?? 0;
        }

        return [
            'names'  => $names,
            'real'   => $realSales->pluck('total')->toArray(),
            'target' => $dataTarget
        ];
    }

    public function render()
    {
        $optCabang = Cache::remember('dash_cabang', 3600, fn() => Penjualan::select('cabang')->distinct()->whereNotNull('cabang')->pluck('cabang'));
        $optSales  = Cache::remember('dash_sales', 3600, fn() => Penjualan::select('sales_name')->distinct()->whereNotNull('sales_name')->pluck('sales_name'));
        
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