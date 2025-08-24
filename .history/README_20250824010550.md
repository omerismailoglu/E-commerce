# E-Ticaret API Projesi

Bu proje, Laravel ve PostgreSQL kullanılarak geliştirilmiş bir e-ticaret API'sidir. JWT token tabanlı authentication sistemi ile güvenli API endpoint'leri sunar.

## Teknik Gereksinimler

- PHP 8.0+
- PostgreSQL 13+
- Laravel 9.x
- Composer

## Kurulum Adımları

### 🐳 Docker ile Kurulum (Önerilen)

#### 1. Projeyi Klonlayın
```bash
git clone <repository-url>
cd Ecom
```

#### 2. Docker Kurulum Scriptini Çalıştırın
```bash
chmod +x docker-setup.sh
./docker-setup.sh
```

#### 3. Manuel Docker Kurulumu (Alternatif)
```bash
# Containers'ları build et ve başlat
docker-compose up -d --build

# Dependencies yükle
docker-compose exec app composer install

# Application key oluştur
docker-compose exec app php artisan key:generate

# JWT secret oluştur
docker-compose exec app php artisan jwt:secret

# Migration'ları çalıştır
docker-compose exec app php artisan migrate

# Sample data yükle
docker-compose exec app php artisan db:seed --class=SampleDataSeeder
```

### 💻 Manuel Kurulum

#### 1. Projeyi Klonlayın
```bash
git clone <repository-url>
cd Ecom
```

#### 2. Bağımlılıkları Yükleyin
```bash
composer install
```

#### 3. Environment Dosyasını Oluşturun
```bash
cp .env.example .env
```

#### 4. Environment Değişkenlerini Düzenleyin
`.env` dosyasında PostgreSQL bağlantı bilgilerini güncelleyin:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=ecom_api
DB_USERNAME=postgres
DB_PASSWORD=your_postgres_password
```

#### 5. Application Key Oluşturun
```bash
php artisan key:generate
```

#### 6. JWT Secret Oluşturun
```bash
php artisan jwt:secret
```

#### 7. Veritabanını Oluşturun
PostgreSQL'de veritabanını oluşturun:
```sql
CREATE DATABASE ecom_api;
```

#### 8. Migration'ları Çalıştırın
```bash
php artisan migrate
```

#### 9. Sample Data'yı Yükleyin
```bash
php artisan db:seed --class=SampleDataSeeder
```

#### 10. Sunucuyu Başlatın
```bash
php artisan serve
```

## API Endpoint'leri

### Authentication

#### POST /api/register
Kullanıcı kaydı
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123"
}
```

#### POST /api/login
Kullanıcı girişi
```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

#### GET /api/profile
Kullanıcı profili görüntüleme (Token gerekli)

#### PUT /api/profile
Kullanıcı profili güncelleme (Token gerekli)
```json
{
    "name": "John Updated",
    "email": "john.updated@example.com"
}
```

#### POST /api/logout
Çıkış yapma (Token gerekli)

### Kategoriler

#### GET /api/categories
Tüm kategorileri listele (Token gerekli)

#### POST /api/categories
Yeni kategori oluştur (Token gerekli)
```json
{
    "name": "Yeni Kategori",
    "description": "Kategori açıklaması"
}
```

#### GET /api/categories/{id}
Kategori detayı (Token gerekli)

#### PUT /api/categories/{id}
Kategori güncelle (Token gerekli)

#### DELETE /api/categories/{id}
Kategori sil (Token gerekli)

### Ürünler

#### GET /api/products
Ürünleri listele

**Query Parameters:**
- `search`: Ürün adı, açıklama veya kategori adında arama
- `category_id`: Tek kategori filtresi
- `category_ids`: Çoklu kategori filtresi (virgülle ayrılmış)
- `min_price`: Minimum fiyat
- `max_price`: Maksimum fiyat
- `in_stock`: Stok durumu (true/false)
- `min_stock`: Minimum stok miktarı
- `max_stock`: Maksimum stok miktarı
- `sort_by`: Sıralama alanı (name, price, stock_quantity, created_at, updated_at)
- `sort_order`: Sıralama yönü (asc, desc)
- `limit`: Sayfa başına kayıt sayısı (varsayılan: 20, maksimum: 100)
- `page`: Sayfa numarası

**Örnek Kullanımlar:**
```
# Elektronik kategorisinde laptop arama
GET /api/products?search=laptop&category_id=1&min_price=500&max_price=2000

# Stokta olan ürünleri fiyata göre sıralama
GET /api/products?in_stock=true&sort_by=price&sort_order=asc

