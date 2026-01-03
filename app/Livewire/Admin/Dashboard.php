<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Master\SalesTarget;
use App\Models\Master\Sales;
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

        $dailySales = $this->baseFilter(Penjualan::query(), 'tgl_penjualan')
            ->selectRaw("DATE(tgl_penjualan) as tgl, SUM(CAST(total_grand AS DECIMAL(20,2))) as total")
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

        $topProduk = $this->baseFilter(Penjualan::query(), 'tgl_penjualan')
            ->when(!empty($this->filterSupplierTopProduk), fn($q) => $q->whereIn('supplier', $this->filterSupplierTopProduk))
            ->selectRaw("nama_item, SUM(CAST(qty AS DECIMAL(20,2))) as total")
            ->groupBy('nama_item')->orderByDesc('total')->limit(10)->get();

        $topCustomer = $this->baseFilter(Penjualan::query(), 'tgl_penjualan')
            ->when(!empty($this->filterSalesTopCust), fn($q) => $q->whereIn('sales_name', $this->filterSalesTopCust))
            ->selectRaw("nama_pelanggan, SUM(CAST(total_grand AS DECIMAL(20,2))) as total")
            ->groupBy('nama_pelanggan')->orderByDesc('total')->limit(10)->get();

        $topSupplier = $this->baseFilter(Penjualan::query(), 'tgl_penjualan')
            ->when(!empty($this->filterKategoriTopSupp), function($q) {
                $items = Produk::whereIn('kategori', $this->filterKategoriTopSupp)->pluck('name_item');
                $q->whereIn('nama_item', $items);
            })
            ->selectRaw("supplier, SUM(CAST(total_grand AS DECIMAL(20,2))) as total")
            ->groupBy('supplier')->orderByDesc('total')->limit(10)->get();

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
        $activeSales = Sales::where('status', 'Active')->get(); 
        $salesIds = $activeSales->pluck('id')->toArray();
        $salesCodes = $activeSales->pluck('sales_code')->toArray();

        $start = $this->startDate ? Carbon::parse($this->startDate) : Carbon::now()->startOfMonth();
        $end   = $this->endDate ? Carbon::parse($this->endDate) : Carbon::now();

        $targets = SalesTarget::whereIn('sales_id', $salesIds)
            ->where(function($q) use ($start, $end) {
                 $q->where(function($sub) use ($start) {
                     $sub->where('year', '>', $start->year)
                         ->orWhere(fn($s) => $s->where('year', $start->year)->where('month', '>=', $start->month));
                 })->where(function($sub) use ($end) {
                     $sub->where('year', '<', $end->year)
                         ->orWhere(fn($s) => $s->where('year', $end->year)->where('month', '<=', $end->month));
                 });
            })->get();

        $targetMap = [];
        foreach ($targets as $t) {
            $targetMap[$t->sales_id] = ($targetMap[$t->sales_id] ?? 0) + (float) $t->target_ims;
        }

        $realMap = Penjualan::query()
            ->whereDate('tgl_penjualan', '>=', $this->startDate)
            ->whereDate('tgl_penjualan', '<=', $this->endDate)
            ->whereIn('kode_sales', $salesCodes)
            ->when(!empty($this->filterCabang), fn($q) => $q->whereIn('cabang', $this->filterCabang))
            ->selectRaw("kode_sales, SUM(CAST(total_grand AS DECIMAL(20,2))) as total")
            ->groupBy('kode_sales')
            ->pluck('total', 'kode_sales')
            ->toArray();

        $details = [];
        $totalTargetGlobal = 0;
        $totalRealGlobal = 0;

        foreach ($activeSales as $sales) {
            $target = $targetMap[$sales->id] ?? 0;
            $real = (float) ($realMap[$sales->sales_code] ?? 0);

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

        usort($details, fn($a, $b) => $b['persen'] <=> $a['persen']);

        $chartDataRaw = $details;
        usort($chartDataRaw, fn($a, $b) => $b['real'] <=> $a['real']); 
        $chartDataLimit = array_slice($chartDataRaw, 0, 10); 

        return [
            'chart_names'  => array_column($chartDataLimit, 'name'),
            'chart_real'   => array_column($chartDataLimit, 'real'),
            'chart_target' => array_column($chartDataLimit, 'target'),
            'details'      => $details, 
            'total_target' => $totalTargetGlobal,
            'total_real'   => $totalRealGlobal
        ];
    }

    public function render()
    {
        $optCabang = Cache::remember('dash_cabang', 3600, fn() => Penjualan::select('cabang')->distinct()->whereNotNull('cabang')->pluck('cabang'));
        $optSales  = Cache::remember('dash_sales', 3600, fn() => Sales::select('sales_name')->where('status', 'Active')->orderBy('sales_name')->pluck('sales_name'));
        
        $optSupplierList = Cache::remember('dash_opt_supp', 3600, fn() => Produk::select('supplier')->whereNotNull('supplier')->distinct()->orderBy('supplier')->pluck('supplier'));
        $optKategoriList = Cache::remember('dash_opt_cat', 3600, fn() => Produk::select('kategori')->whereNotNull('kategori')->where('kategori','!=','')->distinct()->orderBy('kategori')->pluck('kategori'));

        return view('livewire.admin.dashboard', array_merge(
            $this->kpiStats, 
            ['optCabang' => $optCabang, 'optSales' => $optSales, 'chartData' => $this->chartData, 'optSupplierList' => $optSupplierList, 'optKategoriList' => $optKategoriList]
        ))->layout('layouts.app', ['header' => 'Executive Dashboard']);
    }

    public function formatCompact($val)
    {
        if ($val >= 1000000000) { return number_format($val / 1000000000, 2, ',', '.') . ' M'; 
        } elseif ($val >= 1000000) { return number_format($val / 1000000, 1, ',', '.') . ' Jt'; 
        } else { return number_format($val, 0, ',', '.'); }
    }
}