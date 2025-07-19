# Aplikasi Pengelolaan Barang

Aplikasi pengelolaan barang berbasis Laravel untuk mengelola inventori dan stok barang.

## Requirements

Sebelum menginstall aplikasi ini, pastikan sistem Anda memiliki:

-   PHP >= 8.1
-   Composer
-   MySQL/MariaDB
-   Node.js & NPM (untuk asset compilation)
-   Web Server (Apache/Nginx) atau bisa menggunakan Laragon

## Instalasi

Ikuti langkah-langkah berikut untuk menginstall aplikasi setelah di-clone:

### 1. Clone Repository

```bash
git clone <repository-url>
cd aplikasi-pengelolaan-barang
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Configuration

```bash
# Copy file environment
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup

Buka file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=aplikasi_pengelolaan_barang
DB_USERNAME=root
DB_PASSWORD=
```

Buat database baru dengan nama `aplikasi_pengelolaan_barang` di MySQL/MariaDB.

### 5. Database Migration & Seeding

```bash
# Jalankan migration
php artisan migrate

# Jalankan seeder (opsional)
php artisan db:seed
```

### 6. Storage Link

```bash
# Buat symbolic link untuk storage
php artisan storage:link
```

### 7. Compile Assets

```bash
# Compile assets untuk development
npm run dev

# Atau untuk production
npm run build
```

### 8. Menjalankan Aplikasi

```bash
# Jalankan development server
php artisan serve
```

Aplikasi akan berjalan di `http://127.0.0.1:8000`

## Fitur Aplikasi

-   Manajemen kategori barang
-   Manajemen data barang masuk
-   Users Management

## Troubleshooting

### Permission Issues

Jika mengalami masalah permission, jalankan:

```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Clear Cache

Jika mengalami error setelah konfigurasi:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

## License

Aplikasi
