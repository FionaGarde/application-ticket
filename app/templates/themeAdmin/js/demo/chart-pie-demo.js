// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

// Pie Chart Example
var ctx = document.getElementById("myPieChart");
var myPieChart = new Chart(ctx, {
  type: 'doughnut',
  data: {
    labels: ["Validés ", "À modérer ", "Refusés "],
    datasets: [{
      data: [1, 2, 1],
      backgroundColor: ['#1cc88a', '#f6c23e', '#e74a3b'],
      hoverBackgroundColor: ['#1cc88a', '#f6c23e', '#e74a3b'],
      hoverBorderColor: "rgba(100, 100, 100, .5)",
    }],
  },
  options: {
    maintainAspectRatio: false,
    tooltips: {
      backgroundColor: "rgb(234,246,236)",
      bodyFontColor: "#6610f2",
      borderColor: '#6610f2',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      caretPadding: 10,
    },
    legend: {
      display: false
    },
    cutoutPercentage: 70,
  },
});
