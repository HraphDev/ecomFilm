function populateCarousel() {
    const consoles = JSON.parse(localStorage.getItem("consoles")) || [];
    const games = JSON.parse(localStorage.getItem("games")) || [];
    const pc = JSON.parse(localStorage.getItem("computers")) || [];
    const phones = JSON.parse(localStorage.getItem("phones")) || [];
    const tvs = JSON.parse(localStorage.getItem("televisions")) || [];
    
    const allProducts = [...consoles, ...games, ...pc, ...phones, ...tvs];
    
    const randomProducts = getRandomProducts(allProducts, 4);

    const carouselItems = document.getElementById('carouselItems');

    if (randomProducts.length === 0) {
      carouselItems.innerHTML = '<p>No products available.</p>';
      return;
    }

    carouselItems.innerHTML = '';

    const numberOfSlides = Math.ceil(randomProducts.length / 4);
    for (let i = 0; i < numberOfSlides; i++) {
      const isActive = i === 0 ? 'active' : ''; 
      carouselItems.innerHTML += `
        <div class="carousel-item ${isActive}">
          <div class="d-flex justify-content-between">
          ${randomProducts.slice(i * 4, i * 4 + 4).map(product => `
            <div class="card mx-2 charm-card" style="width: 18rem;">
              <img src="${product.image}" class="card-img-top" alt="${product.name}">
              <br>
 <center>
              <h5 class="card-title fw-bold mb-3 text-truncate"> <p></p> ${product.name}</h5>
             <center/>
              <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="price-wrapper">
                  <span class="text-danger fw-bold h5 mb-0">$${product.price}</span>
                  <span class="small text-decoration-line-through ms-2">$${(product.price * 1.2).toFixed(2)}</span>
                </div>
                <br>
                <div class="stock-badge ${product.quantity > 0 ? 'text-success' : 'text-danger'}">
                <br>
                  <small>${product.quantity > 0 ? 'In Stock' : 'Out of Stock'}</small>
                </div>
              </div>
            </div>
          `).join('')}
          </div>
        </div>
        
      `;
    }
  }

  function getRandomProducts(products, count) {
    const shuffled = products.sort(() => 0.5 - Math.random());
    return shuffled.slice(0, count);
  }

  document.addEventListener('DOMContentLoaded', () => {
    populateCarousel();

    let carousel = new bootstrap.Carousel('#carouselExample', {
      interval: 5000, 
    });
  });
  