## KARHUTLA-PELAPORAN DAN PEMANTAUAN KEBAKARAN HUTAN DAN LAHAN

## Identitas
- Nama : M.Fahmi Hadi Wijaya
- NIM  : 251511016 
- Kelas: 2A-D3

## Kebutuhan Environtment
PHP, composer, MySQL, Git

## Langkah Instalasi
git clone https://github.com/fhxwjxy/MFahmi-Hadi-Wijaya-251511016-MiniProject-Proyek-3.git
cd MFahmi-Hadi-Wijaya-251511016-MiniProject-Proyek-3
composer install
npm install
npm run build
cp .env.example .env
php artisan key:generate

## Konfigurasi Database
1. membuat database kosong dengan nama karhutla
2. Di file .env sesuaikan seperti berikut:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=karhutla
DB_USERNAME=root
DB_PASSWORD=

## Cara migration dan Seeding
php artisan migrate:fresh --seed

## Akun demo (password semua akun: password)
- Pelapor: pelapor@karhutla.test dan pelapor2@karhutla.test
- Relawan Pemantau: relawan@karhutla.test
- Relawan nonaktif (uji BR-05): relawan.nonaktif@karhutla.test
- Koordinator Relawan: koordinator@karhutla.test

## Menjalankan aplikasi
php artisan serve
Buka http://127.0.0.1:8000

## Catatan
File bukti disimpan di storage/app/private/bukti dan hanya dapat dibuka
lewat aplikasi oleh pengguna yang berwenang.