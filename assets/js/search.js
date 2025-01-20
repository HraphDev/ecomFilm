document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');

    // Get all products from localStorage
    function getAllProducts() {
        const consoles = JSON.parse(localStorage.getItem('consoles')) || [];
        const games = JSON.parse(localStorage.getItem('games')) || [];
        const computers = JSON.parse(localStorage.getItem('computers')) || [];
        const phones = JSON.parse(localStorage.getItem('phones')) || [];
        const tvs = JSON.parse(localStorage.getItem('televisions')) || [];

        return {
            consoles: { items: consoles, path: 'consoles.html' },
            games: { items: games, path: 'games.html' },
            computers: { items: computers, path: 'computers.html' },
            phones: { items: phones, path: 'phones.html' },
            tvs: { items: tvs, path: 'televisions.html' }
        };
    }

    searchInput.addEventListener('input', (e) => {
        const searchTerm = e.target.value.toLowerCase();
        if (searchTerm.length < 2) {
            searchResults.innerHTML = '';
            searchResults.style.display = 'none';
            return;
        }

        const products = getAllProducts();
        let results = '';

        for (const [category, data] of Object.entries(products)) {
            const matches = data.items.filter(item => 
                item.name.toLowerCase().includes(searchTerm)
            );

            matches.forEach(item => {
                results += `
                <div class="search-item" onclick="navigateToProduct('${data.path}', '${item.id}')">
                    <img src="${item.image}" alt="${item.name}" class="search-item-img">
                    <div class="search-item-details">
                        <div class="item-name">${item.name}</div>
                        <div class="item-category">${category}</div>
                    </div>
                </div>
            `;
            });
        }

        searchResults.innerHTML = results;
        searchResults.style.display = results ? 'block' : 'none';
    });
});

function navigateToProduct(pagePath, productId) {
    // Store the product ID for detail view
    localStorage.setItem('selectedProductId', productId);
    // Navigate to the product category page
    window.location.href = pagePath;
}