# Düşük stoklu ürünleri listeleme
GET /api/products?max_stock=10&sort_by=stock_quantity&sort_order=asc

# Çoklu kategori arama
GET /api/products?category_ids=1,2&search=phone&sort_by=created_at&sort_order=desc
``` (Token gerekli)

**Query Parametreleri:**
- `page`: Sayfa numarası
- `limit`: Sayfa başına kayıt sayısı (varsayılan: 20)
- `category_id`: Kategori filtresi
- `min_price`: Minimum fiyat
- `max_price`: Maksimum fiyat
- `search`: Ürün adında arama

#### POST /api/products
Yeni ürün ekle (Token gerekli)
```json
{
    "name": "Yeni Ürün",
    "description": "Ürün açıklaması",
    "price": 99.99,
    "stock_quantity": 50,
    "category_id": 1
}
```

#### GET /api/products/{id}
Ürün detayı (Token gerekli)

#### PUT /api/products/{id}
Ürün güncelle (Token gerekli)

#### DELETE /api/products/{id}
Ürün sil (Token gerekli)

### Sepet

#### GET /api/cart
Sepeti görüntüle (Token gerekli)

#### POST /api/cart/add
Sepete ürün ekle (Token gerekli)
```json
{
    "product_id": 1,
    "quantity": 2
}
```

#### PUT /api/cart/update
Sepet ürün miktarı güncelle (Token gerekli)
```json
{
    "product_id": 1,
    "quantity": 3
}
```

#### DELETE /api/cart/remove/{product_id}
Sepetten ürün çıkar (Token gerekli)

#### DELETE /api/cart/clear
Sepeti temizle (Token gerekli)

### Siparişler

#### GET /api/orders
Kullanıcının siparişlerini listele (Token gerekli)

#### POST /api/orders
Sipariş oluştur (Token gerekli)

#### GET /api/orders/{id}
Sipariş detayı (Token gerekli)

#### PUT /api/orders/{id}/status
Sipariş durumu güncelle (Admin Token gerekli)
```json
{
    "status": "processing"
}
```

### Admin Dashboard

#### GET /api/admin/dashboard
Dashboard istatistikleri (Admin Token gerekli)

#### GET /api/admin/orders
Tüm siparişleri listele (Admin Token gerekli)
**Query Parameters:**
- `status`: Sipariş durumu filtresi
- `start_date`: Başlangıç tarihi
- `end_date`: Bitiş tarihi
- `search`: Kullanıcı email veya sipariş ID arama
- `sort_by`: Sıralama alanı (id, total_amount, status, created_at)
- `sort_order`: Sıralama yönü (asc, desc)
- `limit`: Sayfa başına kayıt sayısı

#### GET /api/admin/users
Tüm kullanıcıları listele (Admin Token gerekli)
**Query Parameters:**
- `role`: Kullanıcı rolü filtresi
- `search`: İsim veya email arama
- `sort_by`: Sıralama alanı
- `sort_order`: Sıralama yönü
- `limit`: Sayfa başına kayıt sayısı

#### GET /api/admin/inventory
Envanter raporu (Admin Token gerekli)
**Query Parameters:**
- `stock_level`: Stok seviyesi (low, out, normal)
- `category_id`: Kategori filtresi
- `search`: Ürün adı arama
- `sort_by`: Sıralama alanı
- `sort_order`: Sıralama yönü
- `limit`: Sayfa başına kayıt sayısı

#### PUT /api/admin/products/{id}/stock
Ürün stok güncelle (Admin Token gerekli)
```json
{
    "stock_quantity": 50
}
```

## Response Format

Tüm API response'ları aşağıdaki formatı kullanır:

```json
{
    "success": true,
    "message": "İşlem başarılı",
    "data": {},
    "errors": []
}
```

## HTTP Status Kodları

- `200`: Başarılı işlem
- `201`: Oluşturma başarılı
- `400`: Geçersiz istek
- `401`: Yetkisiz erişim
- `404`: Bulunamadı
- `422`: Validasyon hatası
- `500`: Sunucu hatası

## Test Kullanıcıları

Seeder ile oluşturulan test kullanıcıları:

### Admin Kullanıcısı
- **Email:** admin@test.com
- **Password:** admin123
- **Role:** admin

### Normal Kullanıcı
- **Email:** user@test.com
- **Password:** user123
- **Role:** user

## Authentication

API'yi kullanmak için JWT token gereklidir. Token'ı almak için:

1. `/api/register` veya `/api/login` endpoint'ini kullanın
2. Response'dan `data.token` değerini alın
3. Sonraki isteklerde `Authorization` header'ında `Bearer {token}` formatında gönderin

**Örnek:**
```
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
```

## Özellikler

- ✅ JWT Token Authentication
- ✅ Kullanıcı Yönetimi (Kayıt, Giriş, Profil)
- ✅ Kategori Yönetimi (CRUD)
- ✅ Ürün Yönetimi (CRUD)
- ✅ Sepet Yönetimi
- ✅ Sipariş Yönetimi
- ✅ Stok Kontrolü
- ✅ Filtreleme ve Sayfalama
- ✅ Validasyon
- ✅ Error Handling
- ✅ PostgreSQL Veritabanı
- ✅ Sample Data

## 🛡️ Güvenlik Özellikleri

- ✅ **Password Hashing**: Bcrypt ile şifre hashleme
- ✅ **SQL Injection Protection**: Laravel ORM ile koruma
- ✅ **XSS Protection**: Input sanitization middleware
- ✅ **Rate Limiting**: IP tabanlı rate limiting (60 req/min)
- ✅ **JWT Authentication**: Token tabanlı kimlik doğrulama
- ✅ **Input Validation**: Laravel Validator ile validasyon

## 🚀 Bonus Özellikler

### 📦 **Ürün Stok Takibi**
- ✅ **Otomatik Stok Kontrolü**: Sipariş verirken stok yeterliliği kontrolü
- ✅ **Detaylı Stok Hataları**: Hangi ürünlerde ne kadar eksik olduğu bilgisi
- ✅ **Stok Güncelleme**: Sipariş tamamlandığında otomatik stok azaltma
- ✅ **Stok Logging**: Tüm stok değişikliklerinin loglanması
- ✅ **Admin Stok Yönetimi**: Admin panelinden manuel stok güncelleme

### 📧 **Email Bildirimleri**
- ✅ **Sipariş Onayı**: Sipariş oluşturulduğunda otomatik email
- ✅ **Durum Güncellemesi**: Sipariş durumu değiştiğinde email bildirimi
- ✅ **Detaylı Email İçeriği**: Sipariş detayları, ürünler ve toplam tutar

### 🔍 **Gelişmiş Arama ve Filtreleme**
- ✅ **Çoklu Arama**: Ürün adı, açıklama ve kategori adında arama
- ✅ **Fiyat Aralığı**: Min/max fiyat filtreleme
- ✅ **Stok Durumu**: Stokta olan/olmayan ürün filtreleme
- ✅ **Kategori Filtreleme**: Tek veya çoklu kategori seçimi
- ✅ **Gelişmiş Sıralama**: Fiyat, stok, tarih bazında sıralama
- ✅ **Sayfalama**: Detaylı pagination bilgileri

### 👨‍💼 **Admin Dashboard**
- ✅ **Genel İstatistikler**: Toplam kullanıcı, ürün, sipariş sayıları
- ✅ **Gelir Analizi**: Toplam ve aylık gelir takibi
- ✅ **Sipariş Durumları**: Durum bazında sipariş sayıları
- ✅ **Stok Raporu**: Düşük stok ve tükenen ürün sayıları
- ✅ **Son Siparişler**: En son 10 sipariş listesi
- ✅ **En Çok Satan Ürünler**: Satış miktarına göre sıralama
- ✅ **Aylık Satış Grafiği**: Son 6 ayın satış verileri

### 📊 **Admin Yönetim Paneli**
- ✅ **Tüm Siparişler**: Filtreleme, arama ve sıralama ile
- ✅ **Kullanıcı Yönetimi**: Kullanıcı listesi ve detayları
- ✅ **Envanter Raporu**: Stok seviyesi bazında ürün listesi
- ✅ **Stok Değeri**: Toplam envanter değeri hesaplama

### 🔄 **Sipariş Durumu Yönetimi**
- ✅ **Durum Güncelleme**: Admin tarafından sipariş durumu değiştirme
- ✅ **Durum Seçenekleri**: pending, processing, shipped, delivered, cancelled
- ✅ **Email Bildirimi**: Durum değişikliğinde otomatik email
- ✅ **Logging**: Tüm durum değişikliklerinin kaydedilmesi

## 🚀 Bonus Özellikler

### 📦 **Ürün Stok Takibi**
- ✅ **Otomatik Stok Kontrolü**: Sipariş verirken stok yeterliliği kontrolü
- ✅ **Detaylı Stok Hataları**: Hangi ürünlerde ne kadar eksik olduğu bilgisi
- ✅ **Stok Güncelleme**: Sipariş tamamlandığında otomatik stok azaltma
- ✅ **Stok Logging**: Tüm stok değişikliklerinin loglanması
- ✅ **Admin Stok Yönetimi**: Admin panelinden manuel stok güncelleme

**API Endpoint:**
```http
PUT /api/admin/products/{id}/stock
Authorization: Bearer {ADMIN_TOKEN}
Content-Type: application/json

