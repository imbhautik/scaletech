<p align="center">
  <a href="https://laravel.com" target="_blank" rel="noopener">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <a href="#"><img src="https://img.shields.io/badge/build-passing-brightgreen" alt="Build Status"></a>
  <a href="#"><img src="https://img.shields.io/badge/tests-passing-brightgreen" alt="Tests"></a>
  <a href="#"><img src="https://img.shields.io/badge/license-MIT-blue" alt="License"></a>
</p>

# Laravel Webhook Handler Microservice

A Laravel microservice that listens on a single webhook endpoint, processes incoming JSON payloads from multiple sources (GitHub, Stripe, Custom), and stores relevant data in a MySQL database.

---

## 🚀 Features

- Single `/api/webhook` POST endpoint
- Handles multiple webhook sources via `X-Webhook-Source` header:
  - **GitHub:** Stores commit info (commit ID, message, author)
  - **Stripe:** Stores transaction details (amount, currency, status)
  - **Custom:** Stores raw JSON payload
- Validates JSON input and logs requests
- Returns appropriate HTTP status codes (200 success, 400 bad request)
- Basic error handling for payload and database issues
- Unit & feature tests for core webhook flows

---

## ⚙️ Setup & Installation

1. Clone repository:

    ```bash
    git clone https://github.com/your-username/webhook-handler.git
    cd webhook-handler
    ```

2. Install dependencies:

    ```bash
    composer install
    ```

3. Configure environment:

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. Update `.env` with your database credentials:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=webhook_db
    DB_USERNAME=root
    DB_PASSWORD=
    ```

5. Run migrations:

    ```bash
    php artisan migrate
    ```

6. Start development server:

    ```bash
    php artisan serve
    ```

---

## 📬 Webhook Endpoint

**POST** `/api/webhook`

**Required Headers:**

| Header              | Description                                 |
|---------------------|---------------------------------------------|
| `Content-Type`      | `application/json`                          |
| `X-Webhook-Source`  | `github` \| `stripe` \| `custom` (required) |

---

## 🧩 Example Payloads

### GitHub

```json
{
  "commits": [
    {
      "id": "abc123",
      "message": "Initial commit",
      "author": { "name": "Alice" }
    }
  ]
}
