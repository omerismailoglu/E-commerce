<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticaret API</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-5xl font-bold text-gray-900 mb-4">
                🛒 E-Ticaret API
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Laravel ve PostgreSQL ile geliştirilmiş modern RESTful e-ticaret API'si
            </p>
        </div>

        <!-- Features Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
            <!-- Authentication -->
            <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
                <div class="text-3xl mb-4">🔐</div>
                <h3 class="text-xl font-semibold mb-2">Kimlik Doğrulama</h3>
                <p class="text-gray-600">Laravel Sanctum ile güvenli token tabanlı kimlik doğrulama sistemi</p>
            </div>

            <!-- User Management -->
            <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
                <div class="text-3xl mb-4">👥</div>
                <h3 class="text-xl font-semibold mb-2">Kullanıcı Yönetimi</h3>
                <p class="text-gray-600">Kayıt, giriş, profil yönetimi ve rol tabanlı yetkilendirme</p>
            </div>

            <!-- Product Management -->
            <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
                <div class="text-3xl mb-4">📦</div>
                <h3 class="text-xl font-semibold mb-2">Ürün Yönetimi</h3>
                <p class="text-gray-600">Kategori ve ürün CRUD işlemleri, filtreleme ve arama</p>
            </div>

            <!-- Cart System -->
            <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
                <div class="text-3xl mb-4">🛒</div>
                <h3 class="text-xl font-semibold mb-2">Sepet Sistemi</h3>
                <p class="text-gray-600">Sepete ekleme, güncelleme, silme ve temizleme işlemleri</p>
            </div>

            <!-- Order Management -->
            <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
                <div class="text-3xl mb-4">📋</div>
                <h3 class="text-xl font-semibold mb-2">Sipariş Yönetimi</h3>
                <p class="text-gray-600">Sipariş oluşturma, stok kontrolü ve email bildirimleri</p>
            </div>

            <!-- Admin Dashboard -->
            <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
                <div class="text-3xl mb-4">📊</div>
                <h3 class="text-xl font-semibold mb-2">Admin Paneli</h3>
                <p class="text-gray-600">İstatistikler, kullanıcı ve sipariş yönetimi</p>
            </div>
        </div>

        <!-- API Endpoints -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold mb-6 text-center">🚀 API Endpoints</h2>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold mb-3 text-green-600">Public Endpoints</h3>
                    <ul class="space-y-2 text-sm">
                        <li><code class="bg-gray-100 px-2 py-1 rounded">GET /api/categories</code> - Kategori listesi</li>
                        <li><code class="bg-gray-100 px-2 py-1 rounded">GET /api/products</code> - Ürün listesi</li>
                        <li><code class="bg-gray-100 px-2 py-1 rounded">GET /api/products/{id}</code> - Ürün detayı</li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-3 text-blue-600">Auth Required</h3>
                    <ul class="space-y-2 text-sm">
                        <li><code class="bg-gray-100 px-2 py-1 rounded">POST /api/register</code> - Kayıt</li>
                        <li><code class="bg-gray-100 px-2 py-1 rounded">POST /api/login</code> - Giriş</li>
                        <li><code class="bg-gray-100 px-2 py-1 rounded">GET /api/profile</code> - Profil</li>
                        <li><code class="bg-gray-100 px-2 py-1 rounded">GET /api/cart</code> - Sepet</li>
                        <li><code class="bg-gray-100 px-2 py-1 rounded">POST /api/orders</code> - Sipariş</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Interactive API Tester -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold mb-6 text-center">🧪 API Test Paneli</h2>
            
            <!-- Authentication Section -->
            <div class="mb-8 p-6 bg-gray-50 rounded-lg">
                <h3 class="text-lg font-semibold mb-4">🔐 Kimlik Doğrulama</h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Email:</label>
                        <input type="email" id="authEmail" value="admin@example.com" class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Şifre:</label>
                        <input type="password" id="authPassword" value="password123" class="w-full px-3 py-2 border rounded-lg">
                        <p class="text-xs text-gray-500 mt-1">En az 8 karakter olmalıdır</p>
                    </div>
                </div>
                <div class="mt-4 flex gap-2">
                    <button onclick="login()" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Giriş Yap</button>
                    <button onclick="register()" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">Kayıt Ol</button>
                    <button onclick="logout()" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Çıkış</button>
                </div>
                <div id="authStatus" class="mt-2 text-sm"></div>
            </div>

            <!-- API Endpoints Section -->
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Public Endpoints -->
                <div>
                    <h3 class="text-lg font-semibold mb-4 text-green-600">🌐 Public Endpoints</h3>
                    <div class="space-y-3">
                        <button onclick="testEndpoint('GET', '/api/categories')" class="w-full px-4 py-2 bg-green-100 text-green-800 rounded-lg hover:bg-green-200 text-left">
                            <strong>GET</strong> /api/categories
                        </button>
                        <button onclick="testEndpoint('GET', '/api/products')" class="w-full px-4 py-2 bg-green-100 text-green-800 rounded-lg hover:bg-green-200 text-left">
                            <strong>GET</strong> /api/products
                        </button>
                        <button onclick="testEndpoint('GET', '/api/products/1')" class="w-full px-4 py-2 bg-green-100 text-green-800 rounded-lg hover:bg-green-200 text-left">
                            <strong>GET</strong> /api/products/1
                        </button>
                    </div>
                </div>

                <!-- Auth Required Endpoints -->
                <div>
                    <h3 class="text-lg font-semibold mb-4 text-blue-600">🔒 Auth Required</h3>
                    <div class="space-y-3">
                        <button onclick="testEndpoint('GET', '/api/profile')" class="w-full px-4 py-2 bg-blue-100 text-blue-800 rounded-lg hover:bg-blue-200 text-left">
                            <strong>GET</strong> /api/profile
                        </button>
                        <button onclick="testEndpoint('GET', '/api/cart')" class="w-full px-4 py-2 bg-blue-100 text-blue-800 rounded-lg hover:bg-blue-200 text-left">
                            <strong>GET</strong> /api/cart
                        </button>
                        <button onclick="testEndpoint('GET', '/api/orders')" class="w-full px-4 py-2 bg-blue-100 text-blue-800 rounded-lg hover:bg-blue-200 text-left">
                            <strong>GET</strong> /api/orders
                        </button>
                    </div>
                </div>
            </div>

            <!-- Response Display -->
            <div class="mt-8">
                <h3 class="text-lg font-semibold mb-4">📤 Response</h3>
                <div class="bg-gray-900 text-green-400 p-4 rounded-lg">
                    <pre id="responseDisplay" class="text-sm overflow-x-auto">API yanıtları burada görünecek...</pre>
                </div>
            </div>
        </div>

        <!-- Quick Start -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-lg p-8 text-white">
            <h2 class="text-2xl font-bold mb-4">⚡ Hızlı Başlangıç</h2>
            <div class="grid md:grid-cols-4 gap-6">
                <div>
                    <h3 class="text-lg font-semibold mb-2">Test Kullanıcısı</h3>
                    <p class="text-blue-100 mb-2">Email: <code class="bg-blue-800 px-2 py-1 rounded">admin@example.com</code></p>
                    <p class="text-blue-100">Şifre: <code class="bg-blue-800 px-2 py-1 rounded">password123</code></p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-2">Dokümantasyon</h3>
                    <p class="text-blue-100 mb-2">OpenAPI Spec: <code class="bg-blue-800 px-2 py-1 rounded">/public/openapi.yaml</code></p>
                    <p class="text-blue-100">README: <code class="bg-blue-800 px-2 py-1 rounded">/README.md</code></p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-2">Dashboard</h3>
                    <p class="text-blue-100 mb-2">E-ticaret deneyimi için:</p>
                    <a href="/dashboard" class="inline-block bg-white text-blue-600 px-4 py-2 rounded-lg font-semibold hover:bg-blue-50 transition-colors">
                        🛒 Dashboard'a Git
                    </a>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-2">Admin Panel</h3>
                    <p class="text-blue-100 mb-2">Database yönetimi için:</p>
                    <a href="/admin" class="inline-block bg-white text-blue-600 px-4 py-2 rounded-lg font-semibold hover:bg-blue-50 transition-colors">
                        🛠️ Admin Panel'e Git
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-12 text-gray-600">
            <p class="mb-2">Built with ❤️ using Laravel 9 & PostgreSQL</p>
            <p class="text-sm">Laravel v{{ Illuminate\Foundation\Application::VERSION }} | PHP v{{ PHP_VERSION }}</p>
        </div>
    </div>

    <!-- JavaScript for API Testing -->
    <script>
        let authToken = null;
        let currentUser = null;

        // Authentication functions
        async function login() {
            const email = document.getElementById('authEmail').value;
            const password = document.getElementById('authPassword').value;
            
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
                
                if (data.success) {
                    authToken = data.data.token;
                    currentUser = data.data.user;
                    
                    // Store token and user data in localStorage
                    localStorage.setItem('authToken', authToken);
                    localStorage.setItem('user', JSON.stringify(currentUser));
                    
                    document.getElementById('authStatus').innerHTML = 
                        `<span class="text-green-600">✅ Giriş başarılı! Kullanıcı: ${currentUser.name}</span>`;
                    showResponse(data);
                    
                    // Redirect to dashboard after successful login
                    setTimeout(() => {
                        window.location.href = '/dashboard';
                    }, 1000);
                } else {
                    let errorMessage = data.message;
                    if (data.errors) {
                        errorMessage = Object.values(data.errors).flat().join(', ');
                    }
                    document.getElementById('authStatus').innerHTML = 
                        `<span class="text-red-600">❌ Giriş başarısız: ${errorMessage}</span>`;
                    showResponse(data);
                }
            } catch (error) {
                document.getElementById('authStatus').innerHTML = 
                    `<span class="text-red-600">❌ Hata: ${error.message}</span>`;
                showResponse({ error: error.message });
            }
        }

        async function register() {
            const email = document.getElementById('authEmail').value;
            const password = document.getElementById('authPassword').value;
            const name = email.split('@')[0]; // Use email prefix as name
            
            // Validation
            if (password.length < 8) {
                document.getElementById('authStatus').innerHTML = 
                    `<span class="text-red-600">❌ Şifre en az 8 karakter olmalıdır</span>`;
                return;
            }
            
            try {
                const response = await fetch('/api/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ name, email, password })
                });

                const data = await response.json();
                
                if (data.success) {
                    authToken = data.data.token;
                    currentUser = data.data.user;
                    
                    // Store token and user data in localStorage
                    localStorage.setItem('authToken', authToken);
                    localStorage.setItem('user', JSON.stringify(currentUser));
                    
                    document.getElementById('authStatus').innerHTML = 
                        `<span class="text-green-600">✅ Kayıt başarılı! Kullanıcı: ${currentUser.name}</span>`;
                    showResponse(data);
                    
                    // Redirect to dashboard after successful registration
                    setTimeout(() => {
                        window.location.href = '/dashboard';
                    }, 1000);
                } else {
                    let errorMessage = data.message;
                    if (data.errors) {
                        errorMessage = Object.values(data.errors).flat().join(', ');
                    }
                    document.getElementById('authStatus').innerHTML = 
                        `<span class="text-red-600">❌ Kayıt başarısız: ${errorMessage}</span>`;
                    showResponse(data);
                }
            } catch (error) {
                document.getElementById('authStatus').innerHTML = 
                    `<span class="text-red-600">❌ Hata: ${error.message}</span>`;
                showResponse({ error: error.message });
            }
        }

        async function logout() {
            if (!authToken) {
                document.getElementById('authStatus').innerHTML = 
                    `<span class="text-yellow-600">⚠️ Zaten giriş yapılmamış</span>`;
                return;
            }

            try {
                const response = await fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${authToken}`,
                        'Accept': 'application/json',
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    authToken = null;
                    currentUser = null;
                    document.getElementById('authStatus').innerHTML = 
                        `<span class="text-green-600">✅ Çıkış başarılı</span>`;
                    showResponse(data);
                } else {
                    document.getElementById('authStatus').innerHTML = 
                        `<span class="text-red-600">❌ Çıkış başarısız: ${data.message}</span>`;
                    showResponse(data);
                }
            } catch (error) {
                document.getElementById('authStatus').innerHTML = 
                    `<span class="text-red-600">❌ Hata: ${error.message}</span>`;
            }
        }

        // API endpoint testing function
        async function testEndpoint(method, endpoint) {
            const headers = {
                'Accept': 'application/json',
            };

            // Add authorization header if we have a token
            if (authToken) {
                headers['Authorization'] = `Bearer ${authToken}`;
            }

            try {
                const response = await fetch(endpoint, {
                    method: method,
                    headers: headers
                });

                const data = await response.json();
                showResponse(data, response.status, method, endpoint);
            } catch (error) {
                showResponse({
                    error: error.message,
                    endpoint: endpoint,
                    method: method
                }, 0, method, endpoint);
            }
        }

        // Display response in the response area
        function showResponse(data, status = null, method = null, endpoint = null) {
            const responseDisplay = document.getElementById('responseDisplay');
            let displayText = '';

            if (status) {
                displayText += `Status: ${status}\n`;
            }
            if (method && endpoint) {
                displayText += `${method} ${endpoint}\n`;
            }
            displayText += '─'.repeat(50) + '\n';
            displayText += JSON.stringify(data, null, 2);

            responseDisplay.textContent = displayText;
        }

        // Initialize with some helpful info
        document.addEventListener('DOMContentLoaded', function() {
            showResponse({
                message: 'API Test Paneli hazır!',
                instructions: [
                    '1. Önce giriş yapın veya kayıt olun',
                    '2. Sonra endpoint\'leri test edin',
                    '3. Yanıtlar burada görünecek'
                ]
            });
        });
    </script>
</body>
</html>
