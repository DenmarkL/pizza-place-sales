# pizza-place-sales

🛠️ Tech Stack
Laravel 10+

MySQL (or compatible DB)

Eloquent ORM

PHPUnit (for testing)

Optional Vue.js frontend



---

## ⚙️ Installation

## ⚙️ Backend Setup (Laravel)
```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
# Set DB config in .env
php artisan migrate --seed
php artisan serve
```
## ⚙️ Frontend Setup (Laravel)

```bash
cd frontend
npm install
touch frontend/.env.example
npm run dev
```


