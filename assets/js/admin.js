document.addEventListener('DOMContentLoaded', function() {
    // Initialize charts
    initCharts();
    
    // Toggle sidebar on mobile
    document.getElementById('sidebarToggle').addEventListener('click', function() {
      document.getElementById('sidebar').classList.toggle('show');
    });
    
    // Tooltip initialization
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Add active class to current nav item
    const currentPage = window.location.pathname.split('/').pop();
    document.querySelectorAll('.nav-link').forEach(link => {
      if (link.getAttribute('href') === currentPage) {
        link.classList.add('active');
      }
    });
  });
  
  // Initialize Charts
  function initCharts() {
    // Sales Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(salesCtx, {
      type: 'line',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
        datasets: [{
          label: 'Sales',
          data: [12000, 19000, 15000, 22000, 21000, 25000, 28000],
          backgroundColor: 'rgba(67, 97, 238, 0.1)',
          borderColor: '#4361ee',
          borderWidth: 2,
          tension: 0.4,
          fill: true
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            mode: 'index',
            intersect: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              drawBorder: false
            }
          },
          x: {
            grid: {
              display: false
            }
          }
        }
      }
    });
    
    // Traffic Chart
    const trafficCtx = document.getElementById('trafficChart').getContext('2d');
    const trafficChart = new Chart(trafficCtx, {
      type: 'doughnut',
      data: {
        labels: ['Direct', 'Social', 'Referral', 'Organic'],
        datasets: [{
          data: [45, 25, 20, 10],
          backgroundColor: [
            '#4361ee',
            '#f72585',
            '#4cc9f0',
            '#4895ef'
          ],
          borderWidth: 0
        }]
      },
      options: {
        responsive: true,
        cutout: '70%',
        plugins: {
          legend: {
            position: 'bottom'
          }
        }
      }
    });
    
    // Resize charts on window resize
    window.addEventListener('resize', function() {
      salesChart.resize();
      trafficChart.resize();
    });
  }
  
  // Real-time updates simulation
  setInterval(function() {
    fetch('api/dashboard_stats.php')
      .then(response => response.json())
      .then(data => {
        // Update stats cards
        document.querySelectorAll('.stat-card .card-title').forEach((el, index) => {
          if (index === 3) { // Revenue card
            el.textContent = '$' + data.revenue.toLocaleString('en-US', {minimumFractionDigits: 2});
          } else {
            el.textContent = data[Object.keys(data)[index]];
          }
        });
      })
      .catch(error => console.error('Error fetching stats:', error));
  }, 30000); // Update every 30 seconds
  