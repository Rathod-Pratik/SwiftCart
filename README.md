# 🛒 SwiftCart - E-Commerce API Platform

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP](https://img.shields.io/badge/PHP-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?logo=laravel&logoColor=white)](https://laravel.com/)
[![MySQL](https://img.shields.io/badge/MySQL-4479A1?logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Redis](https://img.shields.io/badge/Redis-DC382D?logo=redis&logoColor=white)](https://redis.io/)
[![AWS S3](https://img.shields.io/badge/AWS_S3-569A31?logo=amazons3&logoColor=white)](https://aws.amazon.com/s3/)
[![Sanctum](https://img.shields.io/badge/Auth-Sanctum-EF3B2D?logo=laravel&logoColor=white)](https://laravel.com/docs/sanctum)

A modern, production-ready **Laravel API** for a multi-vendor e-commerce platform. Built with **Laravel**, **Sanctum**, **Redis** (tagged caching), and **AWS S3** for media storage — supporting role-based product management, cart & order workflows, category browsing, and customer reviews, all served as a clean JSON API.

---

## 📌 Features

### 👤 User & Authentication
- **Token-Based Authentication:** Laravel Sanctum for secure, stateless API authentication.
- **Role-Based Access Control (RBAC):** Distinct permissions for Admins, Vendors, and Customers via Gates/Policies.
- **Profile Management:** Update profile details and upload profile images to AWS S3.

### 🛍️ Products & Categories
- **Product Catalog:** Rich product listings with pricing, discounts, stock, features, and multiple images.
- **Advanced Filtering & Search:** Filter by category, vendor, price range, status, visibility, and featured/trending/limited flags.
- **Vendor Scoping:** Vendors manage and view only their own products; admins have full visibility.
- **Categories:** Simple, cached category management for storefront navigation.
- **Information Sections:** Structured product detail sections (e.g. specs, care instructions) stored as related records.

### 🛒 Cart, Orders & Reviews
- **Shopping Cart:** Add, update, and remove cart items with duplicate-item protection.
- **Order Management:** Paginated, per-user order history with authorization checks.
- **Reviews & Ratings:** Authenticated users can leave ratings, comments, and image attachments per product.

### ⚡ Performance & Caching (Redis)
- **Tagged Redis Caching:** Paginated listings (carts, orders, products, categories, reviews) are cached per-user/per-filter using Redis cache tags.
- **Automatic Cache Invalidation:** Eloquent Model Observers (`CartObserver`, `OrderObserver`, `ProductObserver`, `CategoryObserver`, `ReviewObserver`) flush relevant cache tags on create/update/delete — no manual cache management needed in controllers.
- **Scoped Invalidation:** Cache tags are scoped per-user or per-product where relevant, so unrelated cached data isn't wiped unnecessarily.

### 🛡️ Security & Media
- **AWS S3 Media Storage:** Profile images, product images, and review images are uploaded directly to S3 via a dedicated `S3Service`.
- **Request Validation:** Centralized validation rules and custom messages per controller using Laravel's Form Request validation.
- **Authorization Policies:** Every write action (create/update/delete) is gated through Laravel Policies (`Gate::authorize`).
- **Consistent JSON Responses:** Uniform `success` / `message` / `data` response structure with proper HTTP status codes across all endpoints.

---

## 🛠️ Tech Stack

### Backend
- **Language:** PHP
- **Framework:** Laravel
- **Database:** MySQL (or PostgreSQL) with Eloquent ORM
- **Cache:** Redis (via `predis`/`phpredis`) with tagged cache support
- **Cloud Storage:** AWS S3 (via custom `S3Service`)
- **Authentication:** Laravel Sanctum
- **Validation & Security:** Laravel Form Validation, Gates & Policies
- **Containerization:** Docker & Docker Compose

---

## 📂 Project Structure

```
SwiftCart/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── CartController.php        # Cart CRUD, Redis-cached listings
│   │       ├── CategoryController.php    # Category CRUD, Redis-cached listings
│   │       ├── OrderController.php       # Order listing & history
│   │       ├── ProductController.php     # Product catalog, filters, search
│   │       └── ReviewController.php      # Product reviews & ratings
│   ├── Models/
│   │   ├── Cart.php
│   │   ├── Category.php
│   │   ├── Order.php
│   │   ├── Product.php
│   │   └── Review.php
│   ├── Observers/
│   │   ├── CartObserver.php              # Cache invalidation on cart changes
│   │   ├── CategoryObserver.php          # Cache invalidation on category changes
│   │   ├── OrderObserver.php             # Cache invalidation on order changes
│   │   ├── ProductObserver.php           # Cache invalidation on product changes
│   │   └── ReviewObserver.php            # Cache invalidation on review changes
│   ├── Policies/                         # Authorization rules per model
│   ├── Providers/
│   │   └── AppServiceProvider.php        # Observer registration
│   └── Services/
│       └── S3Service.php                 # AWS S3 upload/key generation helper
├── config/
│   ├── database.php                      # Redis & DB connections
│   └── filesystems.php                   # S3 disk configuration
├── database/
│   ├── migrations/
│   └── seeders/
├── routes/
│   └── api.php                           # API route definitions
├── docker-compose.yml                    # App, DB, and Redis services
├── .env.example
└── README.md
```

---

## 🚀 Getting Started

### Prerequisites

Ensure you have the following installed:
- **PHP:** `v8.2+`
- **Composer**
- **MySQL** (or PostgreSQL) — local instance or managed DB
- **Redis:** Local instance or Docker container (required for caching)
- **AWS S3 Bucket:** (Optional, for image uploads)
- **Docker & Docker Compose:** (Optional, for containerized setup)

---

### Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/Rathod-Pratik/SwiftCart.git
   cd SwiftCart
   ```

2. **Install dependencies:**
   ```bash
   composer install
   ```

3. **Configure environment variables:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   Update the relevant values inside `.env`:

   ```env
   APP_NAME=SwiftCart
   APP_URL=http://localhost:8000

   # Database
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=swiftcart
   DB_USERNAME=root
   DB_PASSWORD=

   # Cache & Redis
   CACHE_DRIVER=redis
   REDIS_CLIENT=phpredis
   REDIS_HOST=127.0.0.1
   REDIS_PASSWORD=null
   REDIS_PORT=6379

   # AWS S3 (Media Storage)
   AWS_ACCESS_KEY_ID=your_aws_access_key
   AWS_SECRET_ACCESS_KEY=your_aws_secret_key
   AWS_DEFAULT_REGION=us-east-1
   AWS_BUCKET=your_s3_bucket_name

   # Sanctum
   SANCTUM_STATEFUL_DOMAINS=localhost
   SESSION_DOMAIN=localhost
   ```

4. **Run database migrations & seeders:**
   ```bash
   php artisan migrate --seed
   ```

5. **Serve the application:**
   ```bash
   php artisan serve
   ```

---

### 🐳 Running with Docker

If you'd rather run everything (app, MySQL, Redis) in containers:

```bash
docker-compose up -d --build
docker-compose exec app php artisan migrate --seed
```

When Redis runs inside Docker, remember to point `REDIS_HOST` to the **service name** (e.g. `redis`) rather than `127.0.0.1` — see the `docker-compose.yml` for the exact service names used.

---

## 🛠️ Available Commands

| Command | Description |
| :--- | :--- |
| `php artisan serve` | Starts the local development server |
| `php artisan migrate` | Runs database migrations |
| `php artisan migrate:fresh --seed` | Resets the database and reseeds sample data |
| `php artisan make:observer {Name}Observer --model={Model}` | Generates a new cache-invalidation observer |
| `php artisan test` | Runs the test suite |
| `php artisan tinker` | Opens an interactive REPL (useful for testing Redis connectivity) |

---

## 📡 API Overview

| Route Prefix | Module | Description |
| :--- | :--- | :--- |
| `/api/auth` | **Authentication** | Registration, Login, Logout via Sanctum |
| `/api/cart` | **Cart** | Add, view, update, and remove cart items (Redis-cached, per-user) |
| `/api/orders` | **Orders** | View paginated order history (Redis-cached, per-user) |
| `/api/products` | **Products** | Search, filter, create, update, and manage products (Redis-cached) |
| `/api/categories` | **Categories** | Browse and manage product categories (Redis-cached) |
| `/api/reviews` | **Reviews** | Submit, update, delete, and browse product reviews (Redis-cached per product) |

---

## ⚡ Caching Strategy

SwiftCart uses **Redis tagged caching** to keep paginated listing endpoints fast without serving stale data:

- Each listing endpoint (`cart`, `orders`, `products`, `categories`, `reviews`) caches its paginated response under a key built from the request's filters (page, per_page, search terms, etc.).
- Cache entries are grouped under **tags** (e.g. `carts:user:{id}`, `reviews:product:{id}`, `categories`), so invalidation can target exactly the affected data instead of flushing the entire cache.
- **Eloquent Observers** automatically flush the relevant tags whenever a model is created, updated, or deleted — controllers never need to manage cache invalidation manually.

> ⚠️ **Note:** Cache tags require the Redis (or Memcached) driver — they are not supported on the `file` or `database` cache drivers.

---

## 📄 License

This project is licensed under the **MIT License** - see the [LICENSE](LICENSE) file for details.

```text
MIT License

Copyright (c) 2026 Your Name

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.
```

---

## 👨‍💻 Author

**Rathod Pratik**