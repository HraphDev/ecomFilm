function initTheme() {
    const themeToggle = document.getElementById('themeToggle');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)');
    
    // Get saved theme or default to system preference
    const savedTheme = localStorage.getItem('theme') || 
      (prefersDark.matches ? 'dark' : 'light');
    
    document.documentElement.setAttribute('data-theme', savedTheme);
    updateToggleButton(savedTheme);
  
    themeToggle.addEventListener('click', () => {
      const currentTheme = document.documentElement.getAttribute('data-theme');
      const newTheme = currentTheme === 'light' ? 'dark' : 'light';
      
      document.documentElement.setAttribute('data-theme', newTheme);
      localStorage.setItem('theme', newTheme);
      updateToggleButton(newTheme);
    });
  }
  
  function updateToggleButton(theme) {
    const icon = document.querySelector('#themeToggle i');
    if (theme === 'dark') {
      icon.classList.remove('fa-moon');
      icon.classList.add('fa-sun');
    } else {
      icon.classList.remove('fa-sun');
      icon.classList.add('fa-moon');
    }
  }
  
  // Initialize theme when DOM loads
  document.addEventListener('DOMContentLoaded', initTheme);