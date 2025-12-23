<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>PasarKita - Buy Fresh Products</title>
    <link rel="icon" type="image/x-icon" href="image/PasarKita_Logo.jpg">
    <link rel="stylesheet" href="CSS/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google reCAPTCHA API -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        // Global variables
        let currentProduct = null;
        let currentQuantity = 1;
        let productData = [];
        
        document.addEventListener('DOMContentLoaded', function() {
            // Load products from backend (will be replaced with actual API call)
            loadProducts();
            
            // Setup event listeners
            setupFilters();
            
            // Check if user is logged in
            checkLoginStatus();
        });
        
        function loadProducts() {
            // This is dummy data - Replace with actual API call to backend
            productData = [
                {
                    id: 1,
                    name: "Fresh Organic Tomatoes",
                    seller: "Green Valley Farm",
                    price: 12.50,
                    weight: "500g",
                    description: "Freshly picked organic tomatoes, grown without pesticides. Perfect for salads, sauces, and cooking.",
                    image: "https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=400&h=300&fit=crop",
                    category: "Vegetables",
                    location: "Kuala Lumpur"
                },
                {
                    id: 2,
                    name: "Sweet Pineapples",
                    seller: "Tropical Fruits Co.",
                    price: 8.90,
                    weight: "1kg",
                    description: "Sweet and juicy pineapples from Johor. Perfect for juice, desserts, or eating fresh.",
                    image: "https://images.unsplash.com/photo-1550258987-190a2d41a8ba?w=400&h=300&fit=crop",
                    category: "Fruits",
                    location: "Johor"
                },
                {
                    id: 3,
                    name: "Farm Fresh Eggs",
                    seller: "Happy Chicken Farm",
                    price: 15.00,
                    weight: "10 eggs",
                    description: "Free-range eggs from happy chickens. Rich in omega-3 and natural color.",
                    image: "https://images.unsplash.com/photo-1582722872445-44dc5f7e3c8f?w-400&h=300&fit=crop",
                    category: "Dairy & Eggs",
                    location: "Selangor"
                },
                {
                    id: 4,
                    name: "Organic Spinach",
                    seller: "Green Leaf Farm",
                    price: 6.50,
                    weight: "250g",
                    description: "Fresh organic spinach packed with iron and vitamins. Perfect for salads and cooking.",
                    image: "https://images.unsplash.com/photo-1576045057995-568f588f82fb?w=400&h=300&fit=crop",
                    category: "Vegetables",
                    location: "Penang"
                },
                {
                    id: 5,
                    name: "Fresh Milk",
                    seller: "Dairy Delight",
                    price: 9.90,
                    weight: "1 Liter",
                    description: "Fresh milk from grass-fed cows. Pasteurized and packed daily.",
                    image: "https://images.unsplash.com/photo-1563636619-e9143da7973b?w=400&h=300&fit=crop",
                    category: "Dairy & Eggs",
                    location: "Perak"
                },
                {
                    id: 6,
                    name: "Bananas",
                    seller: "Fruit Paradise",
                    price: 5.50,
                    weight: "1kg",
                    description: "Ripe and sweet bananas. Perfect for smoothies or as a healthy snack.",
                    image: "https://images.unsplash.com/photo-1571771894821-ce9b6c11b08e?w=400&h=300&fit=crop",
                    category: "Fruits",
                    location: "Kedah"
                }
            ];
            
            displayProducts(productData);
        }
        
        function displayProducts(products) {
            const productGrid = document.getElementById('productGrid');
            
            if (products.length === 0) {
                productGrid.innerHTML = `
                    <div class="no-products">
                        <i class="fas fa-search" style="font-size: 3rem; margin-bottom: 20px; color: #cbd5e1;"></i>
                        <h3>No products found</h3>
                        <p>Try adjusting your filters</p>
                    </div>
                `;
                return;
            }
            
            productGrid.innerHTML = '';
            
            products.forEach(product => {
                const productCard = document.createElement('div');
                productCard.className = 'product-card';
                productCard.onclick = () => openProductModal(product);
                
                productCard.innerHTML = `
                    <img src="${product.image}" alt="${product.name}" class="product-image">
                    <div class="product-info">
                        <h3 class="product-title">${product.name}</h3>
                        <p class="product-seller">
                            <i class="fas fa-store"></i> ${product.seller}
                            <span style="float: right;">${product.location}</span>
                        </p>
                        <p class="product-price">RM ${product.price.toFixed(2)}</p>
                        <p class="product-weight"><i class="fas fa-weight-hanging"></i> ${product.weight}</p>
                        <p class="product-description">${product.description.substring(0, 80)}...</p>
                    </div>
                `;
                
                productGrid.appendChild(productCard);
            });
        }
        
        function setupFilters() {
            // Search filter
            document.getElementById('searchInput').addEventListener('keyup', function() {
                filterProducts();
            });
            
            // Category filter
            document.getElementById('categoryFilter').addEventListener('change', function() {
                filterProducts();
            });
            
            // Location filter
            document.getElementById('locationFilter').addEventListener('change', function() {
                filterProducts();
            });
            
            // Price range filter
            document.getElementById('priceRange').addEventListener('input', function() {
                document.getElementById('priceValue').textContent = `RM ${this.value}`;
                filterProducts();
            });
        }
        
        function filterProducts() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const category = document.getElementById('categoryFilter').value;
            const location = document.getElementById('locationFilter').value;
            const maxPrice = parseFloat(document.getElementById('priceRange').value);
            
            const filtered = productData.filter(product => {
                // Search filter
                const matchesSearch = product.name.toLowerCase().includes(searchTerm) || 
                                     product.description.toLowerCase().includes(searchTerm) ||
                                     product.seller.toLowerCase().includes(searchTerm);
                
                // Category filter
                const matchesCategory = category === 'all' || product.category === category;
                
                // Location filter
                const matchesLocation = location === 'all' || product.location === location;
                
                // Price filter
                const matchesPrice = product.price <= maxPrice;
                
                return matchesSearch && matchesCategory && matchesLocation && matchesPrice;
            });
            
            displayProducts(filtered);
        }
        
        function openProductModal(product) {
            currentProduct = product;
            currentQuantity = 1;
            
            // Update modal content
            document.getElementById('modalImage').src = product.image;
            document.getElementById('modalImage').alt = product.name;
            document.getElementById('modalTitle').textContent = product.name;
            document.getElementById('modalSeller').textContent = `Sold by: ${product.seller} (${product.location})`;
            document.getElementById('modalPrice').textContent = `RM ${product.price.toFixed(2)}`;
            document.getElementById('modalWeight').textContent = `Weight: ${product.weight}`;
            document.getElementById('modalDescription').textContent = product.description;
            
            // Update quantity and total
            updateOrderTotal();
            
            // Show modal
            document.getElementById('productModal').style.display = 'flex';
        }
        
        function closeProductModal() {
            document.getElementById('productModal').style.display = 'none';
        }
        
        function updateQuantity(change) {
            currentQuantity += change;
            if (currentQuantity < 1) currentQuantity = 1;
            if (currentQuantity > 99) currentQuantity = 99;
            
            document.getElementById('quantityInput').value = currentQuantity;
            updateOrderTotal();
        }
        
        function updateOrderTotal() {
            if (!currentProduct) return;
            
            const total = currentProduct.price * currentQuantity;
            document.getElementById('totalPrice').textContent = `RM ${total.toFixed(2)}`;
            document.getElementById('summaryPrice').textContent = `RM ${total.toFixed(2)}`;
            document.getElementById('summaryQuantity').textContent = currentQuantity;
            document.getElementById('summaryProduct').textContent = currentProduct.name;
            document.getElementById('summaryWeight').textContent = currentProduct.weight;
        }
        
        function proceedToOrder() {
            if (!currentProduct) return;
            
            // Show confirmation modal
            document.getElementById('productModal').style.display = 'none';
            document.getElementById('confirmationModal').style.display = 'flex';
            
            // Reset reCAPTCHA
            grecaptcha.reset();
            document.getElementById('confirmOrderBtn').disabled = true;
        }
        
        function onRecaptchaSuccess() {
            document.getElementById('confirmOrderBtn').disabled = false;
        }
        
        function confirmOrder() {
            // Here you would send the order to backend
            // For now, we'll show a success message
            
            const orderData = {
                productId: currentProduct.id,
                productName: currentProduct.name,
                quantity: currentQuantity,
                totalPrice: currentProduct.price * currentQuantity,
                seller: currentProduct.seller
            };
            
            // Show loading
            document.getElementById('confirmOrderBtn').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            document.getElementById('confirmOrderBtn').disabled = true;
            
            // Simulate API call
            setTimeout(() => {
                alert(`Order confirmed!\n\nProduct: ${orderData.productName}\nQuantity: ${orderData.quantity}\nTotal: RM ${orderData.totalPrice.toFixed(2)}\n\nThank you for your order! The seller will contact you shortly.`);
                
                // Close modal
                document.getElementById('confirmationModal').style.display = 'none';
                
                // Reset button
                document.getElementById('confirmOrderBtn').innerHTML = '<i class="fas fa-check-circle"></i> Confirm Order';
                document.getElementById('confirmOrderBtn').disabled = false;
                
                // Reset reCAPTCHA
                grecaptcha.reset();
            }, 1500);
        }
        
        function openChat() {
            if (!currentProduct) return;
            
            // Check if user is logged in
            if (!isLoggedIn()) {
                alert('Please login to chat with the seller');
                window.location.href = 'Login.php';
                return;
            }
            
            // Here you would open chat interface
            // For now, simulate chat opening
            alert(`Opening chat with ${currentProduct.seller} about "${currentProduct.name}"`);
            
            // Close product modal
            closeProductModal();
        }
        
        function checkLoginStatus() {
            // This would check with backend if user is logged in
            // For now, we'll simulate by checking localStorage
            const username = localStorage.getItem('username');
            if (username) {
                document.getElementById('userWelcome').textContent = `Welcome, ${username}`;
                document.getElementById('loginBtn').style.display = 'none';
                document.getElementById('userInfo').style.display = 'block';
            }
        }
        
        function isLoggedIn() {
            // Check if user is logged in
            return localStorage.getItem('username') !== null;
        }
        
        function logout() {
            localStorage.removeItem('username');
            window.location.href = 'Login.php';
        }
        
        // Close modals when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('product-modal')) {
                event.target.style.display = 'none';
            }
        }
    </script>
