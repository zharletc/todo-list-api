<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# 📝 Todo List API (Laravel + Docker)

Proyek ini adalah API Todo List menggunakan Laravel dan MySQL, dijalankan dengan Docker.

## 🐳 1. Menjalankan Project dengan Docker

Pastikan kamu sudah menginstal:

* [Docker](https://www.docker.com/)
* [Docker Compose](https://docs.docker.com/compose/install/)

### ✨ Jalankan Docker

```bash
docker compose up -d --build
```

### 📦 Struktur Docker

* `app`: container untuk Laravel
* `db`: container untuk MySQL
* `phpmyadmin`: akses database via browser (opsional)

Pastikan port `8000` (Laravel) dan `3306` (MySQL) tidak bentrok.

## 🛠️ 2. Setup Laravel

Masuk ke container Laravel:

```bash
docker exec -it todo-list-app bash
```

Lalu jalankan perintah berikut di dalam container:

```bash
cp .env.example .env
php artisan key:generate
```

### 🔃 Jalankan Migrasi dan Seeder (opsional)

```bash
php artisan migrate
```

## 📬 3. Import Postman Collection

1. Buka [Postman](https://www.postman.com/downloads/)
2. Pilih `Import` → `File` → pilih file `postman_collection.json` dari folder `/postman/` atau folder lain yang tersedia
3. Set environment jika dibutuhkan (`http://localhost:8200` sebagai base URL)

## 🧪 4. Menjalankan Testing

Jalankan seluruh pengujian fitur:

```bash
php artisan test
```

Atau hanya untuk file tertentu:

```bash
php artisan test --filter=TaskFilterTest
```

> Gunakan trait `DatabaseTransactions` agar data uji otomatis dibersihkan setelah test.

## 👥 5. Akses

* API: [http://localhost:8200](http://localhost:8200)
* PhpMyAdmin (jika disediakan): [http://localhost:8200](http://localhost:8200)

---

## 📟 Catatan

* File Postman Collection tersedia di file `Todo List API.postman_collection.json`
* Pastikan volume Docker tidak menyimpan sisa data lama (`docker volume prune` jika perlu)
* Jika pakai Laravel Excel untuk export, pastikan package sudah terinstal semua