{
    "stock_quantity": 50
}
```

### 📧 **Email Bildirimleri**
- ✅ **Sipariş Onayı**: Sipariş oluşturulduğunda otomatik email
- ✅ **Durum Güncellemesi**: Sipariş durumu değiştiğinde email bildirimi
- ✅ **Detaylı Email İçeriği**: Sipariş detayları, ürünler ve toplam tutar

**Özellikler:**
- Otomatik sipariş onay emaili
- Sipariş durumu değişikliğinde bildirim
- Detaylı sipariş bilgileri içeren email içeriği

### 🔍 **Gelişmiş Arama ve Filtreleme**
- ✅ **Çoklu Arama**: Ürün adı, açıklama ve kategori adında arama
- ✅ **Fiyat Aralığı**: Min/max fiyat filtreleme
- ✅ **Stok Durumu**: Stokta olan/olmayan ürün filtreleme
- ✅ **Kategori Filtreleme**: Tek veya çoklu kategori seçimi
- ✅ **Gelişmiş Sıralama**: Fiyat, stok, tarih bazında sıralama
- ✅ **Sayfalama**: Detaylı pagination bilgileri

**API Endpoint:**
```http
GET /api/products?search=elektronik&min_price=100&max_price=1000&category_id=1&in_stock=true&sort_by=price&sort_order=desc&page=1&limit=20
```

### 👨‍💼 **Admin Dashboard**
- ✅ **Genel İstatistikler**: Toplam kullanıcı, ürün, sipariş sayıları
- ✅ **Gelir Analizi**: Toplam ve aylık gelir takibi
- ✅ **Sipariş Durumları**: Durum bazında sipariş sayıları
- ✅ **Stok Raporu**: Düşük stok ve tükenen ürün sayıları
- ✅ **Son Siparişler**: En son 10 sipariş listesi
- ✅ **En Çok Satan Ürünler**: Satış miktarına göre sıralama
- ✅ **Aylık Satış Grafiği**: Son 6 ayın satış verileri

**API Endpoint:**
```http
GET /api/admin/dashboard
Authorization: Bearer {ADMIN_TOKEN}
```

**Response Örneği:**
```json
{
    "success": true,
    "data": {
        "overview": {
            "total_users": 10,
            "total_products": 15,
            "total_orders": 25,
            "total_revenue": 15000.00,
            "monthly_revenue": 5000.00
        },
        "orders": {
            "pending": 5,
            "processing": 3,
            "shipped": 8,
            "delivered": 7,
            "cancelled": 2
        },
        "stock": {
            "low_stock": 3,
            "out_of_stock": 1
        },
        "recent_orders": [...],
        "top_products": [...],
        "monthly_sales": [...]
    }
}
```

### 📊 **Admin Yönetim Paneli**
- ✅ **Tüm Siparişler**: Filtreleme, arama ve sıralama ile
- ✅ **Kullanıcı Yönetimi**: Kullanıcı listesi ve detayları
- ✅ **Envanter Raporu**: Stok seviyesi bazında ürün listesi
- ✅ **Stok Değeri**: Toplam envanter değeri hesaplama

**API Endpoint'leri:**
```http
GET /api/admin/orders?status=pending&sort_by=created_at&sort_order=desc
GET /api/admin/users?search=admin&sort_by=name
GET /api/admin/inventory?stock_level=low&category_id=1
```

### 🔄 **Sipariş Durumu Yönetimi**
- ✅ **Durum Güncelleme**: Admin tarafından sipariş durumu değiştirme
- ✅ **Durum Seçenekleri**: pending, processing, shipped, delivered, cancelled
- ✅ **Email Bildirimi**: Durum değişikliğinde otomatik email
- ✅ **Logging**: Tüm durum değişikliklerinin kaydedilmesi

**API Endpoint:**
```http
PUT /api/orders/{id}/status
Authorization: Bearer {ADMIN_TOKEN}
Content-Type: application/json

