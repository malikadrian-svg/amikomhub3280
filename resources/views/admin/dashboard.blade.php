@extends('layouts.admin')

@section('title', 'Admin Dashboard - Analytics')
@section('page_title', 'Analytics Dashboard')

@section('content')
    <!-- Header with Filters -->
    <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-8); flex-wrap: wrap; gap: var(--space-4);">
        <div>
            <h1 class="display" style="margin-bottom: var(--space-2);">ANALYTICS DASHBOARD</h1>
            <p class="body-lg" style="color: var(--slate-400);">Platform performance and growth metrics.</p>
        </div>
        <div style="display: flex; align-items: center; gap: var(--space-4);">
            <select id="dateRangeFilter" style="padding: var(--space-2) var(--space-4); background-color: #ffffff; color: #0f172a; border: 2px solid #0f172a; font-family: 'Space Grotesk', sans-serif; font-weight: 600; font-size: 14px; box-shadow: var(--shadow-hard-sm); cursor: pointer; outline: none;">
                <option value="today">Today</option>
                <option value="7">Last 7 Days</option>
                <option value="30" selected>Last 30 Days</option>
                <option value="90">Last 90 Days</option>
                <option value="year">This Year</option>
            </select>
            
            <div style="text-align: right; margin-left: var(--space-4);">
                <p class="h6" style="margin: 0; color: var(--slate-0);">{{ auth()->user()->name ?? 'Admin' }}</p>
                <p class="caption" style="margin: 0; color: var(--slate-400);">SUPER ADMIN</p>
            </div>
            <div style="width: 48px; height: 48px; background-color: var(--slate-0); border: 1px solid var(--slate-700); box-shadow: var(--shadow-hard-sm); display: flex; align-items: center; justify-content: center; overflow: hidden;">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=8b5cf6&color=ffffff" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
        </div>
    </header>

    <!-- Loading Overlay -->
    <div id="analyticsLoading" style="display: none; padding: var(--space-10); text-align: center; color: var(--purple-600); font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 24px;">
        Sedang memuat data...
    </div>

    <div id="analyticsContent">
        <!-- Stats Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: var(--space-6); margin-bottom: var(--space-8);">
            
            <div class="card" style="padding: var(--space-6);">
                <p class="caption" style="color: var(--slate-400); margin-bottom: var(--space-1);">GMV KESELURUHAN</p>
                <h3 class="h2" style="margin: 0; color: var(--slate-0);" id="summaryGmv">Rp 0</h3>
            </div>

            <div class="card" style="padding: var(--space-6); border-color: var(--green-500);">
                <p class="caption" style="color: var(--slate-400); margin-bottom: var(--space-1);">PENDAPATAN PLATFORM</p>
                <h3 class="h2" style="margin: 0; color: var(--green-500);" id="summaryPlatformFee">Rp 0</h3>
            </div>

            <div class="card" style="padding: var(--space-6);">
                <p class="caption" style="color: var(--slate-400); margin-bottom: var(--space-1);">TIKET TERJUAL</p>
                <h3 class="h2" style="margin: 0; color: var(--slate-0);" id="summaryTickets">0</h3>
            </div>

            <div class="card" style="padding: var(--space-6);">
                <p class="caption" style="color: var(--slate-400); margin-bottom: var(--space-1);">PENGGUNA BARU</p>
                <h3 class="h2" style="margin: 0; color: var(--slate-0);" id="summaryUsers">0</h3>
            </div>
            
            <div class="card" style="padding: var(--space-6);">
                <p class="caption" style="color: var(--slate-400); margin-bottom: var(--space-1);">PENYELENGGARA BARU</p>
                <h3 class="h2" style="margin: 0; color: var(--purple-600);" id="summaryOrgs">0</h3>
            </div>
        </div>

        <!-- Main Charts Area -->
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: var(--space-6); margin-bottom: var(--space-8);">
            
            <!-- Revenue Area Chart -->
            <div class="card" style="padding: 0; overflow: hidden; display: flex; flex-direction: column;">
                <div style="padding: var(--space-4) var(--space-6); border-bottom: 2px solid var(--slate-700);">
                    <h3 class="h4" style="margin: 0;">Pendapatan & GMV</h3>
                </div>
                <div style="padding: var(--space-4); flex: 1;">
                    <div id="revenueChart" style="min-height: 300px;"></div>
                </div>
            </div>

            <!-- Category Donut Chart -->
            <div class="card" style="padding: 0; overflow: hidden; display: flex; flex-direction: column;">
                <div style="padding: var(--space-4) var(--space-6); border-bottom: 2px solid var(--slate-700);">
                    <h3 class="h4" style="margin: 0;">Kategori Terpopuler</h3>
                </div>
                <div style="padding: var(--space-4); flex: 1; display: flex; align-items: center; justify-content: center;">
                    <div id="categoryChart" style="width: 100%;"></div>
                </div>
            </div>

        </div>

        <!-- Secondary Charts Area -->
        <div style="display: grid; grid-template-columns: 1fr; gap: var(--space-6); margin-bottom: var(--space-8);">
            <!-- Platform Growth Chart -->
            <div class="card" style="padding: 0; overflow: hidden;">
                <div style="padding: var(--space-4) var(--space-6); border-bottom: 2px solid var(--slate-700);">
                    <h3 class="h4" style="margin: 0;">Pertumbuhan Platform</h3>
                </div>
                <div style="padding: var(--space-4);">
                    <div id="growthChart" style="min-height: 250px;"></div>
                </div>
            </div>
        </div>

        <!-- Tables Area -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-6); margin-bottom: var(--space-8);">
            
            <!-- Top Organizers List -->
            <div class="card" style="padding: 0; overflow: hidden;">
                <div style="padding: var(--space-4) var(--space-6); border-bottom: 2px solid var(--slate-700);">
                    <h3 class="h4" style="margin: 0;">Top Penyelenggara</h3>
                </div>
                <div style="padding: 0; overflow-x: auto;">
                    <table class="table" style="margin: 0; border: none; box-shadow: none;">
                        <thead>
                            <tr>
                                <th style="border-left: none; padding: var(--space-3) var(--space-6);">Nama</th>
                                <th style="padding: var(--space-3) var(--space-6);">Penjualan</th>
                                <th style="border-right: none; padding: var(--space-3) var(--space-6); text-align: right;">Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody id="topOrgsTableBody">
                            <tr><td colspan="3" style="text-align:center; padding: 20px;">Memuat...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Top Events List -->
            <div class="card" style="padding: 0; overflow: hidden;">
                <div style="padding: var(--space-4) var(--space-6); border-bottom: 2px solid var(--slate-700);">
                    <h3 class="h4" style="margin: 0;">Top Events</h3>
                </div>
                <div style="padding: 0; overflow-x: auto;">
                    <table class="table" style="margin: 0; border: none; box-shadow: none;">
                        <thead>
                            <tr>
                                <th style="border-left: none; padding: var(--space-3) var(--space-6);">Event</th>
                                <th style="padding: var(--space-3) var(--space-6);">Tiket Terjual</th>
                                <th style="border-right: none; padding: var(--space-3) var(--space-6); text-align: right;">Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody id="topEventsTableBody">
                            <tr><td colspan="3" style="text-align:center; padding: 20px;">Memuat...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
