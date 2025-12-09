<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('livewire:init', () => {
    let charts = {};
    const initData = @json($chartData);

    const initTop = {
        pNames: @json($topProduk - > pluck('nama_item')),
        pQty: @json($topProduk - > pluck('total_qty')),
        cNames: @json($topCustomer - > pluck('nama_pelanggan')),
        cVal: @json($topCustomer - > pluck('total_beli')),
        sNames: @json($topSupplier - > pluck('supplier')),
        sVal: @json($topSupplier - > pluck('total_beli'))
    };

    const renderAll = (data) => {

        const font = 'Plus Jakarta Sans, sans-serif';
        const fmtRp = (v) => "Rp " + new Intl.NumberFormat('id-ID').format(v);
        const fmtJt = (v) => (v / 1000000).toFixed(1) + " Jt";

        const hBarOpts = {
            chart: {
                type: 'bar',
                height: 400,
                toolbar: {
                    show: false
                },
                fontFamily: font
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    barHeight: '65%',
                    borderRadius: 3
                }
            },
            dataLabels: {
                enabled: true,
                textAnchor: 'start',
                offsetX: 0,
                formatter: (v) => v,
                style: {
                    fontSize: '11px',
                    colors: ['#fff']
                }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4
            },
            xaxis: {
                labels: {
                    show: false
                }
            }
        };

        /* -------------------------
        FIX: Destroy all charts first
        ------------------------- */
        for (let key in charts) {
            if (charts[key]) charts[key].destroy();
        }
        charts = {};

        /* ==============================
              ALL CHART RENDER
        ===============================*/

        charts.ims = new ApexCharts(document.querySelector("#chart-ims"), {
            ...hBarOpts,
            series: [{
                    name: 'Realisasi',
                    data: data.salesRealIMS
                },
                {
                    name: 'Target',
                    data: data.salesTargetIMS
                }
            ],
            colors: ['#4f46e5', '#e2e8f0'],
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: data.salesNames,
                labels: {
                    formatter: (v) => (v / 1000000).toFixed(0) + "Jt"
                }
            },
            tooltip: {
                y: {
                    formatter: fmtRp
                }
            }
        });
        charts.ims.render();

        charts.salesSupp = new ApexCharts(document.querySelector("#chart-sales-supp"), {
            chart: {
                type: 'bar',
                height: 450,
                stacked: true,
                toolbar: {
                    show: false
                },
                fontFamily: font
            },
            series: data.salesSuppSeries,
            plotOptions: {
                bar: {
                    horizontal: true,
                    barHeight: '70%',
                    borderRadius: 2
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: data.salesNames,
                labels: {
                    formatter: (v) => (v / 1000000).toFixed(0) + "Jt"
                }
            },
            tooltip: {
                y: {
                    formatter: fmtRp
                }
            },
            legend: {
                position: 'top'
            },
            colors: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#94a3b8']
        });
        charts.salesSupp.render();

        charts.arQ = new ApexCharts(document.querySelector("#chart-ar-quality"), {
            ...hBarOpts,
            chart: {
                type: 'bar',
                stacked: true,
                height: 400,
                toolbar: {
                    show: false
                },
                fontFamily: font
            },
            series: [{
                    name: 'Lancar',
                    data: data.salesARLancar
                },
                {
                    name: 'Macet (>30)',
                    data: data.salesARMacet
                }
            ],
            colors: ['#10b981', '#ef4444'],
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: data.salesNames,
                labels: {
                    show: false
                }
            },
            tooltip: {
                y: {
                    formatter: fmtRp
                }
            }
        });
        charts.arQ.render();

        charts.oa = new ApexCharts(document.querySelector("#chart-oa"), {
            ...hBarOpts,
            series: [{
                    name: 'Realisasi',
                    data: data.salesRealOA
                },
                {
                    name: 'Target',
                    data: data.salesTargetOA
                }
            ],
            colors: ['#059669', '#d1fae5'],
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: data.salesNames
            }
        });
        charts.oa.render();

        charts.salesRetur = new ApexCharts(document.querySelector("#chart-sales-retur"), {
            series: [{
                    name: 'Penjualan',
                    data: data.sales
                },
                {
                    name: 'Retur',
                    data: data.retur
                }
            ],
            chart: {
                type: 'area',
                height: 350,
                toolbar: {
                    show: false
                },
                fontFamily: font
            },
            colors: ['#6366f1', '#ef4444'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            xaxis: {
                categories: data.dates,
                labels: {
                    show: false
                }
            },
            yaxis: {
                labels: {
                    formatter: fmtJt
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    opacityFrom: 0.4,
                    opacityTo: 0.05
                }
            },
            tooltip: {
                y: {
                    formatter: fmtRp
                }
            }
        });
        charts.salesRetur.render();

        charts.arColl = new ApexCharts(document.querySelector("#chart-ar-coll"), {
            series: [{
                    name: 'Tagihan Baru',
                    data: data.ar
                },
                {
                    name: 'Bayar',
                    data: data.coll
                }
            ],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: {
                    show: false
                },
                fontFamily: font
            },
            colors: ['#f97316', '#10b981'],
            plotOptions: {
                bar: {
                    borderRadius: 3,
                    columnWidth: '60%'
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: data.dates,
                labels: {
                    show: false
                }
            },
            yaxis: {
                labels: {
                    formatter: fmtJt
                }
            },
            tooltip: {
                y: {
                    formatter: fmtRp
                }
            }
        });
        charts.arColl.render();
    };

    /* ------------------------------------
       FIX UTAMA: RENDER RANKING
    -------------------------------------*/
    const renderTop = () => {

        /* Destroy dulu supaya tidak dobel */
        if (charts.topProd) charts.topProd.destroy();
        if (charts.topCust) charts.topCust.destroy();
        if (charts.topSupp) charts.topSupp.destroy();

        const hBarTop = {
            chart: {
                type: 'bar',
                height: 400,
                toolbar: {
                    show: false
                },
                fontFamily: 'Plus Jakarta Sans'
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    barHeight: '70%',
                    borderRadius: 4
                }
            },
            grid: {
                show: false
            },
            xaxis: {
                labels: {
                    show: false
                }
            }
        };

        charts.topProd = new ApexCharts(document.querySelector("#chart-top-produk"), {
            ...hBarTop,
            series: [{
                name: 'Qty',
                data: initTop.pQty
            }],
            xaxis: {
                categories: initTop.pNames
            },
            colors: ['#3b82f6'],
            dataLabels: {
                enabled: true,
                formatter: (v) => new Intl.NumberFormat('id-ID').format(v) + " Unit",
                style: {
                    fontSize: '11px',
                    colors: ['#fff']
                }
            }
        });
        charts.topProd.render();

        charts.topCust = new ApexCharts(document.querySelector("#chart-top-customer"), {
            ...hBarTop,
            series: [{
                name: 'Total',
                data: initTop.cVal
            }],
            xaxis: {
                categories: initTop.cNames
            },
            colors: ['#8b5cf6'],
            dataLabels: {
                enabled: true,
                formatter: (v) => (v / 1000000).toFixed(1) + " Jt",
                style: {
                    fontSize: '11px',
                    colors: ['#fff']
                }
            },
            tooltip: {
                y: {
                    formatter: (v) => "Rp " + new Intl.NumberFormat('id-ID').format(v)
                }
            }
        });
        charts.topCust.render();

        charts.topSupp = new ApexCharts(document.querySelector("#chart-top-supplier"), {
            ...hBarTop,
            series: [{
                name: 'Omzet',
                data: initTop.sVal
            }],
            xaxis: {
                categories: initTop.sNames
            },
            colors: ['#ec4899'],
            dataLabels: {
                enabled: true,
                formatter: (v) => (v / 1000000).toFixed(1) + " Jt",
                style: {
                    fontSize: '11px',
                    colors: ['#fff']
                }
            },
            tooltip: {
                y: {
                    formatter: (v) => "Rp " + new Intl.NumberFormat('id-ID').format(v)
                }
            }
        });
        charts.topSupp.render();
    };

    /* Render awal */
    renderAll(initData);
    renderTop();

    /* -------------------------------
       FIX: Render ulang saat tab ranking dibuka
    -------------------------------- */
    document.addEventListener('click', (e) => {
        if (e.target.closest('[@click="activeTab = \'ranking\'"]')) {
            setTimeout(() => renderTop(), 50);
        }
    });

    /* FIX: Update grafik setelah filter berubah */
    Livewire.on('update-charts', (event) => {
        renderAll(event.data || event[0].data);
        renderTop(); // FIX
    });
});
</script>