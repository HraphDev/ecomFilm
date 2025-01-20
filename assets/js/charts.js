
// Get product data from localStorage
const productsData = JSON.parse(localStorage.getItem("products")) || {};
const consoles = JSON.parse(localStorage.getItem("consoles")) || {};
const games = JSON.parse(localStorage.getItem("games")) || {};
const pc = JSON.parse(localStorage.getItem("computers")) || {};
const phones = JSON.parse(localStorage.getItem("phones")) || {};
const tvs = JSON.parse(localStorage.getItem("televisions")) || {};

// Initialize chart contexts
const progressCtx = document.getElementById('progressChart').getContext('2d');

// Progress Chart (Line)
const progressChart = new Chart(progressCtx , {
  type: 'bar',
  data: {
    labels: ['Consoles', 'Jeux', 'Ordinateurs', 'Télévisions', 'Téléphones'],
    datasets: [{
      label: 'Quantité en stock',
      data: [
        consoles?.length || 0,
        games?.length || 0,
        pc?.length || 0,
        tvs?.length || 0,
        phones?.length || 0
      ],
      backgroundColor: 'rgba(54, 162, 235, 0.2)',
      borderColor: 'rgba(54, 162, 235, 1)',
      borderWidth: 1
    }]
  },
  options: {
    responsive: true,
    scales: {
      y: {
        beginAtZero: true,
        title: {
          display: true,
          text: 'Quantité'
        }
      }
    }
  }
});

/// Initialize the chart context
const usersCtx = document.getElementById('adminChart').getContext('2d');

// Prepare data directly
const users = JSON.parse(localStorage.getItem('admins')) || [];

// Count roles directly for Super Admin, Developer, and Admin (excluding Editor)
const superAdminCount = users.filter(user => user.role === 'Super Admin').length;
const developerCount = users.filter(user => user.role === 'Developer').length;
const adminCount = users.filter(user => user.role === 'Admin').length;

// Initialize the roles line chart
const usersChart = new Chart(usersCtx, {
  type: 'line', // Line chart for smooth curve
  data: {
    labels: ['Super Admin', 'Developer', 'Admin'], // Labels for the roles
    datasets: [{
      label: 'Role Distribution',
      data: [superAdminCount, developerCount, adminCount], // Role count data
      borderColor: [
        'rgba(255, 99, 132, 1)', 
        'rgba(54, 162, 235, 1)', 
        'rgba(75, 192, 192, 1)'
      ], // Border color for the lines
      backgroundColor: [
        'rgba(255, 99, 132, 0.2)', 
        'rgba(54, 162, 235, 0.2)', 
        'rgba(75, 192, 192, 0.2)'
      ], // Background color under the lines
      fill: true, // Fill the area under the line chart
      tension: 0.4, // Smooth the line curve
      borderWidth: 2 // Width of the lines
    }]
  },
  options: {
    responsive: true,
    animation: {
      duration: 1000, // Duration of the animation (1 second)
      easing: 'easeInOutQuad', // Easing function for smooth animation
    },
    plugins: {
      legend: {
        display: false, // Hide the legend
      },
    },
    scales: {
      y: {
        beginAtZero: true, // Start Y-axis from zero
        max: Math.max(superAdminCount, developerCount, adminCount) + 1, // Adjust max value
      }
    }
  }
});
