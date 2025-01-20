 // Initialize data
 let products = JSON.parse(localStorage.getItem(pageKey)) || [];


 function renderProducts() {
    const productCards = document.getElementById("productCards");
    productCards.innerHTML = products.map((product, index) => `
      <div class="col-md-4 mb-4">
        <div class="card product-card h-100 border-0 shadow-sm">
          <div class="position-relative">
            <img src="${product.image}" class="card-img-top" alt="${product.name}" 
                 style="height: 280px; object-fit: cover;">
            <span class="badge bg-danger position-absolute top-0 start-0 m-3">Sale</span>
          </div>
          <div class="card-body p-4">
            <div class="small mb-1">${product.category || 'Category'}</div>
            <h5 class="card-title fw-bold mb-3 text-truncate">${product.name}</h5>
            <div class="d-flex justify-content-between align-items-center mb-2">
              <div class="price-wrapper">
                <span class="text-danger fw-bold h5 mb-0">$${product.price}</span>
                <span class="small text-decoration-line-through ms-2">$${(product.price * 1.2).toFixed(2)}</span>
              </div>
              <div class="stock-badge ${product.quantity > 0 ? 'text-success' : 'text-danger'}">
                <small>${product.quantity > 0 ? 'In Stock' : 'Out of Stock'}</small>
              </div>
            </div>
            <p class="card-text small mb-3 text-truncate">${product.description}</p>
            <div class="d-grid gap-2">
              <button onclick="viewProduct(${index})" class="btn btn-primary btn-sm"> <i class="fas fa-eye"></i> Detail
                
              </button>
              <div class="btn-group">
                <button onclick="editProduct(${index})" class="btn btn-outline-secondary btn-sm flex-grow-1">
                  <i class="fas fa-edit"></i>
                </button>
                <button onclick="deleteProduct(${index})" class="btn btn-outline-danger btn-sm flex-grow-1">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    `).join("");
  }
function viewProduct(index) {
    const products = JSON.parse(localStorage.getItem(pageKey)) || [];
    const product = products[index];
    
    document.getElementById('viewProductName').textContent = product.name;
    document.getElementById('viewProductDescription').textContent = product.description;
    document.getElementById('viewProductPrice').textContent = product.price;
    document.getElementById('viewProductQuantity').textContent = product.quantity;
    document.getElementById('viewProductImage').src = product.image;
  
    const modal = new bootstrap.Modal(document.getElementById('viewProductModal'));
    modal.show();
  }
function saveProduct(event) {
event.preventDefault(); 

const index = document.getElementById("productIndex").value;
const name = document.getElementById("productName").value;
const price = document.getElementById("productPrice").value;
const description = document.getElementById("productDescription").value;
const quantity = document.getElementById("productQuantity").value;
const imageInput = document.getElementById("productImage").files[0];

const reader = new FileReader();
reader.onloadend = () => {
  const imageUrl = imageInput ? reader.result : (products[index]?.image || "");
  const product = { name, price, description, quantity, image: imageUrl };

  if (index) {
    products[index] = product; 
  } else {
    products.push(product);
  }

  localStorage.setItem(pageKey, JSON.stringify(products));
  renderProducts();


  const modal = bootstrap.Modal.getInstance(document.getElementById("productModal"));
  modal.hide();
};

if (imageInput) {
  reader.readAsDataURL(imageInput); 
} else {
  reader.onloadend(); 
}
}


function saveProductData(index, name, price, description, imageUrl) {
if (index) {
  products[index] = { name, price, description, image: imageUrl };
} else {
  products.push({ name, price, description, image: imageUrl });
}

localStorage.setItem(pageKey, JSON.stringify(products));
closeProductModal();
renderProducts();
}


function deleteProduct(index) {
if (confirm("Are you sure you want to delete this product?")) {
  products.splice(index, 1);
  localStorage.setItem(pageKey, JSON.stringify(products));
  renderProducts();
}
}


function showAddProductModal() {
const modal = new bootstrap.Modal(document.getElementById("productModal"));
document.getElementById("productForm").reset();
document.getElementById("productIndex").value = "";
modal.show();
}



function editProduct(index) {
const product = products[index];
const modal = new bootstrap.Modal(document.getElementById("productModal"));
document.getElementById("productIndex").value = index;
document.getElementById("productName").value = product.name;
document.getElementById("productPrice").value = product.price;
document.getElementById("productQuantity").value = product.quantity;
document.getElementById("productDescription").value = product.description;
modal.show();
}


function closeProductModal() {
document.getElementById("productModal").style.display = "none";
}

document.getElementById("productForm").addEventListener("submit", saveProduct);
renderProducts();

  function deleteAllProducts() {
    const products = JSON.parse(localStorage.getItem('products'));

    if (products && products.length > 0) {
      if (confirm("Are you sure you want to delete all products?")) {
        localStorage.removeItem('products');
        alert("All products have been deleted.");
        
        document.getElementById('productCards').innerHTML = ''; 
      }
    } else {
      alert("No products found to delete.");
    }
  }
let currentLanguage = 'en';

function loadTranslations() {
  fetch(`assets/lang/${currentLanguage}.json`)
    .then(response => response.json())
    .then(translations => {
      document.querySelectorAll('[data-translate-key]').forEach(element => {
        const key = element.getAttribute('data-translate-key');
        if (translations[key]) {
          if (element.tagName === 'INPUT' || element.tagName === 'TEXTAREA') {
            element.placeholder = translations[key];
          } else {
            element.innerText = translations[key];
          }
        }
      });
    })
    .catch(error => console.error('Error loading translation file:', error));
}

function setLanguage(language) {
  currentLanguage = language;
  document.documentElement.lang = language;
  if (language === 'ar') {
    document.documentElement.setAttribute('dir', 'rtl');
  } else {
    document.documentElement.setAttribute('dir', 'ltr');
  }
  loadTranslations();
}

document.querySelectorAll('.dropdown-item').forEach(item => {
  item.addEventListener('click', (event) => {
    const language = event.target.getAttribute('onclick').split('\'')[1];
    setLanguage(language);
  });
});

loadTranslations();
