// ============================================
// Vault Charts - Optimized & Reusable
// ============================================

const VaultCharts = (function() {
    'use strict';

    // Configuration
    const CONFIG = {
        colors: {
            deposits: { rgb: '59,130,246', border: 'rgb(59,130,246)' },
            withdrawals: { rgb: '75,85,99', border: 'rgb(75,85,99)' }
        },
        maxRetries: 50,
        retryDelay: 300
    };

    // Utilities
    const Utils = {
        formatNumber(num, decimals = 2, locale = 'en') {
            return new Intl.NumberFormat(locale, {
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals
            }).format(num);
        },

        createGradient(ctx, chartArea, rgb) {
            if (!chartArea) return `rgba(${rgb},0.2)`;
            const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
            gradient.addColorStop(0, `rgba(${rgb},0.02)`);
            gradient.addColorStop(1, `rgba(${rgb},0.28)`);
            return gradient;
        },

        destroyChart(chart) {
            if (chart) {
                try { chart.destroy(); } catch(e) { console.warn('Chart destroy error:', e); }
            }
            return null;
        },

        waitForChartJs(callback, retryCount = 0) {
            if (typeof Chart !== 'undefined' && window.chartsInitialized) {
                callback();
            } else if (retryCount < CONFIG.maxRetries) {
                setTimeout(() => this.waitForChartJs(callback, retryCount + 1), CONFIG.retryDelay);
            } else {
                console.error('Chart.js failed to load');
            }
        },

        async fetchData(url, params = {}) {
            const urlParams = new URLSearchParams(params);
            const response = await fetch(`${url}?${urlParams}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            if (!response.ok) throw new Error('Network response was not ok');
            return await response.json();
        }
    };

    // Chart Options Templates
    const ChartOptions = {
        line(showLegend = true, locale = 'en') {
            return {
                responsive: true,
                maintainAspectRatio: false,
                animation: { duration: 1200, easing: 'easeOutQuart' },
                plugins: {
                    legend: { display: showLegend, position: 'top' },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: (context) => {
                                let label = context.dataset.label || '';
                                if (label) label += ': ';
                                label += Utils.formatNumber(context.parsed.y, 2, locale);
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { display: true, drawBorder: false, drawOnChartArea: true, drawTicks: false },
                        ticks: {
                            callback: (value) => new Intl.NumberFormat(locale, {
                                notation: 'compact',
                                compactDisplay: 'short'
                            }).format(value)
                        }
                    },
                    x: { grid: { display: false, drawBorder: false } }
                }
            };
        },

        bar(locale = 'en') {
            const options = this.line(false, locale);
            return options;
        }
    };

    // Dataset Builder
    function buildDataset(label, data, colorKey, type = 'line') {
        const color = CONFIG.colors[colorKey];
        const dataset = {
            label,
            data,
            borderColor: color.border,
            backgroundColor: (context) => Utils.createGradient(context.chart.ctx, context.chart.chartArea, color.rgb),
            borderWidth: 2
        };

        if (type === 'line') {
            Object.assign(dataset, {
                tension: 0.4,
                fill: true,
                pointBackgroundColor: color.border,
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 3,
                pointHoverRadius: 5
            });
        } else if (type === 'bar') {
            dataset.borderRadius = 6;
        }

        return dataset;
    }

    // Daily Statistics Module
    function DailyStatsChart(options) {
        let chart = null;
        const { canvasId, url, filters, locale, labels } = options;

        async function load(month, currencyId) {
            try {
                month = month || document.getElementById(filters.month)?.value;
                currencyId = currencyId || document.getElementById(filters.currency)?.value;

                const params = {};
                if (month) params.month = month;
                if (currencyId) params.currency_id = currencyId;

                const result = await Utils.fetchData(url, params);
                if (result.success && result.data) {
                    updateStats(result.data);
                    render(result.data.vaultStats);
                }
            } catch (error) {
                console.error('Error loading daily stats:', error);
            }
        }

        function updateStats(data) {
            const elements = {
                totalDeposits: data.totalDeposits,
                totalWithdrawals: data.totalWithdrawals,
                totalTransactions: data.totalTransactions,
                netChange: data.totalDeposits - data.totalWithdrawals
            };

            Object.keys(elements).forEach(key => {
                const el = document.getElementById(key);
                if (el) {
                    el.textContent = typeof elements[key] === 'number' 
                        ? Utils.formatNumber(elements[key], 2, locale) 
                        : elements[key];
                }
            });

            ['monthLabel', 'monthLabel2', 'monthLabel3'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.textContent = data.monthFormatted;
            });

            const lastUpdate = document.getElementById('lastUpdate');
            if (lastUpdate) lastUpdate.textContent = new Date().toLocaleDateString(locale);
        }

        function render(vaultData) {
            const ctx = document.getElementById(canvasId);
            if (!ctx || !vaultData?.length) return;

            Utils.waitForChartJs(() => {
                const chartLabels = vaultData.map(item => 
                    new Date(item.date).toLocaleDateString(locale, { month: 'short', day: 'numeric' })
                );
                const deposits = vaultData.map(item => parseFloat(item.deposits) || 0);
                const withdrawals = vaultData.map(item => parseFloat(item.withdrawals) || 0);

                chart = Utils.destroyChart(chart);
                chart = Utils.destroyChart(Chart.getChart(ctx));

                chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: chartLabels,
                        datasets: [
                            buildDataset(labels.deposits, deposits, 'deposits', 'line'),
                            buildDataset(labels.withdrawals, withdrawals, 'withdrawals', 'line')
                        ]
                    },
                    options: ChartOptions.line(true, locale)
                });
            });
        }

        function init() {
            const monthFilter = document.getElementById(filters.month);
            const currencyFilter = document.getElementById(filters.currency);

            if (!monthFilter || !currencyFilter) {
                setTimeout(init, 200);
                return;
            }

            load();
            monthFilter.addEventListener('change', () => load(monthFilter.value, currencyFilter.value));
            currencyFilter.addEventListener('change', () => load(monthFilter.value, currencyFilter.value));
        }

        return { init };
    }

    // Monthly Overview Module
    function MonthlyOverviewChart(options) {
        let chart = null;
        const { canvasId, url, filters, locale, labels } = options;

        async function load(year, currencyId) {
            try {
                year = year || document.getElementById(filters.year)?.value || new Date().getFullYear();
                currencyId = currencyId || document.getElementById(filters.currency)?.value;

                const params = { year };
                if (currencyId) params.currency_id = currencyId;

                const result = await Utils.fetchData(url, params);
                if (result.success && result.data) {
                    updateStats(result.data);
                    render(result.data.monthlyData);
                }
            } catch (error) {
                console.error('Error loading monthly overview:', error);
            }
        }

        function updateStats(data) {
            document.getElementById('yearTotalDeposits').textContent = Utils.formatNumber(data.totalDeposits, 2, locale);
            document.getElementById('yearTotalWithdrawals').textContent = Utils.formatNumber(data.totalWithdrawals, 2, locale);
        }

        function render(monthlyData) {
            const ctx = document.getElementById(canvasId);
            if (!ctx || !monthlyData?.length) return;

            Utils.waitForChartJs(() => {
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                const deposits = new Array(12).fill(0);
                const withdrawals = new Array(12).fill(0);

                monthlyData.forEach(item => {
                    const monthIndex = item.month - 1;
                    deposits[monthIndex] = parseFloat(item.deposits) || 0;
                    withdrawals[monthIndex] = parseFloat(item.withdrawals) || 0;
                });

                chart = Utils.destroyChart(chart);
                chart = Utils.destroyChart(Chart.getChart(ctx));

                chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: months,
                        datasets: [
                            buildDataset(labels.deposits, deposits, 'deposits', 'line'),
                            buildDataset(labels.withdrawals, withdrawals, 'withdrawals', 'line')
                        ]
                    },
                    options: ChartOptions.line(false, locale)
                });
            });
        }

        function init() {
            const yearFilter = document.getElementById(filters.year);
            const currencyFilter = document.getElementById(filters.currency);

            if (!yearFilter || !currencyFilter) {
                setTimeout(init, 200);
                return;
            }

            load();
            yearFilter.addEventListener('change', () => load(yearFilter.value, currencyFilter.value));
            currencyFilter.addEventListener('change', () => load(yearFilter.value, currencyFilter.value));
        }

        return { init };
    }

    // Comparison Charts Module
    function ComparisonCharts(options) {
        let withdrawalsChart = null;
        let depositsChart = null;
        const { url, filters, locale, labels } = options;

        async function load(currencyId) {
            try {
                currencyId = currencyId || document.getElementById(filters.withdrawals)?.value;

                const params = {};
                if (currencyId) params.currency_id = currencyId;

                const result = await Utils.fetchData(url, params);
                if (result.success && result.data) {
                    updateStats(result.data);
                    renderWithdrawals(result.data);
                    renderDeposits(result.data);
                }
            } catch (error) {
                console.error('Error loading comparison data:', error);
            }
        }

        function updateStats(data) {
            // Withdrawals
            document.getElementById('currentMonthWithdrawals').textContent = Utils.formatNumber(data.currentMonth.withdrawals, 2, locale);
            document.getElementById('previousMonthWithdrawals').textContent = Utils.formatNumber(data.previousMonth.withdrawals, 2, locale);

            const withdrawalsBadge = document.getElementById('withdrawalsChange');
            const withdrawalsChange = data.changes.withdrawals;
            withdrawalsBadge.textContent = (withdrawalsChange >= 0 ? '+' : '') + withdrawalsChange + '%';
            withdrawalsBadge.className = 'badge ' + (withdrawalsChange >= 0 ? 'bg-danger' : 'bg-success');

            // Deposits
            document.getElementById('currentMonthDeposits').textContent = Utils.formatNumber(data.currentMonth.deposits, 2, locale);
            document.getElementById('previousMonthDeposits').textContent = Utils.formatNumber(data.previousMonth.deposits, 2, locale);

            const depositsBadge = document.getElementById('depositsChange');
            const depositsChange = data.changes.deposits;
            depositsBadge.textContent = (depositsChange >= 0 ? '+' : '') + depositsChange + '%';
            depositsBadge.className = 'badge ' + (depositsChange >= 0 ? 'bg-success' : 'bg-danger');
        }

        function renderWithdrawals(data) {
            const ctx = document.getElementById('tokenChart');
            if (!ctx) return;

            Utils.waitForChartJs(() => {
                withdrawalsChart = Utils.destroyChart(withdrawalsChart);
                withdrawalsChart = Utils.destroyChart(Chart.getChart(ctx));

                withdrawalsChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: [data.previousMonthName, data.currentMonthName],
                        datasets: [buildDataset(labels.withdrawals, 
                            [data.previousMonth.withdrawals, data.currentMonth.withdrawals], 
                            'withdrawals', 'bar')]
                    },
                    options: ChartOptions.bar(locale)
                });
            });
        }

        function renderDeposits(data) {
            const ctx = document.getElementById('depositsChart');
            if (!ctx) return;

            Utils.waitForChartJs(() => {
                depositsChart = Utils.destroyChart(depositsChart);
                depositsChart = Utils.destroyChart(Chart.getChart(ctx));

                depositsChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: [data.previousMonthName, data.currentMonthName],
                        datasets: [buildDataset(labels.deposits, 
                            [data.previousMonth.deposits, data.currentMonth.deposits], 
                            'deposits', 'bar')]
                    },
                    options: ChartOptions.bar(locale)
                });
            });
        }

        function init() {
            const withdrawalsFilter = document.getElementById(filters.withdrawals);
            const depositsFilter = document.getElementById(filters.deposits);

            if (!withdrawalsFilter || !depositsFilter) {
                setTimeout(init, 200);
                return;
            }

            load();

            // Sync filters
            withdrawalsFilter.addEventListener('change', function() {
                depositsFilter.value = this.value;
                load(this.value);
            });

            depositsFilter.addEventListener('change', function() {
                withdrawalsFilter.value = this.value;
                load(this.value);
            });
        }

        return { init };
    }

    // Public API
    return {
        DailyStatsChart,
        MonthlyOverviewChart,
        ComparisonCharts
    };
})();