@endsection

@stack('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let revenueChart, categoryChart, growthChart;

        const formatCurrency = (val) => {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(val);
        };

        const initCharts = () => {
            // Options for Revenue Chart (Area)
            const revenueOptions = {
                chart: { type: 'area', height: 320, fontFamily: 'Space Grotesk, sans-serif', toolbar: { show: false } },
                colors: ['#8b5cf6', '#22c55e'],
                stroke: { curve: 'smooth', width: 2 },
                fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.7, opacityTo: 0.1, stops: [0, 90, 100] } },
                dataLabels: { enabled: false },
                series: [],
                xaxis: { categories: [] },
                yaxis: { labels: { formatter: (val) => formatCurrency(val) } },
                tooltip: { y: { formatter: (val) => formatCurrency(val) } }
            };
            revenueChart = new ApexCharts(document.querySelector("#revenueChart"), revenueOptions);
            revenueChart.render();

            // Options for Category Chart (Donut)
            const categoryOptions = {
                chart: { type: 'donut', height: 320, fontFamily: 'Space Grotesk, sans-serif' },
                colors: ['#8b5cf6', '#a855f7', '#d946ef', '#f43f5e', '#f97316'],
                series: [],
                labels: [],
                plotOptions: { donut: { size: '65%' } },
                dataLabels: { enabled: false },
                legend: { position: 'bottom' }
            };
            categoryChart = new ApexCharts(document.querySelector("#categoryChart"), categoryOptions);
            categoryChart.render();

            // Options for Growth Chart (Line)
            const growthOptions = {
                chart: { type: 'line', height: 280, fontFamily: 'Space Grotesk, sans-serif', toolbar: { show: false } },
                colors: ['#3b82f6', '#f59e0b'],
                stroke: { curve: 'straight', width: 3 },
                dataLabels: { enabled: false },
                series: [],
                xaxis: { categories: [] }
            };
            growthChart = new ApexCharts(document.querySelector("#growthChart"), growthOptions);
            growthChart.render();
        };

        const loadAnalyticsData = (range) => {
            document.getElementById('analyticsContent').style.opacity = '0.5';
            
            fetch(`/admin/analytics/data?range=${range}`)
                .then(res => res.json())
                .then(data => {
                    // Update Summary Cards
                    document.getElementById('summaryGmv').textContent = formatCurrency(data.summary.gmv);
                    document.getElementById('summaryPlatformFee').textContent = formatCurrency(data.summary.platform_fee);
                    document.getElementById('summaryTickets').textContent = data.summary.tickets_sold.toLocaleString('id-ID');
                    document.getElementById('summaryUsers').textContent = data.summary.new_users.toLocaleString('id-ID');
                    document.getElementById('summaryOrgs').textContent = data.summary.new_orgs.toLocaleString('id-ID');

                    // Update Revenue Chart
                    revenueChart.updateSeries(data.revenue_trend.series);
                    revenueChart.updateOptions({ xaxis: { categories: data.revenue_trend.labels } });

                    // Update Category Chart
                    categoryChart.updateSeries(data.category_distribution.series);
                    categoryChart.updateOptions({ labels: data.category_distribution.labels });

                    // Update Growth Chart
                    growthChart.updateSeries(data.platform_growth.series);
                    growthChart.updateOptions({ xaxis: { categories: data.platform_growth.labels } });

                    // Update Top Orgs Table
                    const tbody = document.getElementById('topOrgsTableBody');
                    tbody.innerHTML = '';
                    if (data.top_organizers.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="3" style="text-align:center; padding: 20px; color: var(--slate-400);">Belum ada data</td></tr>';
                    } else {
                        data.top_organizers.forEach(org => {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td style="border-left: none; padding: var(--space-3) var(--space-6); font-weight: 700;">${org.name}</td>
                                <td style="padding: var(--space-3) var(--space-6);">${org.total_orders} Transaksi</td>
                                <td style="border-right: none; padding: var(--space-3) var(--space-6); text-align: right; color: var(--purple-600); font-weight: 700;">${formatCurrency(org.total_revenue)}</td>
                            `;
                            tbody.appendChild(tr);
                        });
                    }

                    // Update Top Events Table
                    const tbodyEvents = document.getElementById('topEventsTableBody');
                    tbodyEvents.innerHTML = '';
                    if (data.top_events.length === 0) {
                        tbodyEvents.innerHTML = '<tr><td colspan="3" style="text-align:center; padding: 20px; color: var(--slate-400);">Belum ada data</td></tr>';
                    } else {
                        data.top_events.forEach(event => {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td style="border-left: none; padding: var(--space-3) var(--space-6); font-weight: 700; max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="${event.title}">${event.title}</td>
                                <td style="padding: var(--space-3) var(--space-6);">${event.total_orders} Tiket</td>
                                <td style="border-right: none; padding: var(--space-3) var(--space-6); text-align: right; color: var(--purple-600); font-weight: 700;">${formatCurrency(event.total_revenue)}</td>
                            `;
                            tbodyEvents.appendChild(tr);
                        });
                    }

                    document.getElementById('analyticsContent').style.opacity = '1';
                })
                .catch(err => {
                    console.error('Error loading analytics:', err);
                    document.getElementById('analyticsContent').style.opacity = '1';
                });
        };

        // Initialize
        initCharts();
        loadAnalyticsData('30');

        // Filter event listener
        document.getElementById('dateRangeFilter').addEventListener('change', function(e) {
            loadAnalyticsData(e.target.value);
        });
    });
</script>
