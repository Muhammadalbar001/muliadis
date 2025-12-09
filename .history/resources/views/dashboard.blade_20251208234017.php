<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- CARD SUMMARY --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white p-6 shadow rounded-lg">
                <h3 class="text-gray-600 font-semibold mb-2">Total Sales</h3>
                <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($salesSum, 0, ',', '.') }}</p>
            </div>

            <div class="bg-white p-6 shadow rounded-lg">
                <h3 class="text-gray-600 font-semibold mb-2">Total Retur</h3>
                <p class="text-2xl font-bold text-red-500">Rp {{ number_format($returSum, 0, ',', '.') }}</p>
            </div>

            <div class="bg-white p-6 shadow rounded-lg">
                <h3 class="text-gray-600 font-semibold mb-2">Total AR</h3>
                <p class="text-2xl font-bold text-yellow-600">Rp {{ number_format($arSum, 0, ',', '.') }}</p>
            </div>

            <div class="bg-white p-6 shadow rounded-lg">
                <h3 class="text-gray-600 font-semibold mb-2">Collect</h3>
                <p class="text-2xl font-bold text-green-600">Rp {{ number_format($collSum, 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- CHARTS SECTION --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Grafik Omzet --}}
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="font-bold text-gray-700 mb-4 border-b pb-2">OMZET (Sales & Retur)</h3>
                <canvas id="chartOmzet" height="130"></canvas>
            </div>

            {{-- Grafik AR --}}
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="font-bold text-gray-700 mb-4 border-b pb-2">AR & Collect</h3>
                <canvas id="chartAR" height="130"></canvas>
            </div>
        </div>

        {{-- RANKING TERLARIS --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">

            {{-- Top Product --}}
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="font-bold text-gray-700 mb-4 border-b pb-2">TOP 10 PRODUK TERLARIS (Qty)</h3>
                <canvas id="chartTopProduk" height="150"></canvas>
            </div>

            {{-- Top Customer --}}
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="font-bold text-gray-700 mb-4 border-b pb-2">TOP 10 CUSTOMER</h3>
                <canvas id="chartTopCustomer" height="150"></canvas>
            </div>
        </div>

    </div>
</div>


{{-- =============================== --}}
{{-- CHART.JS SCRIPT --}}
{{-- =============================== --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {

    // ======================== DATA GRAFIK ========================
    const labels = @json($chartData['labels']);
    const salesData = @json($chartData['sales']);
    const returData = @json($chartData['retur']);
    const arData = @json($chartData['ar']);
    const collectData = @json($chartData['collect']);

    // Ranking
    const topProductLabels = @json($topProductLabels);
    const topProductData = @json($topProductData);

    const topCustomerLabels = @json($topCustomerLabels);
    const topCustomerData = @json($topCustomerData);


    // ======================== GRAFIK OMZET ========================
    new Chart(document.getElementById("chartOmzet"), {
        type: "line",
        data: {
            labels: labels,
            datasets: [{
                    label: "Sales",
                    data: salesData,
                    borderWidth: 2,
                    fill: false
                },
                {
                    label: "Retur",
                    data: returData,
                    borderWidth: 2,
                    fill: false
                }
            ]
        }
    });


    // ======================== GRAFIK AR ========================
    new Chart(document.getElementById("chartAR"), {
        type: "bar",
        data: {
            labels: labels,
            datasets: [{
                    label: "AR",
                    data: arData,
                    borderWidth: 2
                },
                {
                    label: "Collect",
                    data: collectData,
                    borderWidth: 2
                }
            ]
        }
    });


    // ======================== TOP PRODUK ========================
    new Chart(document.getElementById("chartTopProduk"), {
        type: "bar",
        data: {
            labels: topProductLabels,
            datasets: [{
                label: "Qty Terjual",
                data: topProductData,
                borderWidth: 2
            }]
        }
    });


    // ======================== TOP CUSTOMER ========================
    new Chart(document.getElementById("chartTopCustomer"), {
        type: "bar",
        data: {
            labels: topCustomerLabels,
            datasets: [{
                label: "Total Pembelian",
                data: topCustomerData,
                borderWidth: 2
            }]
        }
    });

});
</script>