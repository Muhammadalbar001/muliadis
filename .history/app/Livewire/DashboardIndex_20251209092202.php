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
use Illuminate\Support\Str;

class DashboardIndex extends Component
{
    public $startDate;
    public $endDate;
    public $filterCabang = [];
    public $filterSales = [];

    public function mount()
    {
        $this->startDate = date('Y-m-01');
        $this->endDate = date('Y-m-d');
    }

    public function updated($propertyName) 
    { 
        $this->dispatch('update-charts', data: $this->getChartData());
    }

    public function render()
    {
        // 1. KPI (Total) - Gunakan REPLACE agar angka string terbaca benar
        $salesSum = $this->queryPenjualan()->sum(DB::raw("CAST(REPLACE(REPLACE(total_grand, '.', ''), ',', '.') AS DECIMAL(20,2))"));
        $returSum = $this->queryRetur()->sum(DB::raw("CAST(REPLACE(REPLACE(total_grand, '.', ''), ',', '.') AS DECIMAL(20,2))"));
        $arSum    = $this->queryAR()->sum(DB::raw("CAST(REPLACE(REPLACE(nilai, '.', ''), ',', '.') AS DECIMAL(20,2))"));
        $collSum  = $this->queryCollection()->sum(DB::raw("CAST(REPLACE(REPLACE(receive_amount, '.', ''), ',', '.') AS DECIMAL(20,2))"));
        
        $persenRetur = $salesSum > 0 ? ($returSum / $salesSum) * 100 : 0;

        // 2. TOP RANKING (Ambil 10 Besar)
        $topProduk = $this->queryPenjualan()
            ->selectRaw("nama_item, SUM(CAST(REPLACE(qty, ',', '') AS DECIMAL(20,2))) as total_qty")
            ->groupBy('nama_item')->orderByDesc('total_qty')->limit(10)->get();

        $topCustomer = $this->queryPenjualan()
            ->selectRaw("nama_pelanggan, SUM(CAST(REPLACE(REPLACE(total_grand, '.', ''), ',', '.') AS DECIMAL(20,2))) as total_beli")
            ->groupBy('nama_pelanggan')->orderByDesc('total_beli')->limit(10)->get();

        $topSupplier = $this->queryPenjualan()
            ->selectRaw("supplier, SUM(CAST(REPLACE(REPLACE(total_grand, '.', ''), ',', '.') AS DECIMAL(20,2))) as total_beli")
            ->whereNotNull('supplier')->where('supplier', '!=', '')
            ->groupBy('supplier')->orderByDesc('total_beli')->limit(10)->get();

        // 3. FILTER & CHART DATA
        $optCabang = Cache::remember('dash_cabang', 3600, fn() => Penjualan::select('cabang')->distinct()->whereNotNull('cabang')->pluck('cabang'));
        $optSales  = Cache::remember('dash_sales', 3600, fn() => Penjualan::select('sales_name')->distinct()->whereNotNull('sales_name')->pluck('sales_name'));

        $chartData = $this->getChartData();

        return view('livewire.dashboard-index', compact(
            'salesSum', 'returSum', 'arSum', 'collSum', 'persenRetur',
            'topProduk', 'topCustomer', 'topSupplier',
            'optCabang', 'optSales', 'chartData'
        ))->layout('layouts.app', ['header' => 'Executive Dashboard']);
    }

    private function getChartData()
    {
        $dates = $this->getDatesRange();

        // Query Trend Harian (Dengan REPLACE angka)
        $dailySales = $this->queryPenjualan()->selectRaw("DATE_FORMAT(tgl_penjualan, '%Y-%m-%d') as tgl, SUM(CAST(REPLACE(REPLACE(total_grand, '.', ''), ',', '.') AS DECIMAL(20,2))) as total")->groupBy('tgl')->pluck('total', 'tgl')->toArray();
        $dailyRetur = $this->queryRetur()->selectRaw("DATE_FORMAT(tgl_retur, '%Y-%m-%d') as tgl, SUM(CAST(REPLACE(REPLACE(total_grand, '.', ''), ',', '.') AS DECIMAL(20,2))) as total")->groupBy('tgl')->pluck('total', 'tgl')->toArray();
        $dailyAR    = $this->queryAR()->selectRaw("DATE_FORMAT(tgl_penjualan, '%Y-%m-%d') as tgl, SUM(CAST(REPLACE(REPLACE(total_nilai, '.', ''), ',', '.') AS DECIMAL(20,2))) as total")->groupBy('tgl')->pluck('total', 'tgl')->toArray();
        $dailyColl  = $this->queryCollection()->selectRaw("DATE_FORMAT(tanggal, '%Y-%m-%d') as tgl, SUM(CAST(REPLACE(REPLACE(receive_amount, '.', ''), ',', '.') AS DECIMAL(20,2))) as total")->groupBy('tgl')->pluck('total', 'tgl')->toArray();

        // Mapping agar tanggal yang kosong tetap ada nilainya 0
        $dataSales = []; $dataRetur = []; $dataAR = []; $dataColl = [];
        $labels = [];
        
        foreach ($dates as $date) {
            $labels[] = date('d M', strtotime($date)); // Format Tgl: 01 Jan
            $dataSales[] = (float)($dailySales[$date] ?? 0);
            $dataRetur[] = (float)($dailyRetur[$date] ?? 0);
            $dataAR[]    = (float)($dailyAR[$date] ?? 0);
            $dataColl[]  = (float)($dailyColl[$date] ?? 0);
        }

        return [
            'labels' => $labels,
            'sales' => $dataSales, 
            'retur' => $dataRetur, 
            'ar' => $dataAR, 
            'coll' => $dataColl
        ];
    }

    // ... (Fungsi Helper queryPenjualan dll tetap sama seperti kode Anda sebelumnya) ...
    // Pastikan fungsi baseFilter, queryPenjualan, dll ada di sini (copy dari kode Anda yang lama)
    private function baseFilter($query, $dateCol) {
        return $query->whereDate($dateCol, '>=', $this->startDate)->whereDate($dateCol, '<=', $this->endDate)
            ->when(!empty($this->filterCabang), fn($q) => $q->whereIn('cabang', $this->filterCabang))
            ->when(!empty($this->filterSales), fn($q) => $q->whereIn('sales_name', $this->filterSales));
    }
    private function queryPenjualan() { return $this->baseFilter(Penjualan::query(), 'tgl_penjualan'); }
    private function queryRetur()     { return $this->baseFilter(Retur::query(), 'tgl_retur'); }
    private function queryAR()        { return $this->baseFilter(AccountReceivable::query(), 'tgl_penjualan'); }
    private function queryCollection(){ 
        $q = Collection::query()->whereDate('tanggal', '>=', $this->startDate)->whereDate('tanggal', '<=', $this->endDate)
                ->when(!empty($this->filterCabang), fn($q) => $q->whereIn('cabang', $this->filterCabang));
        if(!empty($this->filterSales)) $q->whereIn('sales_name', $this->filterSales);
        return $q;
    }
    private function getDatesRange() {
        $dates = []; $c = Carbon::parse($this->startDate); $e = Carbon::parse($this->endDate);
        while ($c <= $e) { $dates[] = $c->format('Y-m-d'); $c->addDay(); }
        return $dates;
    }
}