# 🌍 GERIP - Global Export Risk Intelligence Platform

GERIP (Global Export Risk Intelligence Platform) merupakan aplikasi berbasis web yang dikembangkan untuk memantau risiko rantai pasok global (Global Supply Chain) secara real-time. Sistem ini mengintegrasikan data cuaca, ekonomi, mata uang, pelabuhan, berita internasional, serta analisis risiko negara untuk mendukung pengambilan keputusan dalam aktivitas ekspor dan impor.

## 🎯 Project Objective

Aplikasi ini bertujuan untuk membantu proses monitoring kondisi rantai pasok global secara real-time sehingga pengguna memperoleh informasi strategis sebelum mengambil keputusan ekspor maupun impor.

---

## ✨ Features

### 🔐 Authentication
- Sistem Login & Register yang aman.

### 👨‍💼 Admin Panel
Admin memiliki akses untuk mengelola platform:
- 📊 **Dashboard**
- 👥 **User Management**
- 🚢 **Port Dataset Management**
- 📝 **Analysis Articles Management**

### 👤 User Features
Pengguna dapat memanfaatkan berbagai fitur intelijen:
- 📊 **Executive Dashboard**
- 🌍 **Country Monitor**
- ☁ **Weather Monitoring**
- 💱 **Currency Intelligence**
- ⚠ **Risk Analytics**
- 📰 **Global News**
- 🚢 **Port Monitoring**
- 📈 **Analytics**
- 🌎 **Country Comparison**
- ⭐ **Watchlist**

---

## 🛠️ Tech Stack

GERIP dibangun menggunakan teknologi modern untuk performa yang optimal:

| Category | Technology |
| :--- | :--- |
| **Backend Framework** | Laravel 13, PHP 8.3 |
| **Database** | MySQL |
| **Frontend** | Bootstrap 5, Blade Template, HTML5, CSS3, JavaScript |
| **Mapping** | Leaflet.js, OpenStreetMap |
| **Integration** | REST API |

---

## 📂 Project Structure

```text
gerip/
├── app/                  # Application core logic (Controllers, Models, Services)
├── bootstrap/            # Framework bootstrapping
├── config/               # Configuration files
├── database/             # Migrations and seeders
├── public/               # Public assets (CSS, JS, images)
├── resources/            # Blade templates and uncompiled assets
├── routes/               # Web and API routing
├── storage/              # Application storage (logs, cache, uploads)
├── .env.example          # Environment variables template
└── README.md             # Project documentation
```

---

## 🚀 Installation

Ikuti langkah-langkah berikut untuk menjalankan proyek di lingkungan lokal:

1. **Clone repository**
   ```bash
   git clone <repository_url>
   cd gerip
   ```

2. **Install dependensi PHP**
   ```bash
   composer install
   ```

3. **Install dependensi Node.js**
   ```bash
   npm install
   ```

4. **Konfigurasi Environment**
   ```bash
   cp .env.example .env
   ```
   *(Jangan lupa untuk mengatur koneksi database pada file `.env`)*

5. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

6. **Jalankan Migrasi dan Seeder**
   ```bash
   php artisan migrate --seed
   ```

7. **Kompilasi Asset Frontend**
   ```bash
   npm run dev
   ```

8. **Jalankan Development Server**
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses melalui `http://localhost:8000`.

---

## 📄 License

This project was developed for academic purposes.

---

## 👩‍💻 Developer

**Insyira Fayani**  
Kelas **A3**
