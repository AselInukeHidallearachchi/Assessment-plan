# Week 2: Detailed Code Explanations

This document provides deep-dive explanations for every concept, class, and pattern used in the Laravel CRUD module.

---

## Table of Contents

1. [Laravel Architecture Overview](#laravel-architecture-overview)
2. [Service Container & Dependency Injection](#service-container--dependency-injection)
3. [Facades Explained](#facades-explained)
4. [Migrations Deep Dive](#migrations-deep-dive)
5. [Eloquent ORM Internals](#eloquent-orm-internals)
6. [Middleware & Request Lifecycle](#middleware--request-lifecycle)
7. [Validation System](#validation-system)
8. [Blade Engine Mechanics](#blade-engine-mechanics)
9. [Security Features](#security-features)

---

## Laravel Architecture Overview

### The MVC Pattern in Laravel

```
┌─────────────┐     ┌──────────────┐     ┌─────────────┐
│   Browser   │────▶│    Routes    │────▶│ Controller  │
└─────────────┘     └──────────────┘     └──────┬──────┘
                                                │
                    ┌──────────────┐            │
                    │     View     │◀───────────┘
                    │   (Blade)    │
                    └──────▲───────┘
                           │
                    ┌──────┴───────┐
                    │    Model     │
                    │   (Eloquent) │
                    └──────┬───────┘
                           │
                    ┌──────▼───────┐
                    │  Database    │
                    └──────────────┘
```

### Request Flow

1. **HTTP Request** arrives at `public/index.php`
2. **Autoloader** loads framework files
3. **Service Container** is instantiated
4. **Kernel** bootstraps the application
5. **Router** matches URI to route definition
6. **Middleware** processes request (auth, CORS, etc.)
7. **Controller** executes business logic
8. **Model** interacts with database
9. **View** renders HTML response
10. **Response** sent back to browser

---

## Service Container & Dependency Injection

### What is the Service Container?

The service container is a **tool for managing class dependencies**. It automatically resolves and injects dependencies when creating objects.

### Example Without DI (Bad Practice)

```php
class PostController extends Controller
{
    protected $db;
    
    public function __construct()
    {
        // Hard dependency - difficult to test
        $this->db = new MySqlConnection();
    }
}
```

### Example With DI (Good Practice)

```php
class PostController extends Controller
{
    protected $postModel;
    
    // Laravel automatically injects Post model
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }
}
```

### How Route Model Binding Works

When you type-hint a model in a controller method:

```php
public function show(Post $post)
```

Laravel:
1. Sees the `{post}` parameter in the route
2. Extracts the ID from the URL (e.g., `/posts/5` → `5`)
3. Queries the database: `Post::find(5)`
4. Injects the resulting model instance
5. Returns 404 if not found

### Manual Binding Example

You can customize this behavior:

```php
// In RouteServiceProvider::boot()
Route::bind('post', function ($value) {
    return Post::where('slug', $value)->firstOrFail();
});
```

Now `/posts/my-first-post` works instead of `/posts/5`.

---

## Facades Explained

### What are Facades?

Facades provide a **static interface to classes** available in the service container. They make code more readable and expressive.

### Behind the Scenes

When you write:

```php
Schema::create('posts', function (Blueprint $table) {
    $table->id();
});
```

What actually happens:

```php
$app->make('db.schema')->create('posts', function (Blueprint $table) {
    $table->id();
});
```

### Common Facades Used in Week 2

| Facade | Underlying Class | Purpose |
|--------|-----------------|---------|
| `Schema` | `Illuminate\Database\Schema\Builder` | Database schema operations |
| `Route` | `Illuminate\Routing\Router` | Route definitions |
| `DB` | `Illuminate\Database\DatabaseManager` | Raw database queries |
| `View` | `Illuminate\View\Factory` | View rendering |
| `Redirect` | `Illuminate\Routing\Redirector` | HTTP redirects |

### Creating Custom Facades

```php
// 1. Create the class
class PaymentProcessor {
    public function process($amount) {
        // ...
    }
}

// 2. Bind to container (in ServiceProvider)
$this->app->singleton('payment', function () {
    return new PaymentProcessor();
});

// 3. Create facade class
class Payment extends Facade {
    protected static function getFacadeAccessor() {
        return 'payment';
    }
}

// 4. Use it
Payment::process(100);
```

---

## Migrations Deep Dive

### Migration File Structure

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            // Column definitions
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
```

### Why Anonymous Classes?

Laravel 9+ uses anonymous classes for migrations because:
- Cleaner syntax (no need to name every migration class)
- No naming conflicts
- Each migration file is independent

### Column Types Reference

| Method | SQL Type | Description |
|--------|----------|-------------|
| `id()` | UNSIGNED BIGINT | Auto-incrementing primary key |
| `string('name')` | VARCHAR(255) | Short text |
| `text('content')` | TEXT | Long text |
| `integer('count')` | INT | Integer number |
| `decimal('price', 8, 2)` | DECIMAL(8,2) | Decimal number |
| `boolean('active')` | BOOLEAN | True/false |
| `date('published_at')` | DATE | Date only |
| `datetime('scheduled_at')` | DATETIME | Date and time |
| `timestamp('posted_at')` | TIMESTAMP | Timestamp |
| `timestamps()` | created_at, updated_at | Both timestamps |
| `softDeletes()` | deleted_at | For soft deletes |
| `foreignId('user_id')` | UNSIGNED BIGINT | Foreign key ID |

### Modifiers

```php
$table->string('email')->unique()->index();
$table->integer('age')->nullable()->default(18);
$table->string('name')->after('id'); // Position
```

### Running Migrations

```bash
# Run all pending migrations
php artisan migrate

# Run migrations in dry-run mode (see what would run)
php artisan migrate --pretend

# Force migration in production
php artisan migrate --force

# Rollback last batch
php artisan migrate:rollback

# Rollback all migrations
php artisan migrate:reset

# Refresh (rollback + migrate)
php artisan migrate:refresh

# Fresh (drop all tables + migrate)
php artisan migrate:fresh
```

---

## Eloquent ORM Internals

### Active Record Pattern

Eloquent implements the **Active Record pattern**:
- Each model represents a table row
- Model handles its own persistence
- Business logic lives in the model

### How Eloquent Works

```php
// This code:
$posts = Post::where('status', 'published')
             ->latest()
             ->limit(10)
             ->get();

// Translates to:
SELECT * FROM posts 
WHERE status = 'published' 
ORDER BY created_at DESC 
LIMIT 10;
```

### Query Builder Chain

Each method returns a query builder instance:

```php
Post::query()           // SELECT * FROM posts
    ->where(...)        // Adds WHERE clause
    ->orderBy(...)      // Adds ORDER BY
    ->limit(...)        // Adds LIMIT
    ->get();            // Executes and returns Collection
```

### Mass Assignment Protection

```php
// Vulnerable without $fillable
Post::create($request->all()); // Could set any field!

// Protected with $fillable
protected $fillable = ['title', 'content', 'status'];
Post::create($request->only($fillable)); // Only allowed fields
```

### Alternative: $guarded

```php
// Block specific fields instead of allowing specific ones
protected $guarded = ['is_admin', 'role'];

// Or guard everything
protected $guarded = ['*'];
```

### Accessors & Mutators

Transform attributes when getting/setting:

```php
// Accessor - transform when reading
public function getTitleAttribute($value)
{
    return ucfirst($value);
}

// Usage: $post->title (automatically capitalized)

// Mutator - transform when writing
public function setTitleAttribute($value)
{
    $this->attributes['title'] = strtolower($value);
}

// Usage: $post->title = "Hello" (stored as "hello")

// Modern Laravel 9+ syntax
protected function title(): Attribute
{
    return Attribute::make(
        get: fn ($value) => ucfirst($value),
        set: fn ($value) => strtolower($value),
    );
}
```

### Scopes

Reusable query constraints:

```php
// Local scope
public function scopePublished($query)
{
    return $query->where('status', 'published');
}

// Usage: Post::published()->get();

// Global scope (always applied)
protected static function booted()
{
    static::addGlobalScope('active', function ($query) {
        $query->where('is_active', true);
    });
}
```

---

## Middleware & Request Lifecycle

### What is Middleware?

Middleware **filters HTTP requests** entering your application:

```
Request → Middleware 1 → Middleware 2 → Controller → Response
              ↓              ↓
         (Auth check)    (CORS headers)
```

### Built-in Middleware

| Middleware | Purpose |
|------------|---------|
| `auth` | Check if user is logged in |
| `guest` | Check if user is NOT logged in |
| `throttle` | Rate limiting |
| `cors` | Cross-Origin Resource Sharing |
| `verified` | Email verification required |

### Creating Custom Middleware

```bash
php artisan make:middleware CheckPostOwnership
```

```php
public function handle(Request $request, Closure $next)
{
    $post = $request->route('post');
    
    if ($post->author_id !== auth()->id()) {
        abort(403, 'Unauthorized action.');
    }
    
    return $next($request);
}
```

### Middleware Priority

Order matters! Middleware runs in the order defined in `bootstrap/app.php` or `app/Http/Kernel.php`.

---

## Validation System

### Validation Flow

```
Form Submit → Form Request → Validation Rules
                                      ↓
                              Passes? ──┬── Yes → Controller
                                      │
                                      └── No → Redirect Back + Errors
```

### Available Validation Rules

#### Common Rules

```php
'title' => 'required|string|max:255|min:3',
'email' => 'required|email|unique:users,email',
'password' => 'required|min:8|confirmed',
'age' => 'required|integer|between:18,100',
'status' => 'sometimes|in:draft,published,archived',
'website' => 'nullable|url',
'avatar' => 'image|mimes:jpg,png|max:2048',
'tags' => 'array',
'tags.*' => 'string|distinct',
```

#### Conditional Validation

```php
public function rules(): array
{
    $rules = [
        'title' => 'required|string|max:255',
    ];
    
    if ($this->isMethod('POST')) {
        // Only require password on creation
        $rules['password'] = 'required|min:8';
    }
    
    return $rules;
}
```

### Custom Validation Messages

```php
public function messages(): array
{
    return [
        'title.required' => 'A title is required to create a post.',
        'title.min' => 'The title must be at least :min characters.',
        'email.unique' => 'This email is already registered.',
    ];
}
```

### Custom Validation Attributes

Change field names in error messages:

```php
public function attributes(): array
{
    return [
        'author_name' => 'author',
        'content' => 'post content',
    ];
}
```

Error becomes: "The post content field is required."

---

## Blade Engine Mechanics

### How Blade Works

```
.blade.php → Blade Compiler → Cached PHP → Output HTML
```

Blade templates are compiled to plain PHP and cached until changes are made.

### Template Inheritance

```php
// Layout (parent)
@yield('content')

// Child view
@extends('layouts.app')
@section('content')
    <h1>Hello</h1>
@endsection
```

### Control Structures

```blade
@if($condition)
    // Show if true
@elseif($other)
    // Show if other
@else
    // Default
@endif

@foreach($items as $item)
    {{ $item->name }}
@endforeach

@forelse($items as $item)
    {{ $item->name }}
@empty
    <p>No items found.</p>
@endforelse

@for($i = 0; $i < 10; $i++)
    {{ $i }}
@endfor

@while($condition)
    // Loop
@endwhile
```

### Components & Slots (Modern Blade)

```blade
<!-- Component definition -->
<x-alert type="success">
    Operation completed!
</x-alert>

<!-- Inside component -->
<div class="alert alert-{{ $type }}">
    {{ $slot }}
</div>
```

### Stacks

Push content to sections defined in layout:

```blade
<!-- In layout -->
@stack('scripts')

<!-- In view -->
@push('scripts')
    <script src="/custom.js"></script>
@endpush
```

---

## Security Features

### CSRF Protection

Cross-Site Request Forgery tokens prevent malicious sites from submitting forms on behalf of users.

```blade
<form method="POST">
    @csrf  <!-- Generates hidden token field -->
</form>
```

Token is verified by `VerifyCsrfToken` middleware.

### XSS Prevention

Blade escapes output by default:

```blade
{{ $userInput }}  <!-- Escaped: <script> becomes &lt;script&gt; -->

{!! $trustedHtml !!}  <!-- Unescaped - use carefully! -->
```

### SQL Injection Prevention

Eloquent uses parameterized queries:

```php
// Safe - parameters are bound separately
Post::where('title', $userInput)->get();

// Dangerous - never do this!
DB::select("SELECT * FROM posts WHERE title = '$userInput'");
```

### Mass Assignment Vulnerability

```php
// Vulnerable
protected $guarded = [];  // All fields mass-assignable

// Protected
protected $fillable = ['title', 'content'];  // Only these fields
```

### Authentication & Authorization

```php
// In controller
public function edit(Post $post)
{
    // Check ownership
    if ($post->author_id !== auth()->id()) {
        abort(403);
    }
}

// Using Gates
Gate::define('update-post', function ($user, $post) {
    return $user->id === $post->author_id;
});

// Usage
if (Gate::allows('update-post', $post)) {
    // Can edit
}
```

---

## Performance Considerations

### N+1 Query Problem

```php
// BAD - N+1 queries
$posts = Post::all();
foreach ($posts as $post) {
    echo $post->author->name;  // Query per post!
}

// GOOD - Eager loading
$posts = Post::with('author')->get();  // 2 queries total
```

### Pagination vs Loading All

```php
// BAD - Memory issues with large datasets
$posts = Post::all();

// GOOD - Loads only needed records
$posts = Post::paginate(15);
```

### Caching Query Results

```php
$posts = Cache::remember('posts.index', 3600, function () {
    return Post::latest()->paginate(15);
});
```

---

## Debugging Tips

### Debug Bar

Install Laravel Debugbar for detailed insights:

```bash
composer require barryvdh/laravel-debugbar --dev
```

### Tinker for Testing

```bash
php artisan tinker
>>> Post::count()
>>> Post::latest()->first()->toArray()
>>> factory(Post::class, 10)->create()
```

### Logging

```php
Log::info('Post created', ['post' => $post->id]);
Log::error('Validation failed', ['errors' => $validator->errors()]);
```

Check logs in `storage/logs/laravel.log`.

### Query Log

```php
DB::enableQueryLog();
// ... run queries ...
dd(DB::getQueryLog());
```

---

## Summary

This document covered:

✅ Laravel's MVC architecture and request flow
✅ Service Container and Dependency Injection
✅ How Facades work under the hood
✅ Migration system and column types
✅ Eloquent ORM internals and patterns
✅ Middleware and request filtering
✅ Validation system and custom rules
✅ Blade templating engine mechanics
✅ Security features and best practices
✅ Performance optimization techniques
✅ Debugging tools and techniques

Master these concepts and you'll have a solid foundation for Laravel development!
