<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - E-Ticaret API</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .table-container { max-height: 400px; overflow-y: auto; }
    </style>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">
                🛠️ Admin Panel
            </h1>
            <p class="text-gray-600">Database verilerini yönetin ve API'yi test edin</p>
        </div>

        <!-- Authentication Section -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold mb-4">🔐 Kimlik Doğrulama</h2>
            <div class="grid md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Email:</label>
                    <input type="email" id="adminEmail" value="admin@example.com" class="w-full px-3 py-2 border rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Şifre:</label>
                    <input type="password" id="adminPassword" value="password123" class="w-full px-3 py-2 border rounded-lg">
                </div>
                <div class="flex items-end">
                    <button onclick="adminLogin()" class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                        Admin Girişi
                    </button>
                </div>
            </div>
            <div id="adminStatus" class="text-sm"></div>
        </div>

        <!-- Data Management Tabs -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8 px-6">
                    <button onclick="showTab('categories')" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="categories">
                        📂 Kategoriler
                    </button>
                    <button onclick="showTab('products')" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="products">
                        📦 Ürünler
                    </button>
                    <button onclick="showTab('users')" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="users">
                        👥 Kullanıcılar
                    </button>
                    <button onclick="showTab('orders')" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="orders">
                        📋 Siparişler
                    </button>
                    <button onclick="showTab('stats')" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="stats">
                        📊 İstatistikler
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Categories Tab -->
                <div id="categories" class="tab-content">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Kategoriler</h3>
                        <button onclick="loadCategories()" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                            Yenile
                        </button>
                    </div>
                    <div class="table-container">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ad</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Açıklama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody id="categoriesTable" class="bg-white divide-y divide-gray-200">
                                <!-- Categories will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Products Tab -->
                <div id="products" class="tab-content hidden">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Ürünler</h3>
                        <button onclick="loadProducts()" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                            Yenile
                        </button>
                    </div>
                    <div class="table-container">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ad</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fiyat</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                </tr>
                            </thead>
                            <tbody id="productsTable" class="bg-white divide-y divide-gray-200">
                                <!-- Products will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Users Tab -->
                <div id="users" class="tab-content hidden">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Kullanıcılar</h3>
                        <button onclick="loadUsers()" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                            Yenile
                        </button>
                    </div>
                    <div class="table-container">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ad</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kayıt Tarihi</th>
                                </tr>
                            </thead>
                            <tbody id="usersTable" class="bg-white divide-y divide-gray-200">
                                <!-- Users will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Orders Tab -->
                <div id="orders" class="tab-content hidden">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Siparişler</h3>
                        <button onclick="loadOrders()" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">
                            Yenile
                        </button>
                    </div>
                    <div class="table-container">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kullanıcı</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Toplam</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarih</th>
                                </tr>
                            </thead>
                            <tbody id="ordersTable" class="bg-white divide-y divide-gray-200">
                                <!-- Orders will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Stats Tab -->
                <div id="stats" class="tab-content hidden">
                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="bg-blue-500 text-white p-6 rounded-lg">
                            <h4 class="text-lg font-semibold">Toplam Kullanıcı</h4>
                            <p id="totalUsers" class="text-3xl font-bold">-</p>
                        </div>
                        <div class="bg-green-500 text-white p-6 rounded-lg">
                            <h4 class="text-lg font-semibold">Toplam Sipariş</h4>
                            <p id="totalOrders" class="text-3xl font-bold">-</p>
                        </div>
                        <div class="bg-purple-500 text-white p-6 rounded-lg">
                            <h4 class="text-lg font-semibold">Toplam Ürün</h4>
                            <p id="totalProducts" class="text-3xl font-bold">-</p>
                        </div>
                        <div class="bg-orange-500 text-white p-6 rounded-lg">
                            <h4 class="text-lg font-semibold">Toplam Gelir</h4>
                            <p id="totalRevenue" class="text-3xl font-bold">-</p>
                        </div>
                    </div>
                    <div class="mt-6">
                        <button onclick="loadStats()" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                            İstatistikleri Yenile
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Response Log -->
        <div class="mt-8 bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold mb-4">📤 API Yanıtları</h3>
            <div class="bg-gray-900 text-green-400 p-4 rounded-lg">
                <pre id="responseLog" class="text-sm overflow-x-auto max-h-64 overflow-y-auto">API yanıtları burada görünecek...</pre>
            </div>
        </div>
    </div>

    <script>
        let adminToken = null;

        // Tab management
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active class from all tab buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('border-blue-500', 'text-blue-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Show selected tab content
            document.getElementById(tabName).classList.remove('hidden');
            
            // Add active class to selected tab button
            event.target.classList.remove('border-transparent', 'text-gray-500');
            event.target.classList.add('border-blue-500', 'text-blue-600');
            
            // Load data for the selected tab
            switch(tabName) {
                case 'categories':
                    loadCategories();
                    break;
                case 'products':
                    loadProducts();
                    break;
                case 'users':
                    loadUsers();
                    break;
                case 'orders':
                    loadOrders();
                    break;
                case 'stats':
                    loadStats();
                    break;
            }
        }

        // Admin authentication
        async function adminLogin() {
            const email = document.getElementById('adminEmail').value;
            const password = document.getElementById('adminPassword').value;
            
            try {
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ email, password })
                });

                const data = await response.json();
                
                if (data.success && data.data.user.role === 'admin') {
                    adminToken = data.data.token;
                    document.getElementById('adminStatus').innerHTML = 
                        `<span class="text-green-600">✅ Admin girişi başarılı! Hoş geldiniz, ${data.data.user.name}</span>`;
                    logResponse(data);
                    
                    // Load initial data
                    loadCategories();
                } else {
                    document.getElementById('adminStatus').innerHTML = 
                        `<span class="text-red-600">❌ Admin yetkisi gerekli veya giriş başarısız</span>`;
                    logResponse(data);
                }
            } catch (error) {
                document.getElementById('adminStatus').innerHTML = 
                    `<span class="text-red-600">❌ Hata: ${error.message}</span>`;
            }
        }

        // Data loading functions
        async function loadCategories() {
            if (!adminToken) {
                logResponse({ error: 'Admin token gerekli' });
                return;
            }

            try {
                const response = await fetch('/api/categories', {
                    headers: {
                        'Authorization': `Bearer ${adminToken}`,
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();
                if (data.success) {
                    displayCategories(data.data);
                }
                logResponse(data);
            } catch (error) {
                logResponse({ error: error.message });
            }
        }

        async function loadProducts() {
            if (!adminToken) {
                logResponse({ error: 'Admin token gerekli' });
                return;
            }

            try {
                const response = await fetch('/api/products', {
                    headers: {
                        'Authorization': `Bearer ${adminToken}`,
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();
                if (data.success) {
                    displayProducts(data.data.data);
                }
                logResponse(data);
            } catch (error) {
                logResponse({ error: error.message });
            }
        }

        async function loadUsers() {
            if (!adminToken) {
                logResponse({ error: 'Admin token gerekli' });
                return;
            }

            try {
                const response = await fetch('/api/admin/users/stats', {
                    headers: {
                        'Authorization': `Bearer ${adminToken}`,
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();
                if (data.success) {
                    displayUsers(data.data.recent_users);
                }
                logResponse(data);
            } catch (error) {
                logResponse({ error: error.message });
            }
        }

        async function loadOrders() {
            if (!adminToken) {
                logResponse({ error: 'Admin token gerekli' });
                return;
            }

            try {
                const response = await fetch('/api/admin/dashboard', {
                    headers: {
                        'Authorization': `Bearer ${adminToken}`,
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();
                if (data.success) {
                    displayOrders(data.data.recent_orders);
                }
                logResponse(data);
            } catch (error) {
                logResponse({ error: error.message });
            }
        }

        async function loadStats() {
            if (!adminToken) {
                logResponse({ error: 'Admin token gerekli' });
                return;
            }

            try {
                const response = await fetch('/api/admin/dashboard', {
                    headers: {
                        'Authorization': `Bearer ${adminToken}`,
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();
                if (data.success) {
                    displayStats(data.data.stats);
                }
                logResponse(data);
            } catch (error) {
                logResponse({ error: error.message });
            }
        }

        // Display functions
        function displayCategories(categories) {
            const tbody = document.getElementById('categoriesTable');
            tbody.innerHTML = categories.map(category => `
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${category.id}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${category.name}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${category.description || '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <button class="text-blue-600 hover:text-blue-900">Düzenle</button>
                    </td>
                </tr>
            `).join('');
        }

        function displayProducts(products) {
            const tbody = document.getElementById('productsTable');
            tbody.innerHTML = products.map(product => `
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${product.id}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${product.name}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${product.price} TL</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${product.stock_quantity}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${product.category?.name || '-'}</td>
                </tr>
            `).join('');
        }

        function displayUsers(users) {
            const tbody = document.getElementById('usersTable');
            tbody.innerHTML = users.map(user => `
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${user.id}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${user.name}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${user.email}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${user.role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'}">
                            ${user.role}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${new Date(user.created_at).toLocaleDateString('tr-TR')}</td>
                </tr>
            `).join('');
        }

        function displayOrders(orders) {
            const tbody = document.getElementById('ordersTable');
            tbody.innerHTML = orders.map(order => `
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${order.id}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${order.user?.name || '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${order.total_amount} TL</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            ${order.status}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${new Date(order.created_at).toLocaleDateString('tr-TR')}</td>
                </tr>
            `).join('');
        }

        function displayStats(stats) {
            document.getElementById('totalUsers').textContent = stats.total_users || 0;
            document.getElementById('totalOrders').textContent = stats.total_orders || 0;
            document.getElementById('totalProducts').textContent = stats.total_products || 0;
            document.getElementById('totalRevenue').textContent = (stats.total_revenue || 0) + ' TL';
        }

        // Log response
        function logResponse(data) {
            const log = document.getElementById('responseLog');
            const timestamp = new Date().toLocaleTimeString('tr-TR');
            const logEntry = `[${timestamp}] ${JSON.stringify(data, null, 2)}\n\n`;
            log.textContent = logEntry + log.textContent;
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            logResponse({
                message: 'Admin Panel hazır!',
                instructions: [
                    '1. Admin bilgileriyle giriş yapın',
                    '2. Tabları kullanarak verileri görüntüleyin',
                    '3. API yanıtları burada loglanacak'
                ]
            });
        });
    </script>
</body>
</html>
