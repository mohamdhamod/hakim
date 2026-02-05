// Lazy-loading Chart.js for dashboard charts
// Track if Chart.js is already loaded
let chartsInitialized = false;

async function initializeCharts() {
  // Prevent double initialization
  if (chartsInitialized) return;

  // Check if contentChart exists on the page
  const contentChart = document.getElementById('contentChart');
  if (!contentChart) return;

  // Dynamically import Chart.js only when needed
  const ChartModule = await import('chart.js/auto');
  const Chart = ChartModule && ChartModule.default ? ChartModule.default : ChartModule;

  // Make Chart globally available
  window.Chart = Chart;
  
  chartsInitialized = true;
  window.chartsInitialized = true;

  // Configure Chart.js global defaults
  if (Chart.defaults) {
    Chart.defaults.animation = Chart.defaults.animation || {};
    Chart.defaults.animation.duration = 1000;
    Chart.defaults.animation.easing = 'easeOutQuart';
    Chart.defaults.responsive = true;
  }
}

// Auto-initialize when DOM is ready
function initChartsWhenReady() {
  if (document.readyState === 'complete') {
    initializeCharts();
  } else {
    window.addEventListener('load', initializeCharts);
  }
}

// Expose initializeCharts for manual initialization
window.initializeCharts = initializeCharts;

initChartsWhenReady();

