
        function filterMovies() {
            let searchInput = document.getElementById('searchInput').value.toLowerCase();
            let movies = document.querySelectorAll('.movie-item');

            movies.forEach(function(movie) {
                let title = movie.getAttribute('data-title');
                if (title.includes(searchInput)) {
                    movie.style.display = 'block'; 
                } else {
                    movie.style.display = 'none'; 
                }
            });
        }

        function applyFilters() {
            let categoryFilter = document.getElementById('categoryFilter').value;
            let priceFilter = document.getElementById('priceFilter').value;
            let movies = document.querySelectorAll('.movie-item');

            movies.forEach(function(movie) {
                let category = movie.getAttribute('data-category').toLowerCase();
                let price = parseFloat(movie.getAttribute('data-price'));
                
                let matchesCategory = categoryFilter ? category.includes(categoryFilter.toLowerCase()) : true;
                let matchesPrice = (priceFilter === 'low' && price < 10) || (priceFilter === 'high' && price >= 10) || !priceFilter;

                if (matchesCategory && matchesPrice) {
                    movie.style.display = 'block'; 
                } else {
                    movie.style.display = 'none';
                }
            });
        }

