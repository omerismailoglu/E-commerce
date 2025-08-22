# E-Ticaret API

PHP ve PostgreSQL kullanarak geliştirilmiş RESTful e-ticaret API'si.

## Özellikler

- Kullanıcı yönetimi (kayıt, giriş, profil)
- Kategori ve ürün yönetimi (Admin)
- Sepet işlemleri
- Sipariş yönetimi ve stok takibi
- JWT tabanlı kimlik doğrulama (Sanctum)
- Admin middleware
- Email bildirimleri
- OpenAPI dokümantasyonu

## Kurulum

### Gereksinimler
- PHP 8.0+
- PostgreSQL 13+
- Composer

### Adımlar
1. `composer install`
2. `.env` dosyasını kopyalayın ve veritabanı ayarlarını yapın
3. `php artisan key:generate`
4. `php artisan migrate --seed`

### Docker ile PostgreSQL
```bash
docker compose up -d
```

## API Endpoints

### Public
- `GET /api/categories` - Kategori listesi
- `GET /api/products` - Ürün listesi (filtreleme + sayfalama)
- `GET /api/products/{id}` - Ürün detayı

### Auth Required
- `POST /api/register` - Kullanıcı kaydı
- `POST /api/login` - Kullanıcı girişi
- `POST /api/logout` - Çıkış
- `GET /api/profile` - Profil görüntüleme
- `PUT /api/profile` - Profil güncelleme

### Cart
- `GET /api/cart` - Sepeti görüntüle
- `POST /api/cart/add` - Sepete ürün ekle
- `PUT /api/cart/update` - Sepet güncelle
- `DELETE /api/cart/remove/{id}` - Ürün çıkar
- `DELETE /api/cart/clear` - Sepeti temizle

### Orders
- `POST /api/orders` - Sipariş oluştur
- `GET /api/orders` - Siparişleri listele
- `GET /api/orders/{id}` - Sipariş detayı

### Admin Only
- `POST /api/categories` - Kategori oluştur
- `PUT /api/categories/{id}` - Kategori güncelle
- `DELETE /api/categories/{id}` - Kategori sil
- `POST /api/products` - Ürün oluştur
- `PUT /api/products/{id}` - Ürün güncelle
- `DELETE /api/products/{id}` - Ürün sil
- `PUT /api/orders/{id}/status` - Sipariş durumu güncelle

### Admin Dashboard
- `GET /api/admin/dashboard` - Ana dashboard istatistikleri
- `GET /api/admin/users/stats` - Kullanıcı istatistikleri
- `GET /api/admin/orders/stats` - Sipariş istatistikleri

## Test Kullanıcısı
- Email: admin@example.com
- Şifre: password123
- Rol: admin

## Dokümantasyon
OpenAPI spec: `/public/openapi.yaml`