{
    "status": "processing"
}
```

**Geçerli Durumlar:**
- `pending` - Beklemede
- `processing` - İşleniyor
- `shipped` - Kargoda
- `delivered` - Teslim Edildi
- `cancelled` - İptal Edildi

## 🐳 Docker Komutları

### Container Yönetimi
```bash
# Containers'ları başlat
docker-compose up -d

# Containers'ları durdur
docker-compose down

# Containers'ları yeniden başlat
docker-compose restart

# Log'ları görüntüle
docker-compose logs -f

# Belirli bir service'in log'larını görüntüle
docker-compose logs -f app
```

### Laravel Komutları (Docker içinde)
```bash
# Artisan komutları
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
docker-compose exec app php artisan cache:clear

# Composer komutları
docker-compose exec app composer install
docker-compose exec app composer update

# Yeni dosyalar oluşturma
docker-compose exec app php artisan make:controller Api/NewController
docker-compose exec app php artisan make:model NewModel -m
docker-compose exec app php artisan make:seeder NewSeeder
```

### Veritabanı İşlemleri
```bash
# PostgreSQL'e bağlan
docker-compose exec postgres psql -U postgres -d ecom_api

# Database backup
docker-compose exec postgres pg_dump -U postgres ecom_api > backup.sql

# Database restore
docker-compose exec -T postgres psql -U postgres -d ecom_api < backup.sql
```

## 💻 Geliştirme

### Yeni Migration Oluşturma
```bash
# Docker ile
docker-compose exec app php artisan make:migration create_table_name

