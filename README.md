# Melbis Shop Laravel Demo

This repository contains demonstration scripts showcasing how to seamlessly integrate the high-performance **Melbis Shop 6.5.0** core with the modern **Laravel** framework. 

It provides a practical example of how to leverage Melbis Shop's powerful backend logic (cart calculations, options, database operations) while utilizing Laravel's routing, controllers, and Blade templating engine for the frontend presentation.

## 🚀 Demo

[https://laravel.melbis.com/](https://laravel.melbis.com/)

## ⚙️ Installation Guide

Follow these steps to set up the demonstration project on your local server.

### Prerequisites
* PHP 8.3 or higher
* [Composer](https://getcomposer.org/) installed globally

### Step 1: Download the Project
Download the archive of this demonstration project from GitHub:
[https://github.com/melbis/melbis-shop-laravel](https://github.com/melbis/melbis-shop-laravel) and extract it to your web server directory.

### Step 2: Install Melbis Core
In the root directory of your project, install the stable Melbis Shop core package via Composer:
```bash
composer require melbis/melbis-shop
```

### Step 3: Install Laravel Dependencies
Navigate to the `laravel` subfolder and install the framework dependencies:
```bash
cd laravel
composer install
```

### Step 4: Configure Autoloading (The Bridge)
To allow Laravel to access the Melbis core classes, you need to update the autoloader. Open the `laravel/composer.json` file and add the Melbis namespace to the `psr-4` section:
```json
"autoload": {
    "psr-4": {
        "App\\": "app/",
        "Database\\Factories\\": "database/factories/",
        "Database\\Seeders\\": "database/seeders/",
        "Melbis\\MelbisShop\\": "../core/class/"
    }
}
```

After saving the file, regenerate the Composer autoload files:
```bash
composer dump-autoload
```

### Step 5: Environment Setup
Copy the example environment file and generate your application key:
```bash
cp .env.example .env
php artisan key:generate
```
*Note: Make sure your database credentials are correctly set up. The Melbis Core will read its configuration directly from the root `config.json` file via the `MelbisLogic` service.*

---

## 🏗️ Architecture Overview

This project uses a hybrid architecture that combines the classic direct-control procedural paradigm of Melbis Shop with Laravel's strict MVC (Model-View-Controller) structure.

### 1. The Bridge (`App\Services\MelbisLogic`)
This is the heart of the integration. Instead of mixing procedural code inside Laravel controllers, we use the **Bridge Pattern**. The `MelbisLogic` service acts as a wrapper that:
* Initializes the global Melbis environment (`$gParser`, `$gDb`).
* Reads the legacy `config.json` dynamically.
* Converts procedural core errors into standard Laravel Exceptions.
* Provides a clean `call()` method to execute native Melbis functions (e.g., `$melbis->call('MELBIS_INC_LOGIC_order_calc', [$user_id ,$version])`).

### 2. Thin Controllers (`App\Http\Controllers`)
Laravel controllers (like `CartController`) act purely as traffic directors. They intercept HTTP requests, use Dependency Injection to call the `MelbisLogic` service, handle Laravel's native sessions, and return JSON responses or views. They contain **zero** business or mathematical logic.

### 3. Native Modules (Melbis Core)
All the heavy lifting—database queries, cart calculations, discounts, multithreading, and option processing—is handled safely within the native Melbis `/units/` and `/core/` directories. This ensures 100% mathematical consistency with the Windows desktop application.

### 4. Dumb Views (`resources/views`)
The frontend is built using Laravel Blade templates and Bootstrap 5. Views receive pre-calculated arrays from the controllers and simply render the HTML, keeping the presentation layer completely separated from the data processing layer.

---

## 🔗 Quick Links

* 🌍 **Official Website:** [melbis.com](https://melbis.com/)
* 💰 **Prices & Licenses:** [melbis.com/price](https://melbis.com/en/price/)
* 📀 **Installation Packages:** [melbis.com/download](https://melbis.com/en/download/)
* 💻 **GitHub Releases:** [melbis/melbis-shop/releases](https://github.com/melbis/melbis-shop/releases)
* 🐳 **Docker Hub:** [melbis/melbis-shop](https://hub.docker.com/r/melbis/melbis-shop)
* 📦 **Packagist (Composer):** [melbis/melbis-shop](https://packagist.org/packages/melbis/melbis-shop)

## 💬 Community & Examples

* 📢 **Telegram News:** [@melbis_shop](https://t.me/melbis_shop)
* 📺 **YouTube Tutorials:** [Melbis-Shop Channel](https://www.youtube.com/@Melbis-Shop)
* 🖼️ **Screenshots:** [View gallery](https://melbis.com/en/screenshots/)
* 📖 **Documentation:** [Installation & Setup](https://melbis.com/en/dev/install/prepare/)
* 🏆 **Flagship Example Store:** [Astroscope.com.ua](https://astroscope.com.ua/) *(A live example of a high-load store powered by Melbis Shop)*

---
*Built for speed, scaled for business.*

