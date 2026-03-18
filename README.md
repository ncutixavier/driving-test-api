# Driving Test API

A scalable SaaS Driving Test Platform built with **Laravel 13**, **Vue 3**, **Inertia.js**, and **TailwindCSS**.

## Tech Stack

| Layer     | Technology                          |
|-----------|-------------------------------------|
| Backend   | Laravel 13 (PHP 8.3+), MySQL        |
| Frontend  | Vue 3, Inertia.js, TailwindCSS      |
| Auth      | Laravel Sanctum                     |
| Build     | Vite                                |
| Testing   | PHPUnit (SQLite in-memory)          |

---

## Prerequisites

Make sure the following are installed on your machine before you begin:

| Tool       | Minimum version | Check command          |
|------------|-----------------|------------------------|
| PHP        | 8.3             | `php --version`        |
| Composer   | 2.x             | `composer --version`   |
| Node.js    | 18.x            | `node --version`       |
| npm        | 9.x             | `npm --version`        |
| MySQL      | 8.0             | `mysql --version`      |

---

## Local Setup

### 1. Clone the repository

```bash
git clone https://github.com/ncutixavier/driving-test-api.git
cd driving-test-api
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Install Node dependencies

```bash
npm install
```

### 4. Configure environment variables

Copy the example environment file and open it in your editor:

```bash
cp .env.example .env
```

Then generate the application key:

```bash
php artisan key:generate
```

### 5. Configure the database

Open `.env` and update the database credentials to match your local MySQL setup:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=driving_test
DB_USERNAME=root
DB_PASSWORD=your_password
```

Create the database in MySQL if it does not exist yet:

```bash
mysql -u root -p -e "CREATE DATABASE driving_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 6. Run database migrations

```bash
php artisan migrate
```

### 7. (Optional) Seed the database with sample data

```bash
php artisan db:seed
```

This creates 5 categories, 10 questions per category, and 4 answers per question.

---

## Running the Application

### Option A — Run everything with one command

The `composer dev` shortcut starts the Laravel server, queue worker, log watcher, and Vite dev server all at once:

```bash
composer dev
```

The application will be available at **http://localhost:8000**.

### Option B — Run backend and frontend separately

**Backend** (Laravel development server):

```bash
php artisan serve
```

The API and web routes will be available at **http://localhost:8000**.

**Frontend** (Vite dev server with hot module replacement):

Open a second terminal tab and run:

```bash
npm run dev
```

Vite proxies asset requests through the Laravel server — open **http://localhost:8000** in your browser (not the Vite port directly).

---

## Building for Production

Compile and version frontend assets:

```bash
npm run build
```

This outputs optimised files to `public/build/` which Laravel's Vite helper picks up automatically.

---

## Running Tests

Tests use an **SQLite in-memory database** so no extra database setup is required.

Run the full test suite:

```bash
php artisan test
```

Run only the API feature tests:

```bash
php artisan test tests/Feature/Api/V1/
```

---

## API Reference

All endpoints are prefixed with `/api/v1/`.

### Categories

| Method      | Endpoint                   | Description                                              |
|-------------|----------------------------|----------------------------------------------------------|
| `GET`       | `/api/v1/categories`       | List categories (`search`, `is_active` filters)          |
| `POST`      | `/api/v1/categories`       | Create a category                                        |
| `GET`       | `/api/v1/categories/{id}`  | Get a single category                                    |
| `PUT/PATCH` | `/api/v1/categories/{id}`  | Update a category                                        |
| `DELETE`    | `/api/v1/categories/{id}`  | Delete a category (soft delete)                          |

### Questions

| Method      | Endpoint                    | Description                                                             |
|-------------|-----------------------------|-------------------------------------------------------------------------|
| `GET`       | `/api/v1/questions`         | List questions (`category_id`, `difficulty`, `is_active`, `search`)     |
| `POST`      | `/api/v1/questions`         | Create a question (optionally with inline `answers` array)              |
| `GET`       | `/api/v1/questions/random`  | Get random active questions (`count`, `category_id`)                    |
| `GET`       | `/api/v1/questions/{id}`    | Get a single question with its answers                                  |
| `PUT/PATCH` | `/api/v1/questions/{id}`    | Update a question                                                       |
| `DELETE`    | `/api/v1/questions/{id}`    | Delete a question (soft delete)                                         |

### Answers

| Method      | Endpoint                          | Description                  |
|-------------|-----------------------------------|------------------------------|
| `GET`       | `/api/v1/questions/{id}/answers`  | List answers for a question  |
| `POST`      | `/api/v1/answers`                 | Create an answer             |
| `GET`       | `/api/v1/answers/{id}`            | Get a single answer          |
| `PUT/PATCH` | `/api/v1/answers/{id}`            | Update an answer             |
| `DELETE`    | `/api/v1/answers/{id}`            | Delete an answer             |

### Example requests

**Create a question with answers in one request:**

```bash
curl -X POST http://localhost:8000/api/v1/questions \
  -H "Content-Type: application/json" \
  -d '{
    "category_id": 1,
    "question": "What does a red traffic light mean?",
    "difficulty": "easy",
    "answers": [
      { "answer": "Stop",             "is_correct": true  },
      { "answer": "Slow down",        "is_correct": false },
      { "answer": "Speed up",         "is_correct": false },
      { "answer": "Yield to traffic", "is_correct": false }
    ]
  }'
```

**Get 5 random questions from a specific category:**

```bash
curl "http://localhost:8000/api/v1/questions/random?count=5&category_id=1"
```

---

## Project Structure

```
app/
├── Http/
│   ├── Controllers/Api/V1/   # API controllers (Category, Question, Answer)
│   ├── Middleware/            # HandleInertiaRequests
│   ├── Requests/Api/         # Form request validators
│   └── Resources/Api/        # API resource transformers
├── Models/                   # Eloquent models (Category, Question, Answer)
└── Services/                 # Business logic services
database/
├── factories/                # Model factories for seeding / testing
├── migrations/               # Database schema migrations
└── seeders/                  # DatabaseSeeder
resources/
├── css/app.css               # TailwindCSS entry point
├── js/
│   ├── app.js                # Inertia + Vue 3 bootstrap
│   ├── pages/                # Vue page components
│   ├── components/           # Reusable Vue components
│   └── layouts/              # Vue layout components
└── views/app.blade.php       # Inertia root Blade template
routes/
├── api.php                   # REST API routes (/api/v1/...)
└── web.php                   # Web / Inertia routes
tests/
└── Feature/Api/V1/           # API feature tests
```

---

## License

This project is open-sourced software licensed under the [MIT license](LICENSE).