# Manuel
php artisan make:migration create_table_name
```

### Yeni Model Oluşturma
```bash
# Docker ile
docker-compose exec app php artisan make:model ModelName -m

# Manuel
php artisan make:model ModelName -m
```

### Yeni Controller Oluşturma
```bash
# Docker ile
docker-compose exec app php artisan make:controller Api/ControllerName

# Manuel
php artisan make:controller Api/ControllerName
```

### Yeni Seeder Oluşturma
```bash
# Docker ile
docker-compose exec app php artisan make:seeder SeederName

# Manuel
php artisan make:seeder SeederName
```

## 📋 Proje Özeti

### ✅ **Temel Özellikler**
- 🔐 JWT Token Authentication
- 👥 Kullanıcı Yönetimi (Kayıt, Giriş, Profil)
- 📂 Kategori Yönetimi (CRUD)
- 🛍️ Ürün Yönetimi (CRUD, Arama, Filtreleme)
- 🛒 Sepet Yönetimi (Ekleme, Güncelleme, Silme)
- 📦 Sipariş Yönetimi (Oluşturma, Listeleme, Detay)

### 🚀 **Bonus Özellikler**
- 📦 **Stok Takibi**: Otomatik stok kontrolü ve güncelleme
- 📧 **Email Bildirimleri**: Sipariş onayı ve durum güncellemeleri
- 🔍 **Gelişmiş Arama**: Çoklu filtreleme ve arama
- 👨‍💼 **Admin Dashboard**: İstatistikler ve raporlar
- 🔄 **Sipariş Durumu**: Admin tarafından durum yönetimi

### 🛡️ **Güvenlik Özellikleri**
- 🔐 JWT Token Authentication
- 🔒 Role-based Access Control (Admin/User)
- 🛡️ SQL Injection Koruması (Eloquent ORM)
- 🚫 XSS Koruması (Middleware)
- ⏱️ Rate Limiting (60 request/minute)
- ✅ Input Validation ve Sanitization

### 🐳 **Docker Desteği**
- 📦 Containerized Laravel Application
- 🗄️ PostgreSQL Database
- 🌐 Nginx Web Server
- 🔴 Redis Cache
- 🔧 Otomatik Kurulum Scripti

### 📊 **Teknolojiler**
- **Backend**: Laravel 9.x
- **Database**: PostgreSQL 13+
- **Authentication**: JWT (php-open-source-saver/jwt-auth)
- **Containerization**: Docker & Docker Compose
- **Web Server**: Nginx
- **Cache**: Redis
- **Language**: PHP 8.0+

### 📈 **API İstatistikleri**
- **Toplam Endpoint**: 25+
- **Authentication**: JWT Bearer Token
- **Response Format**: JSON
- **HTTP Status Codes**: 200, 201, 400, 401, 403, 404, 422, 429, 500
- **Rate Limiting**: 60 requests/minute
- **Pagination**: Tüm liste endpoint'lerinde

### 🎯 **Test Kullanıcıları**
- **Admin**: `admin@test.com` / `admin123`
- **User**: `user@test.com` / `user123