</head>
<body>
    <!-- Header -->
    <header class="buying-header">
        <div class="header-left">
            <img src="image/PasarKita_Logo.jpg" alt="PasarKita" class="website-logo" style="width: 50px;">
            <h1 style="color: navy; margin: 0;">PasarKita</h1>
        </div>
        
        <div style="display: flex; align-items: center; gap: 20px;">
            <!-- Seller Button -->
            <a href="selling-page.php" class="seller-btn">
                <i class="fas fa-store"></i> Sell Your Products
            </a>
            
            <!-- User Info -->
            <div id="userInfo" style="display: none;">
                <span id="userWelcome" style="color: #475569;"></span>
                <button onclick="logout()" style="margin-left: 10px; padding: 8px 15px; background: #ef4444; color: white; border: none; border-radius: 4px; cursor: pointer;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </div>
            
            <!-- Login Button (shown when not logged in) -->
            <a href="Login.php" id="loginBtn" style="color: #3b82f6; text-decoration: none;">
                <i class="fas fa-user"></i> Login
            </a>
        </div>
    </header>

    <!-- Main Container -->
    <div class="buying-container">
        <h2 style="text-align: center; color: navy; margin-bottom: 20px;">Fresh Products from Local Farmers</h2>
        
        <!-- Filters -->
        <div class="filters-container">
            <div class="filter-group">
                <label for="searchInput"><i class="fas fa-search"></i> Search</label>
                <input type="text" id="searchInput" class="filter-input" placeholder="Search products...">
            </div>
            
            <div class="filter-group">
                <label for="categoryFilter"><i class="fas fa-filter"></i> Category</label>
                <select id="categoryFilter" class="filter-input">
                    <option value="all">All Categories</option>
                    <option value="Vegetables">Vegetables</option>
                    <option value="Fruits">Fruits</option>
                    <option value="Dairy & Eggs">Dairy & Eggs</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="locationFilter"><i class="fas fa-map-marker-alt"></i> Location</label>
                <select id="locationFilter" class="filter-input">
                    <option value="all">All Locations</option>
                    <option value="Kuala Lumpur">Kuala Lumpur</option>
                    <option value="Selangor">Selangor</option>
                    <option value="Johor">Johor</option>
                    <option value="Penang">Penang</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="priceRange">
                    <i class="fas fa-tag"></i> Max Price: <span id="priceValue">RM 50</span>
                </label>
                <input type="range" id="priceRange" class="filter-input" min="1" max="100" value="50">
            </div>
        </div>
        
        <!-- Product Grid -->
        <div class="product-grid" id="productGrid">
            <!-- Products will be loaded here -->
            <div class="loading">
                <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #3b82f6;"></i>
                <p>Loading products...</p>
            </div>
        </div>
    </div>

    <!-- Product Detail Modal -->
    <div class="product-modal" id="productModal">
        <div class="modal-content">
            <button class="modal-close" onclick="closeProductModal()">&times;</button>
            
            <div class="modal-body">
                <div class="modal-image-container">
                    <img id="modalImage" src="" alt="Product Image" class="modal-image">
                </div>
                
                <div class="modal-details">
                    <h2 id="modalTitle" class="modal-title"></h2>
                    <p id="modalSeller" class="modal-seller"></p>
                    <p id="modalPrice" class="modal-price"></p>
                    <p id="modalWeight" class="modal-weight"></p>
                    <p id="modalDescription" class="modal-description"></p>
                    
                    <!-- Order Form -->
                    <div class="order-form">
                        <div class="form-group">
                            <label for="quantityInput"><i class="fas fa-cart-plus"></i> Quantity:</label>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <button onclick="updateQuantity(-1)" style="padding: 5px 15px; background: #cbd5e1; border: none; border-radius: 4px; cursor: pointer;">-</button>
                                <input type="number" id="quantityInput" class="quantity-input" value="1" min="1" max="99" onchange="currentQuantity = parseInt(this.value); updateOrderTotal();">
                                <button onclick="updateQuantity(1)" style="padding: 5px 15px; background: #cbd5e1; border: none; border-radius: 4px; cursor: pointer;">+</button>
                            </div>
                        </div>
                        
                        <div class="total-price">
                            Total: <span id="totalPrice">RM 0.00</span>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="modal-actions">
                        <button class="chat-btn" onclick="openChat()">
                            <i class="fas fa-comment-dots"></i> Chat with Seller
                        </button>
                        <button class="order-btn" onclick="proceedToOrder()">
                            <i class="fas fa-shopping-cart"></i> Buy Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Confirmation Modal -->
    <div class="product-modal confirmation-modal" id="confirmationModal">
        <div class="modal-content confirmation-content">
            <button class="modal-close" onclick="document.getElementById('confirmationModal').style.display='none'">&times;</button>
            
            <div class="confirmation-body">
                <h2 class="confirmation-title"><i class="fas fa-shopping-cart"></i> Confirm Your Order</h2>
                
                <!-- Order Summary -->
                <div class="order-summary">
                    <h3>Order Summary</h3>
                    <div class="summary-item">
                        <span>Product:</span>
                        <span id="summaryProduct">-</span>
                    </div>
                    <div class="summary-item">
                        <span>Weight:</span>
                        <span id="summaryWeight">-</span>
                    </div>
                    <div class="summary-item">
                        <span>Quantity:</span>
                        <span id="summaryQuantity">-</span>
                    </div>
                    <div class="summary-item">
                        <span>Unit Price:</span>
                        <span id="summaryUnitPrice">-</span>
                    </div>
                    <div class="summary-item summary-total">
                        <span>Total Amount:</span>
                        <span id="summaryPrice">-</span>
                    </div>
                </div>
                
                <!-- Payment Instructions -->
                <div style="background: #e0f2fe; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                    <h4><i class="fas fa-info-circle"></i> Payment Instructions</h4>
                    <p>After confirming your order, you will be redirected to our secure payment gateway.</p>
                    <p><strong>Note:</strong> This is a dummy payment system for demonstration.</p>
                </div>
                
                <!-- reCAPTCHA -->
                <div class="recaptcha-container">
                    <div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI" data-callback="onRecaptchaSuccess"></div>
                </div>
                <p style="text-align: center; font-size: 0.9rem; color: #64748b; margin-bottom: 20px;">
                    <i class="fas fa-shield-alt"></i> This helps prevent automated orders
                </p>
                
                <!-- Confirm Button -->
                <button id="confirmOrderBtn" class="confirm-btn" onclick="confirmOrder()" disabled>
                    <i class="fas fa-check-circle"></i> Confirm Order & Proceed to Payment
                </button>
                
                <p style="text-align: center; margin-top: 15px;">
                    <button onclick="document.getElementById('confirmationModal').style.display='none'; document.getElementById('productModal').style.display='flex';" 
                            style="background: none; border: none; color: #3b82f6; cursor: pointer;">
                        <i class="fas fa-arrow-left"></i> Back to product
                    </button>
                </p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer style="text-align: center; padding: 20px; background: #f8fafc; margin-top: 40px; color: #64748b;">
        <p>© 2024 PasarKita - Direct Farmer to Buyer Platform</p>
        <p>Contact: support@pasarkita.com | Phone: +60 12-345 6789</p>
    </footer>
</body>
</html>