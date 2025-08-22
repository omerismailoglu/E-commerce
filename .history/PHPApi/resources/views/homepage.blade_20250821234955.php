<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticaret - Ana Sayfa</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .hero-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .product-card { transition: all 0.3s ease; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold text-gray-900">🛒 E-Ticaret</a>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/" class="text-gray-700 hover:text-blue-600 font-medium">Ana Sayfa</a>
                    <a href="/products" class="text-gray-700 hover:text-blue-600 font-medium">Ürünler</a>
                    <a href="/categories" class="text-gray-700 hover:text-blue-600 font-medium">Kategoriler</a>
                    <a href="/about" class="text-gray-700 hover:text-blue-600 font-medium">Hakkımızda</a>
                    <a href="/contact" class="text-gray-700 hover:text-blue-600 font-medium">İletişim</a>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <button onclick="toggleSearch()" class="p-2 text-gray-600 hover:text-gray-900">
                            <i class="fas fa-search text-xl"></i>
                        </button>
                    </div>
                    <div class="relative">
                        <button onclick="toggleCart()" class="relative p-2 text-gray-600 hover:text-gray-900">
                            <i class="fas fa-shopping-cart text-xl"></i>
                            <span id="cartCount" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">0</span>
                        </button>
                    </div>
                    <div id="authButtons">
                        <a href="/login" class="px-4 py-2 text-blue-600 hover:text-blue-800 font-medium">Giriş Yap</a>
                        <a href="/register" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">Kayıt Ol</a>
                    </div>
                    <div id="userMenu" class="hidden">
                        <span id="userName" class="text-gray-700 font-medium mr-2">Kullanıcı</span>
                        <button onclick="logout()" class="text-red-600 hover:text-red-800">
                            <i class="fas fa-sign-out-alt"></i> Çıkış
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-gradient text-white py-20">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-5xl font-bold mb-6">Alışverişin En İyi Adresi</h1>
            <p class="text-xl mb-8 max-w-2xl mx-auto">Binlerce ürün, uygun fiyatlar ve hızlı teslimat ile alışveriş deneyiminizi en üst seviyeye çıkarın.</p>
            <div class="flex justify-center space-x-4">
                <a href="/products" class="px-8 py-3 bg-white text-blue-600 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                    <i class="fas fa-shopping-bag mr-2"></i>Alışverişe Başla
                </a>
                <a href="/categories" class="px-8 py-3 border-2 border-white text-white rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-colors">
                    <i class="fas fa-tags mr-2"></i>Kategoriler
                </a>
            </div>
        </div>
    </section>

    <!-- Search Bar -->
    <div id="searchBar" class="hidden bg-white shadow-lg py-4">
        <div class="max-w-4xl mx-auto px-4">
            <div class="flex space-x-4">
                <input type="text" id="searchInput" placeholder="Ürün ara..." class="flex-1 px-4 py-2 border rounded-lg">
                <button onclick="searchProducts()" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-search"></i> Ara
                </button>
            </div>
        </div>
    </div>

    <!-- Featured Categories -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Popüler Kategoriler</h2>
            <div id="categoriesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Categories will be loaded here -->
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Öne Çıkan Ürünler</h2>
            <div id="productsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <!-- Products will be loaded here -->
            </div>
            <div class="text-center mt-12">
                <a href="/products" class="px-8 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                    Tüm Ürünleri Gör
                </a>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shipping-fast text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Hızlı Teslimat</h3>
                    <p class="text-gray-600">Siparişleriniz 24 saat içinde kargoya verilir</p>
                </div>
                <div class="text-center">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-2xl text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Güvenli Ödeme</h3>
                    <p class="text-gray-600">SSL sertifikası ile güvenli alışveriş</p>
                </div>
                <div class="text-center">
                    <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-undo text-2xl text-purple-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Kolay İade</h3>
                    <p class="text-gray-600">14 gün içinde ücretsiz iade hakkı</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter -->
    <section class="py-16 bg-blue-600 text-white">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4">Fırsatları Kaçırmayın!</h2>
            <p class="text-xl mb-8">E-posta listemize katılın ve özel indirimlerden ilk siz haberdar olun.</p>
            <div class="flex max-w-md mx-auto space-x-4">
                <input type="email" placeholder="E-posta adresiniz" class="flex-1 px-4 py-3 rounded-lg text-gray-900">
                <button class="px-6 py-3 bg-white text-blue-600 rounded-lg font-semibold hover:bg-gray-100">
                    Abone Ol
                </button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">🛒 E-Ticaret</h3>
                    <p class="text-gray-400">Güvenilir alışveriş deneyimi için buradayız.</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Hızlı Linkler</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="/products" class="hover:text-white">Ürünler</a></li>
                        <li><a href="/categories" class="hover:text-white">Kategoriler</a></li>
                        <li><a href="/about" class="hover:text-white">Hakkımızda</a></li>
                        <li><a href="/contact" class="hover:text-white">İletişim</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Müşteri Hizmetleri</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="/help" class="hover:text-white">Yardım</a></li>
                        <li><a href="/shipping" class="hover:text-white">Kargo Bilgileri</a></li>
                        <li><a href="/returns" class="hover:text-white">İade Koşulları</a></li>
                        <li><a href="/privacy" class="hover:text-white">Gizlilik Politikası</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">İletişim</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><i class="fas fa-phone mr-2"></i>+90 555 123 4567</li>
                        <li><i class="fas fa-envelope mr-2"></i>info@eticaret.com</li>
                        <li><i class="fas fa-map-marker-alt mr-2"></i>İstanbul, Türkiye</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2024 E-Ticaret. Tüm hakları saklıdır.</p>
            </div>
        </div>
    </footer>

    <script>
        let authToken = null;
        let currentUser = null;
        let cart = [];

        // Initialize homepage
        document.addEventListener('DOMContentLoaded', function() {
            // Check if user is logged in
            const token = localStorage.getItem('authToken');
            const user = localStorage.getItem('user');
            
            if (token && user) {
                authToken = token;
                currentUser = JSON.parse(user);
                showUserMenu();
                loadCart();
            }
            
            loadCategories();
            loadFeaturedProducts();
        });

        function showUserMenu() {
            document.getElementById('authButtons').classList.add('hidden');
            document.getElementById('userMenu').classList.remove('hidden');
            document.getElementById('userName').textContent = currentUser.name;
        }

        function toggleSearch() {
            const searchBar = document.getElementById('searchBar');
            searchBar.classList.toggle('hidden');
        }

        function toggleCart() {
            if (authToken) {
                window.location.href = '/dashboard?section=cart';
            } else {
                window.location.href = '/login';
            }
        }

        function searchProducts() {
            const query = document.getElementById('searchInput').value;
            if (query.trim()) {
                window.location.href = `/products?search=${encodeURIComponent(query)}`;
            }
        }

        // Load categories
        async function loadCategories() {
            try {
                const response = await fetch('/api/categories');
                const data = await response.json();
                if (data.success) {
                    displayCategories(data.data.slice(0, 4)); // Show only 4 categories
                }
            } catch (error) {
                console.error('Error loading categories:', error);
            }
        }

        function displayCategories(categories) {
            const grid = document.getElementById('categoriesGrid');
            grid.innerHTML = categories.map(category => `
                <div class="bg-white rounded-lg shadow-md p-6 text-center product-card">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-tags text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">${category.name}</h3>
                    <p class="text-gray-600 text-sm mb-4">${category.description || 'Ürün kategorisi'}</p>
                    <a href="/products?category=${category.id}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Ürünleri Gör
                    </a>
                </div>
            `).join('');
        }

        // Load featured products
        async function loadFeaturedProducts() {
            try {
                const response = await fetch('/api/products?limit=8');
                const data = await response.json();
                if (data.success) {
                    displayProducts(data.data.data);
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
                        <p class="text-gray-600 text-sm mb-3">${product.description || 'Ürün açıklaması'}</p>
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

        // Cart functions
        async function loadCart() {
            if (!authToken) return;
            
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
                    updateCartCount();
                }
            } catch (error) {
                console.error('Error loading cart:', error);
            }
        }

        function updateCartCount() {
            const count = cart.reduce((sum, item) => sum + item.quantity, 0);
            document.getElementById('cartCount').textContent = count;
        }

        async function addToCart(productId) {
            if (!authToken) {
                window.location.href = '/login';
                return;
            }

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
                        quantity: 1
                    })
                });

                const data = await response.json();
                if (data.success) {
                    loadCart();
                    showNotification('Ürün sepete eklendi!', 'success');
                }
            } catch (error) {
                console.error('Error adding to cart:', error);
                showNotification('Ürün sepete eklenirken hata oluştu!', 'error');
            }
        }

        function viewProduct(productId) {
            window.location.href = `/products/${productId}`;
        }

        function logout() {
            localStorage.removeItem('authToken');
            localStorage.removeItem('user');
            window.location.href = '/login';
        }

        function showNotification(message, type = 'info') {
            // Simple notification
            alert(message);
        }
    </script>
</body>
</html>
