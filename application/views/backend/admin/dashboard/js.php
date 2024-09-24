<!-- <script src="<?= base_url('') ?>assets/vendors/chartjs/Chart.min.js"></script>
<script src="<?= base_url('') ?>assets/vendors/apexcharts/apexcharts.min.js"></script>
<script src="<?= base_url('') ?>assets/js/pages/dashboard.js"></script> -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function loadDashboardData() {
        fetch('<?= base_url('admin/dashboard/get_data') ?>')
            .then(response => response.json())
            .then(data => {
                // Update card statistics
                updateCardStatistics(data);

                // Update charts
                updateBloodStockChart(data.bloodStockByGroup);
                updateDonationUsageChart(data.donationUsageTrend);
            })
            .catch(error => {
                console.error('Error:', error);
                displayErrorMessage('Gagal memuat data dashboard. Silakan coba lagi nanti.');
            });
    }

    function updateCardStatistics(data) {
        document.getElementById('totalBloodStock').textContent = (data.totalBloodStock || 0) + ' kantong';
        document.getElementById('donorsThisMonth').textContent = data.donorsThisMonth || 0;
        document.getElementById('pendingRequests').textContent = data.pendingRequests || 0;
        document.getElementById('transactionsThisMonth').textContent = data.transactionsThisMonth || 0;
    }

    function updateBloodStockChart(data) {
        if (!data || data.length === 0) {
            displayChartError('bloodStockChart', 'Tidak ada data stok darah tersedia.');
            return;
        }

        const labels = data.map(item => item.golongan_darah);
        const values = data.map(item => item.jumlah);

        new Chart(document.getElementById('bloodStockChart'), {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                        '#9966FF', '#FF9F40', '#33CC33', '#996633'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    title: {
                        display: true,
                        text: 'Stok Darah per Golongan'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.label}: ${context.raw} kantong`;
                            }
                        }
                    }
                }
            }
        });
    }

    function updateDonationUsageChart(data) {
        if (!data || data.length === 0) {
            displayChartError('donationUsageChart', 'Tidak ada data tren donasi dan penggunaan tersedia.');
            return;
        }

        const monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        const donations = new Array(12).fill(0);
        const usage = new Array(12).fill(0);

        if (data && data.length > 0) {
            data.forEach(item => {
                const monthIndex = monthLabels.indexOf(item.bulan);
                if (monthIndex !== -1) {
                    donations[monthIndex] = item.donasi;
                    usage[monthIndex] = item.penggunaan;
                }
            });
        }

        console.log(donations, usage);

        new Chart(document.getElementById('donationUsageChart'), {
            type: 'bar',
            data: {
                labels: monthLabels,
                datasets: [{
                        label: 'Donasi',
                        data: donations,
                        backgroundColor: 'rgba(54, 162, 235, 0.8)'
                    },
                    {
                        label: 'Penggunaan',
                        data: usage,
                        backgroundColor: 'rgba(255, 99, 132, 0.8)'
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Tren Donasi vs Penggunaan'
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Bulan'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Unit Darah'
                        }
                    }
                }
            }
        });
    }

    function displayChartError(chartId, message) {
        const ctx = document.getElementById(chartId).getContext('2d');
        ctx.font = '14px Arial';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText(message, ctx.canvas.width / 2, ctx.canvas.height / 2);
    }

    function displayErrorMessage(message) {
        const errorDiv = document.getElementById('errorMessage');
        if (errorDiv) {
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
        } else {
            console.error(message);
        }
    }

    // Panggil fungsi ini saat halaman dimuat
    document.addEventListener('DOMContentLoaded', loadDashboardData);
</script>