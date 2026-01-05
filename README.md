# ETS2Mods - Advanced Euro Truck Simulator 2 Mod Platform

A modern, secure, and feature-rich mod sharing platform for Euro Truck Simulator 2 (ETS2), built with **Laravel 12**.

### **Demo:**
- **[ETS2Mods](https://ets2.turkishmods.com)**

<img width="1536" height="1024" alt="ETS2Mods TurkishMods Metehan BİLAL" src="https://github.com/user-attachments/assets/382b8e49-d5fe-4a6e-a5e1-6ea545dfa62f" />


## 🌟 Features

### 🚀 **New: System Installer**
- **Automated Setup**: A user-friendly, 7-step installation wizard that guides you from server requirement checks to the final dashboard.
- **Environment Manager**: GUI to easily configure your database and application settings `.env` without touching code.
- **One-Click Migration**: Automatically creates tables and seed initial data.

### 🚛 Core Experience
- **Mod Management**: Complete lifecycle management (Upload, Edit, Version Control).
- **Advanced Search**: Full-text search with categories, game versions, and sorting (Popular, Rated, Newest).
- **Rich Content**: **TinyMCE** integration for rich text descriptions and standardized image uploads.
- **User Profiles**: Public profiles with bios, social links, activity feeds, and badges (Verified, Modder, Elite).

### 💬 Community & Interaction
- **Discussion System**: Threaded comments and replies.
- **Rating System**: 5-star rating system impacting both Mod and User reputation.
- **Social Features**: Follow mods/users, receive notifications (Email/In-app).
- **Badges**: Visual indicators for Mod Owners and Verified Authors in comments.

### 🛡️ Security & Integrity
- **Robust Moderation**:
  - Multi-level report system (Low to Critical).
  - **Auto-Flagging**: Mods with high report volumes are automatically flagged.
  - **Admin Queue**: Streamlined queue for approving/rejecting mods and comments.
- **Abuse Prevention**:
  - **Rate Limiting**: Download limits per IP to prevent scraping.
  - **Anti-Spam**: Duplicate download prevention logic.

### ⚡ Technical Highlights
- **Laravel 12 & PHP 8.3**: Cutting-edge backend foundation.
- **Livewire 3**: Dynamic, reactive frontend components (Search, Comments, Admin Queue).
- **Tailwind CSS 3**: Modern, responsive utility-first design.
- **Alpine.js**: Lightweight JavaScript interactivity.
- **Spatie Integration**: Permissions, Media Library, and Activity Logging.
- **Security**: CSRF Protection, Policy-based Authorization, Rate Limiting

## 🛠️ Technology Stack

- **Backend**: PHP 8.3+, Laravel 12.x, MySQL 8.0+
- **Frontend**: Blade, Livewire 3, Tailwind CSS, Alpine.js, Vite
- **Services**: TinyMCE (Rich Text), Google reCAPTCHA v2 (Spam Protection)

## 📦 Installation

### Prerequisites
- PHP >= 8.3+
- Composer
- Node.js & NPM
- MySQL 8.0+

### Quick Start (Using Installer)

1.  **Clone the Repository**
    ```bash
    git clone https://github.com/MBCustoms/ETS2Mods.git
    cd ets2mods
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    npm install
    npm run build
    ```

3.  **Launch the Application**
    ```bash
    php artisan serve
    ```

4.  **Run the Installer**
    - Visit `http://127.0.0.1:8000` in your browser.
    - You will be automatically redirected to the **Installation Wizard**.
    - Follow the on-screen instructions to check requirements, set up your database, and create your admin account.

### Manual Installation (Advanced)

If you prefer to bypass the installer:

1.  **Environment Setup**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    *Update `.env` with your database credentials.*

2.  **Database Migration & Seeding**
    ```bash
    php artisan migrate --seed
    ```
    *This creates the database structure and adds a default admin user.*
    *A default admin user is created during seeding.*
	*Credentials are shown on the installer screen or can be changed immediately after login.*

3.  **Mark as Installed**
    Create a file named `installed` inside the `storage` folder to prevent the installer from running.
    ```bash
    echo "INSTALLED" > storage/installed
    ```

## ⚙️ Configuration

### Key .env Variables
```env
APP_NAME="ETS2Mods"
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=ets2mods

# Mail (Required for notifications)
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io

# Admin & Permissions
# A default admin user is created during seeding.
# Credentials are shown on the installer screen or can be changed immediately after login.
```

## 👥 Contributing

Contributions are welcome! Please follow these steps:
1.  Fork the project.
2.  Create your feature branch (`git checkout -b feature/AmazingFeature`).
3.  Commit your changes (`git commit -m 'Add some AmazingFeature'`).
4.  Push to the branch (`git push origin feature/AmazingFeature`).
5.  Open a Pull Request.

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---
*Built with ❤️ by the TurkishMods Developer Team.*
