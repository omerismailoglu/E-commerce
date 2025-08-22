<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol - E-Ticaret</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .register-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold text-gray-900">🛒 E-Ticaret</a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/" class="text-gray-700 hover:text-blue-600 font-medium">Ana Sayfa</a>
                    <a href="/login" class="text-gray-700 hover:text-blue-600 font-medium">Giriş Yap</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Register Section -->
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-green-100">
                    <i class="fas fa-user-plus text-2xl text-green-600"></i>
                </div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Yeni Hesap Oluşturun
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Veya
                    <a href="/login" class="font-medium text-blue-600 hover:text-blue-500">
                        mevcut hesabınıza giriş yapın
                    </a>
                </p>
            </div>
            
            <form id="registerForm" class="mt-8 space-y-6">
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            Ad Soyad
                        </label>
                        <input id="name" name="name" type="text" required 
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                               placeholder="Adınız ve soyadınız">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            E-posta Adresi
                        </label>
                        <input id="email" name="email" type="email" required 
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                               placeholder="ornek@email.com">
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Şifre
                        </label>
                        <input id="password" name="password" type="password" required 
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                               placeholder="En az 8 karakter">
                        <p class="mt-1 text-xs text-gray-500">En az 8 karakter olmalıdır</p>
                    </div>
                    <div>
                        <label for="password_confirm" class="block text-sm font-medium text-gray-700">
                            Şifre Tekrar
                        </label>
                        <input id="password_confirm" name="password_confirm" type="password" required 
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                               placeholder="Şifrenizi tekrar girin">
                    </div>
                </div>

                <div class="flex items-center">
                    <input id="terms" name="terms" type="checkbox" required
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="terms" class="ml-2 block text-sm text-gray-900">
                        <a href="/terms" class="text-blue-600 hover:text-blue-500">Kullanım şartlarını</a> ve 
                        <a href="/privacy" class="text-blue-600 hover:text-blue-500">gizlilik politikasını</a> kabul ediyorum
                    </label>
                </div>

                <div>
                    <button type="submit" 
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-user-plus text-green-500 group-hover:text-green-400"></i>
                        </span>
                        Hesap Oluştur
                    </button>
                </div>

                <div id="errorMessage" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded"></div>
                <div id="successMessage" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded"></div>
            </form>

            <!-- Social Register -->
            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-gray-50 text-gray-500">Veya şununla kayıt olun</span>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-3">
                    <button type="button" 
                            class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <i class="fab fa-google text-red-600"></i>
                        <span class="ml-2">Google</span>
                    </button>
                    <button type="button" 
                            class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <i class="fab fa-facebook text-blue-600"></i>
                        <span class="ml-2">Facebook</span>
                    </button>
                </div>
            </div>

            <!-- Benefits -->
            <div class="mt-8 p-4 bg-blue-50 rounded-lg">
                <h3 class="text-sm font-medium text-blue-900 mb-3">Hesap oluşturmanın avantajları:</h3>
                <ul class="space-y-2 text-sm text-blue-800">
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-600 mr-2"></i>
                        Hızlı alışveriş deneyimi
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-600 mr-2"></i>
                        Sipariş geçmişi takibi
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-600 mr-2"></i>
                        Özel indirimler ve fırsatlar
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check text-green-600 mr-2"></i>
                        Güvenli ödeme seçenekleri
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirm').value;
            
            // Validation
            if (password.length < 8) {
                showError('Şifre en az 8 karakter olmalıdır');
                return;
            }
            
            if (password !== passwordConfirm) {
                showError('Şifreler eşleşmiyor');
                return;
            }
            
            // Hide previous messages
            hideMessages();
            
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
                    // Store token and user data
                    localStorage.setItem('authToken', data.data.token);
                    localStorage.setItem('user', JSON.stringify(data.data.user));
                    
                    // Show success message
                    showSuccess('Hesap başarıyla oluşturuldu! Yönlendiriliyorsunuz...');
                    
                    // Redirect to dashboard
                    setTimeout(() => {
                        window.location.href = '/dashboard';
                    }, 1500);
                } else {
                    // Show error message
                    let errorText = data.message;
                    if (data.errors) {
                        errorText = Object.values(data.errors).flat().join(', ');
                    }
                    showError(errorText);
                }
            } catch (error) {
                showError('Bağlantı hatası oluştu. Lütfen tekrar deneyin.');
            }
        });

        function showError(message) {
            hideMessages();
            const errorMsg = document.getElementById('errorMessage');
            errorMsg.textContent = message;
            errorMsg.classList.remove('hidden');
        }

        function showSuccess(message) {
            hideMessages();
            const successMsg = document.getElementById('successMessage');
            successMsg.textContent = message;
            successMsg.classList.remove('hidden');
        }

        function hideMessages() {
            document.getElementById('errorMessage').classList.add('hidden');
            document.getElementById('successMessage').classList.add('hidden');
        }

        // Password confirmation validation
        document.getElementById('password_confirm').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const passwordConfirm = this.value;
            
            if (passwordConfirm && password !== passwordConfirm) {
                this.classList.add('border-red-500');
                this.classList.remove('border-gray-300');
            } else {
                this.classList.remove('border-red-500');
                this.classList.add('border-gray-300');
            }
        });
    </script>
</body>
</html>

