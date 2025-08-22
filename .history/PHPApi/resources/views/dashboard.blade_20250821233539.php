<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticaret Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .sidebar { transition: all 0.3s ease; }
        .product-card { transition: transform 0.2s ease; }
        .product-card:hover { transform: translateY(-2px); }
        .cart-item { transition: all 0.2s ease; }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-gray-900">🛒 E-Ticaret</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <button onclick="toggleCart()" class="relative p-2 text-gray-600 hover:text-gray-900">
                            <i class="fas fa-shopping-cart text-xl"></i>
                            <span id="cartCount" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">0</span>
                        </button>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span id="userName" class="text-gray-700 font-medium">Kullanıcı</span>
                        <button onclick="logout()" class="text-red-600 hover:text-red-800">
                            <i class="fas fa-sign-out-alt"></i> Çıkış
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg min-h-screen">
            <div class="p-4">
                <nav class="space-y-2">
                    <button onclick="showSection('products')" class="nav-btn w-full text-left px-4 py-2 rounded-lg hover:bg-blue-50 hover:text-blue-600" data-section="products">
                        <i class="fas fa-box mr-3"></i> Ürünler
                    </button>
                    <button onclick="showSection('categories')" class="nav-btn w-full text-left px-4 py-2 rounded-lg hover:bg-blue-50 hover:text-blue-600" data-section="categories">
                        <i class="fas fa-tags mr-3"></i> Kategoriler
                    </button>
                    <button onclick="showSection('cart')" class="nav-btn w-full text-left px-4 py-2 rounded-lg hover:bg-blue-50 hover:text-blue-600" data-section="cart">
                        <i class="fas fa-shopping-cart mr-3"></i> Sepetim
                    </button>
                    <button onclick="showSection('orders')" class="nav-btn w-full text-left px-4 py-2 rounded-lg hover:bg-blue-50 hover:text-blue-600" data-section="orders">
                        <i class="fas fa-list mr-3"></i> Siparişlerim
                    </button>
                    <button onclick="showSection('profile')" class="nav-btn w-full text-left px-4 py-2 rounded-lg hover:bg-blue-50 hover:text-blue-600" data-section="profile">
                        <i class="fas fa-user mr-3"></i> Profilim
                    </button>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <!-- Products Section -->
            <div id="products" class="section">
                <div class="mb-6">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Ürünler</h2>
                    <div class="flex space-x-4 mb-6">
                        <select id="categoryFilter" class="px-4 py-2 border rounded-lg">
                            <option value="">Tüm Kategoriler</option>
                        </select>
                        <input type="text" id="searchInput" placeholder="Ürün ara..." class="px-4 py-2 border rounded-lg flex-1">
                        <button onclick="searchProducts()" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                            <i class="fas fa-search"></i> Ara
                        </button>
                    </div>
                </div>
                <div id="productsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <!-- Products will be loaded here -->
                </div>
                <div class="mt-8 flex justify-center">
                    <div id="pagination" class="flex space-x-2">
                        <!-- Pagination will be loaded here -->
                    </div>
                </div>
            </div>

            <!-- Categories Section -->
            <div id="categories" class="section hidden">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Kategoriler</h2>
                <div id="categoriesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Categories will be loaded here -->
                </div>
            </div>

            <!-- Cart Section -->
            <div id="cart" class="section hidden">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Sepetim</h2>
                <div id="cartItems" class="space-y-4">
                    <!-- Cart items will be loaded here -->
                </div>
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-semibold">Toplam: <span id="cartTotal">0.00 TL</span></span>
                        <div class="space-x-2">
                            <button onclick="clearCart()" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                                <i class="fas fa-trash"></i> Sepeti Temizle
                            </button>
                            <button onclick="checkout()" class="px-6 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                                <i class="fas fa-credit-card"></i> Sipariş Ver
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orders Section -->
            <div id="orders" class="section hidden">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Siparişlerim</h2>
                <div id="ordersList" class="space-y-4">
                    <!-- Orders will be loaded here -->
                </div>
            </div>

            <!-- Profile Section -->
            <div id="profile" class="section hidden">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Profilim</h2>
                <div class="max-w-2xl">
                    <form id="profileForm" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ad Soyad</label>
                            <input type="text" id="profileName" class="w-full px-4 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" id="profileEmail" class="w-full px-4 py-2 border rounded-lg" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Yeni Şifre (opsiyonel)</label>
                            <input type="password" id="profilePassword" class="w-full px-4 py-2 border rounded-lg" placeholder="Değiştirmek istemiyorsanız boş bırakın">
                        </div>
                        <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                            <i class="fas fa-save"></i> Profili Güncelle
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Detail Modal -->
    <div id="productModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-2xl w-full p-6">
                <div class="flex justify-between items-start mb-4">
                    <h3 id="modalTitle" class="text-xl font-bold"></h3>
                    <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div id="modalContent" class="space-y-4">
                    <!-- Modal content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        let authToken = null;
        let currentUser = null;
        let cart = [];
        let currentPage = 1;

        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', function() {
            // Check if user is logged in
            const token = localStorage.getItem('authToken');
            const user = localStorage.getItem('user');
            
            if (token && user) {
                authToken = token;
                currentUser = JSON.parse(user);
                document.getElementById('userName').textContent = currentUser.name;
                loadCart();
                showSection('products');
            } else {
                // Redirect to login if not authenticated
                window.location.href = '/';
            }
        });

        // Navigation
        function showSection(sectionName) {
            // Hide all sections
            document.querySelectorAll('.section').forEach(section => {
                section.classList.add('hidden');
            });
            
            // Remove active class from all nav buttons
            document.querySelectorAll('.nav-btn').forEach(btn => {
                btn.classList.remove('bg-blue-50', 'text-blue-600');
            });
            
            // Show selected section
            document.getElementById(sectionName).classList.remove('hidden');
            
            // Add active class to selected nav button
            event.target.classList.add('bg-blue-50', 'text-blue-600');
            
            // Load data for the section
            switch(sectionName) {
                case 'products':
                    loadProducts();
                    break;
                case 'categories':
                    loadCategories();
                    break;
                case 'cart':
                    loadCart();
                    break;
                case 'orders':
                    loadOrders();
                    break;
                case 'profile':
                    loadProfile();
                    break;
            }
        }

        // Products
        async function loadProducts(page = 1) {
            try {
                let url = `/api/products?page=${page}`;
                const categoryFilter = document.getElementById('categoryFilter').value;
                const searchInput = document.getElementById('searchInput').value;
                
                if (categoryFilter) url += `&category_id=${categoryFilter}`;
                if (searchInput) url += `&search=${encodeURIComponent(searchInput)}`;
                
                const response = await fetch(url, {
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();
                if (data.success) {
                    displayProducts(data.data.data);
                    displayPagination(data.data);
                    loadCategoryFilter();
                }
            } catch (error) {
                console.error('Error loading products:', error);
            }
        }

        function displayProducts(products) {
            const grid = document.getElementById('productsGrid');
            grid.innerHTML = products.map(product => `
                <div class="product-card bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">${product.name}</h3>
                        <p class="text-gray-600 text-sm mb-3">${product.description || 'Açıklama yok'}</p>
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-xl font-bold text-green-600">${product.price} TL</span>
                            <span class="text-sm text-gray-500">Stok: ${product.stock_quantity}</span>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="viewProduct(${product.id})" class="flex-1 px-3 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 text-sm">
                                <i class="fas fa-eye"></i> İncele
                            </button>
                            <button onclick="addToCart(${product.id})" class="flex-1 px-3 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 text-sm">
                                <i class="fas fa-cart-plus"></i> Sepete Ekle
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function displayPagination(data) {
            const pagination = document.getElementById('pagination');
            const totalPages = data.last_page;
            const currentPage = data.current_page;
            
            let paginationHtml = '';
            
            if (currentPage > 1) {
                paginationHtml += `<button onclick="loadProducts(${currentPage - 1})" class="px-3 py-2 border rounded-lg hover:bg-gray-50">Önceki</button>`;
            }
            
            for (let i = 1; i <= totalPages; i++) {
                if (i === currentPage) {
                    paginationHtml += `<button class="px-3 py-2 bg-blue-500 text-white rounded-lg">${i}</button>`;
                } else {
                    paginationHtml += `<button onclick="loadProducts(${i})" class="px-3 py-2 border rounded-lg hover:bg-gray-50">${i}</button>`;
                }
            }
            
            if (currentPage < totalPages) {
                paginationHtml += `<button onclick="loadProducts(${currentPage + 1})" class="px-3 py-2 border rounded-lg hover:bg-gray-50">Sonraki</button>`;
            }
            
            pagination.innerHTML = paginationHtml;
        }

        async function loadCategoryFilter() {
            try {
                const response = await fetch('/api/categories', {
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();
                if (data.success) {
                    const select = document.getElementById('categoryFilter');
                    select.innerHTML = '<option value="">Tüm Kategoriler</option>';
                    data.data.forEach(category => {
                        select.innerHTML += `<option value="${category.id}">${category.name}</option>`;
                    });
                }
            } catch (error) {
                console.error('Error loading categories:', error);
            }
        }

        function searchProducts() {
            loadProducts(1);
        }

        // Product Detail
        async function viewProduct(productId) {
            try {
                const response = await fetch(`/api/products/${productId}`, {
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();
                if (data.success) {
                    const product = data.data;
                    document.getElementById('modalTitle').textContent = product.name;
                    document.getElementById('modalContent').innerHTML = `
                        <div class="space-y-4">
                            <p class="text-gray-600">${product.description || 'Açıklama yok'}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-2xl font-bold text-green-600">${product.price} TL</span>
                                <span class="text-gray-500">Stok: ${product.stock_quantity}</span>
                            </div>
                            <div class="flex space-x-2">
                                <input type="number" id="quantityInput" value="1" min="1" max="${product.stock_quantity}" class="px-3 py-2 border rounded-lg w-20">
                                <button onclick="addToCart(${product.id}, document.getElementById('quantityInput').value)" class="flex-1 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                                    <i class="fas fa-cart-plus"></i> Sepete Ekle
                                </button>
                            </div>
                        </div>
                    `;
                    document.getElementById('productModal').classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error loading product:', error);
            }
        }

        function closeModal() {
            document.getElementById('productModal').classList.add('hidden');
        }

        // Cart
        async function loadCart() {
            try {
                const response = await fetch('/api/cart', {
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();
                if (data.success) {
                    cart = data.data.items || [];
                    displayCart();
                    updateCartCount();
                }
            } catch (error) {
                console.error('Error loading cart:', error);
            }
        }

        function displayCart() {
            const cartContainer = document.getElementById('cartItems');
            if (cart.length === 0) {
                cartContainer.innerHTML = '<p class="text-gray-500 text-center py-8">Sepetiniz boş</p>';
                document.getElementById('cartTotal').textContent = '0.00 TL';
                return;
            }

            cartContainer.innerHTML = cart.map(item => `
                <div class="cart-item bg-white rounded-lg shadow p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <h4 class="font-semibold">${item.product.name}</h4>
                            <p class="text-gray-600">${item.product.price} TL</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button onclick="updateCartItem(${item.product_id}, ${item.quantity - 1})" class="px-2 py-1 bg-gray-200 rounded">-</button>
                            <span class="px-3 py-1">${item.quantity}</span>
                            <button onclick="updateCartItem(${item.product_id}, ${item.quantity + 1})" class="px-2 py-1 bg-gray-200 rounded">+</button>
                            <button onclick="removeFromCart(${item.product_id})" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');

            const total = cart.reduce((sum, item) => sum + (item.product.price * item.quantity), 0);
            document.getElementById('cartTotal').textContent = total.toFixed(2) + ' TL';
        }

        function updateCartCount() {
            const count = cart.reduce((sum, item) => sum + item.quantity, 0);
            document.getElementById('cartCount').textContent = count;
        }

        async function addToCart(productId, quantity = 1) {
            try {
                const response = await fetch('/api/cart/add', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: parseInt(quantity)
                    })
                });

                const data = await response.json();
                if (data.success) {
                    loadCart();
                    closeModal();
                    showNotification('Ürün sepete eklendi!', 'success');
                }
            } catch (error) {
                console.error('Error adding to cart:', error);
                showNotification('Ürün sepete eklenirken hata oluştu!', 'error');
            }
        }

        async function updateCartItem(productId, quantity) {
            if (quantity <= 0) {
                removeFromCart(productId);
                return;
            }

            try {
                const response = await fetch('/api/cart/update', {
                    method: 'PUT',
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantity
                    })
                });

                const data = await response.json();
                if (data.success) {
                    loadCart();
                }
            } catch (error) {
                console.error('Error updating cart:', error);
            }
        }

        async function removeFromCart(productId) {
            try {
                const response = await fetch(`/api/cart/remove/${productId}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();
                if (data.success) {
                    loadCart();
                    showNotification('Ürün sepetten çıkarıldı!', 'success');
                }
            } catch (error) {
                console.error('Error removing from cart:', error);
            }
        }

        async function clearCart() {
            if (!confirm('Sepeti temizlemek istediğinizden emin misiniz?')) return;

            try {
                const response = await fetch('/api/cart/clear', {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();
                if (data.success) {
                    loadCart();
                    showNotification('Sepet temizlendi!', 'success');
                }
            } catch (error) {
                console.error('Error clearing cart:', error);
            }
        }

        // Orders
        async function loadOrders() {
            try {
                const response = await fetch('/api/orders', {
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();
                if (data.success) {
                    displayOrders(data.data);
                }
            } catch (error) {
                console.error('Error loading orders:', error);
            }
        }

        function displayOrders(orders) {
            const container = document.getElementById('ordersList');
            if (orders.length === 0) {
                container.innerHTML = '<p class="text-gray-500 text-center py-8">Henüz siparişiniz yok</p>';
                return;
            }

            container.innerHTML = orders.map(order => `
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h4 class="font-semibold text-lg">Sipariş #${order.id}</h4>
                            <p class="text-gray-600">${new Date(order.created_at).toLocaleDateString('tr-TR')}</p>
                        </div>
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">${order.status}</span>
                    </div>
                    <div class="space-y-2">
                        ${order.items.map(item => `
                            <div class="flex justify-between items-center py-2 border-b">
                                <span>${item.product.name} x ${item.quantity}</span>
                                <span>${item.price} TL</span>
                            </div>
                        `).join('')}
                    </div>
                    <div class="mt-4 pt-4 border-t">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold">Toplam:</span>
                            <span class="font-bold text-lg">${order.total_amount} TL</span>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        async function checkout() {
            if (cart.length === 0) {
                showNotification('Sepetiniz boş!', 'error');
                return;
            }

            try {
                const response = await fetch('/api/orders', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();
                if (data.success) {
                    showNotification('Sipariş başarıyla oluşturuldu!', 'success');
                    loadCart();
                    showSection('orders');
                }
            } catch (error) {
                console.error('Error creating order:', error);
                showNotification('Sipariş oluşturulurken hata oluştu!', 'error');
            }
        }

        // Profile
        async function loadProfile() {
            try {
                const response = await fetch('/api/profile', {
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();
                if (data.success) {
                    const user = data.data;
                    document.getElementById('profileName').value = user.name;
                    document.getElementById('profileEmail').value = user.email;
                }
            } catch (error) {
                console.error('Error loading profile:', error);
            }
        }

        document.getElementById('profileForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const name = document.getElementById('profileName').value;
            const password = document.getElementById('profilePassword').value;
            
            const updateData = { name };
            if (password) updateData.password = password;

            try {
                const response = await fetch('/api/profile', {
                    method: 'PUT',
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(updateData)
                });

                const data = await response.json();
                if (data.success) {
                    showNotification('Profil güncellendi!', 'success');
                    currentUser = data.data;
                    document.getElementById('userName').textContent = currentUser.name;
                    localStorage.setItem('user', JSON.stringify(currentUser));
                }
            } catch (error) {
                console.error('Error updating profile:', error);
                showNotification('Profil güncellenirken hata oluştu!', 'error');
            }
        });

        // Categories
        async function loadCategories() {
            try {
                const response = await fetch('/api/categories', {
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();
                if (data.success) {
                    displayCategories(data.data);
                }
            } catch (error) {
                console.error('Error loading categories:', error);
            }
        }

        function displayCategories(categories) {
            const grid = document.getElementById('categoriesGrid');
            grid.innerHTML = categories.map(category => `
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-2">${category.name}</h3>
                    <p class="text-gray-600">${category.description || 'Açıklama yok'}</p>
                    <button onclick="loadProducts(1, ${category.id})" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                        Ürünleri Gör
                    </button>
                </div>
            `).join('');
        }

        // Utility functions
        function logout() {
            localStorage.removeItem('authToken');
            localStorage.removeItem('user');
            window.location.href = '/';
        }

        function showNotification(message, type = 'info') {
            // Simple notification - you can enhance this with a proper notification library
            alert(message);
        }

        function toggleCart() {
            showSection('cart');
        }
    </script>
</body>
</html>
