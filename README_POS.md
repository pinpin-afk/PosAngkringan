# Sistem POS Angkringan

Sistem Point of Sale (POS) untuk bisnis angkringan yang dibangun dengan Laravel dan Vue.js.

## Fitur

- ✅ Manajemen Produk dan Kategori
- ✅ Keranjang Belanja Interaktif
- ✅ Sistem Pemesanan
- ✅ Multiple Payment Methods (Tunai, Kartu, QRIS)
- ✅ Kalkulasi Pajak Otomatis
- ✅ Manajemen Stok
- ✅ Interface Responsif dengan Tailwind CSS

## Instalasi

### 1. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 2. Setup Environment

```bash
# Copy file environment
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 3. Setup Database

```bash
# Jalankan migration
php artisan migrate

# Jalankan seeder untuk data sample
php artisan db:seed
```

### 4. Build Assets

```bash
# Build untuk development
npm run dev

# Build untuk production
npm run build
```

### 5. Jalankan Server

```bash
# Jalankan Laravel server
php artisan serve

# Jalankan Vite dev server (terminal terpisah)
npm run dev
```

## Penggunaan

1. Buka browser dan akses `http://localhost:8000`
2. Sistem POS akan menampilkan daftar produk yang tersedia
3. Klik produk untuk menambahkannya ke keranjang
4. Atur jumlah item di keranjang
5. Isi informasi pelanggan (opsional)
6. Pilih metode pembayaran
7. Klik "Proses Pesanan" untuk menyelesaikan transaksi

## API Endpoints

### Produk
- `GET /api/pos/products` - Daftar produk
- `POST /api/pos/products` - Tambah produk
- `GET /api/pos/products/{id}` - Detail produk
- `PUT /api/pos/products/{id}` - Update produk
- `DELETE /api/pos/products/{id}` - Hapus produk

### Kategori
- `GET /api/pos/categories` - Daftar kategori
- `POST /api/pos/categories` - Tambah kategori
- `GET /api/pos/categories/{id}` - Detail kategori
- `PUT /api/pos/categories/{id}` - Update kategori
- `DELETE /api/pos/categories/{id}` - Hapus kategori

### Pesanan
- `GET /api/pos/orders` - Daftar pesanan
- `POST /api/pos/orders` - Buat pesanan baru
- `GET /api/pos/orders/{id}` - Detail pesanan
- `PUT /api/pos/orders/{id}` - Update status pesanan

## Struktur Database

### Tabel Categories
- id, name, description, is_active, timestamps

### Tabel Products
- id, name, description, price, stock, category_id, image, is_active, timestamps

### Tabel Orders
- id, order_number, customer_name, customer_phone, subtotal, tax, discount, total, payment_method, status, notes, timestamps

### Tabel Order Items
- id, order_id, product_id, quantity, price, subtotal, timestamps

## Teknologi yang Digunakan

- **Backend**: Laravel 11
- **Frontend**: Vue.js 3
- **CSS Framework**: Tailwind CSS
- **Build Tool**: Vite
- **Database**: SQLite (default)

## Kontribusi

1. Fork repository
2. Buat feature branch
3. Commit perubahan
4. Push ke branch
5. Buat Pull Request

## Lisensi

MIT License
