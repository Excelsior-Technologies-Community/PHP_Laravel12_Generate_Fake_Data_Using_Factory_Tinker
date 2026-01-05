# PHP_Laravel12_Generate_Fake_Data_Using_Factory_Tinker

## Project Overview

This project demonstrates how to generate fake data in Laravel using **Model Factories** and **Tinker**. It provides a complete, practical setup for creating, seeding, testing, and displaying fake data with relationships. This is ideal for learning, development, testing, and interview preparation.

---

## Prerequisites

Before starting, ensure you have the following installed:

* PHP 8.1 or higher
* Composer
* MySQL
* Laravel 12.x

---

## Installation Steps

### Step 1: Create Laravel Project

```bash
composer create-project laravel/laravel laravel-factory-demo
cd laravel-factory-demo
```

---

### Step 2: Configure Database

Edit the `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_factory
DB_USERNAME=root
DB_PASSWORD=yourpassword
```

Create database:

```sql
CREATE DATABASE laravel_factory;
```

---

### Step 3: Generate Model, Migration, Factory

```bash
php artisan make:model Post -mf
```

Files created:

* app/Models/Post.php
* database/migrations/xxxx_create_posts_table.php
* database/factories/PostFactory.php

---

### Step 4: Define Database Schema

Edit migration file:

```php
Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('content');
    $table->string('author');
    $table->boolean('is_published')->default(true);
    $table->integer('views')->default(0);
    $table->timestamp('published_at')->nullable();
    $table->timestamps();
});
```

Run migration:

```bash
php artisan migrate
```

---

### Step 5: Configure Model

```php
class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title','content','author','is_published','views','published_at'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime'
    ];
}
```

---

### Step 6: Create Factory Definition

```php
class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'content' => $this->faker->paragraphs(3, true),
            'author' => $this->faker->name(),
            'is_published' => $this->faker->boolean(80),
            'views' => $this->faker->numberBetween(0, 10000),
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    public function published()
    {
        return $this->state(fn () => [
            'is_published' => true,
            'published_at' => now(),
        ]);
    }

    public function unpublished()
    {
        return $this->state(fn () => [
            'is_published' => false,
            'published_at' => null,
        ]);
    }

    public function popular()
    {
        return $this->state(fn () => [
            'views' => $this->faker->numberBetween(5000, 100000),
        ]);
    }
}
```

---

## Using Tinker to Generate Fake Data

Start Tinker:

```bash
php artisan tinker
```

### Basic Operations

```php
App\Models\Post::factory()->create();
App\Models\Post::factory()->count(10)->create();
```

### Factory States

```php
App\Models\Post::factory()->published()->create();
App\Models\Post::factory()->unpublished()->create();
App\Models\Post::factory()->popular()->create();
```
---
## Screenshot
<img width="1786" height="923" alt="image" src="https://github.com/user-attachments/assets/3b4477d2-a7b0-46d0-8c73-885a7a48eb9b" />
<img width="1788" height="950" alt="image" src="https://github.com/user-attachments/assets/6290e361-0256-47bb-bcb2-7741aea652ed" />

<img width="1773" height="929" alt="image" src="https://github.com/user-attachments/assets/952a2b46-c5d9-4686-9f82-8652b4ec2c12" />



---

## Creating Relationships

### User Model

```php
public function posts()
{
    return $this->hasMany(Post::class, 'author_id');
}
```

### Post Model

```php
public function user()
{
    return $this->belongsTo(User::class, 'author_id');
}
```

### Migration Update

```php
$table->foreignId('author_id')->nullable()->constrained('users');
```

---

## Database Seeding

Create Seeder:

```bash
php artisan make:seeder PostSeeder
```

```php
class PostSeeder extends Seeder
{
    public function run(): void
    {
        Post::factory()->count(50)->create();
        Post::factory()->count(10)->published()->create();
        Post::factory()->count(5)->unpublished()->create();
        Post::factory()->count(7)->popular()->create();
    }
}
```

Run Seeder:

```bash
php artisan db:seed
php artisan migrate:fresh --seed
```

---

## Testing with Factories

```bash
php artisan make:test PostTest
```

```php
public function test_post_index_page()
{
    $posts = Post::factory()->count(5)->create();
    $this->get('/posts')->assertStatus(200);
}
```

Run tests:

```bash
php artisan test
```

---

## Best Practices

* Always define fillable or guarded fields
* Use factory states for different scenarios
* Use seeders for realistic data
* Keep factories readable and simple
* Use relationships for production-like data

---

## Project Structure

```text
laravel-factory-demo/
├── app/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── routes/
├── resources/
└── tests/
```

---

## Conclusion

This project shows how Laravel Factories and Tinker help generate realistic fake data quickly. It improves development speed, testing accuracy, and UI development quality while keeping the code clean and maintainable.
