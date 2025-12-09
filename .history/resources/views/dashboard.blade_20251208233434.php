@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- TITLE --}}
        <h1 class="text-2xl font-bold text-gray-700 mb-4">Dashboard</h1>

        {{-- GRID 3 GRAFIK --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- PRODUK TERLARIS (QTY) --}}
            <div class="bg-white p-4 shadow rounded-lg">
                <h3 class="font-semibold mb-3 text-gray-700">Produk Terlaris (Qty)</h3>
                <canvas id="produkChart" height="200"></canvas>
            </div>

            {{-- PELANGGAN TERBAIK (VALUE) --}}
            <div class="bg-white p-4 shadow rounded-lg">
                <h3 class="font-semibold mb-3 text-gray-700">Pelanggan Terbaik (Value)</h3>
                <canvas id="pelangganChart" height="200"></canvas>
            </div>

            {{-- SUPPLIER (OMZET) --}}
            <div class="bg-white p-4 shadow rounded-lg">
                <h3 class="font-semibold mb-3 text-gray-700">Supplier Berdasarkan Omzet</h3>
                <canvas id="supplierChart" height="200"></canvas>
            </div>

        </div>

    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// DATA DARI BACKEND
const produkLabels = @json($produk_labels);
const produkQty = @json($produk_qty);

const pelangganLabels = @json($pelanggan_labels);
const pelangganValue = @json($pelanggan_value);

const supplierLabels = @json($supplier_labels);
const supplierOmzet = @json($supplier_omzet);

// PRODUK TERLARIS (QTY)
new Chart(document.getElementById('produkChart'), {
    type: 'bar',
    data: {
        labels: produkLabels,
        datasets: [{
            label: 'Qty',
            data: produkQty,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true
    }
});

// PELANGGAN TERBAIK (VALUE)
new Chart(document.getElementById('pelangganChart'), {
    type: 'bar',
    data: {
        labels: pelangganLabels,
        datasets: [{
            label: 'Total Value',
            data: pelangganValue,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true
    }
});

// SUPPLIER (OMZET)
new Chart(document.getElementById('supplierChart'), {
    type: 'bar',
    data: {
        labels: supplierLabels,
        datasets: [{
            label: 'Omzet',
            data: supplierOmzet,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true
    }
});
</script>
@endsection