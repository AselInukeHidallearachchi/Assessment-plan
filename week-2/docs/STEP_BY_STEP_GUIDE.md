# Week 2: Step-by-Step Implementation Guide

This guide walks you through building a complete Laravel CRUD module **line by line**, with detailed explanations for every concept.

---

## Table of Contents

1. [Prerequisites & Setup](#prerequisites--setup)
2. [Step 1: Create Database Migration](#step-1-create-database-migration)
3. [Step 2: Create the Model](#step-2-create-the-model)
4. [Step 3: Create Form Request Classes](#step-3-create-form-request-classes)
5. [Step 4: Create the Controller](#step-4-create-the-controller)
6. [Step 5: Define Routes](#step-5-define-routes)
7. [Step 6: Create Blade Views](#step-6-create-blade-views)
8. [Step 7: Test Everything](#step-7-test-everything)
9. [Common Mistakes to Avoid](#common-mistakes-to-avoid)

---

## Prerequisites & Setup

### What You Need Before Starting

- **PHP 8.1 or higher** - Laravel 10+ requires modern PHP features
- **Composer** - PHP dependency manager
- **A database** - MySQL, PostgreSQL, or SQLite
- **Basic understanding of**:
  - PHP syntax (variables, functions, classes)
  - Object-Oriented Programming (OOP) concepts
  - MVC architecture pattern
  - SQL basics

### Setting Up Your Environment

#### Option A: Fresh Laravel Installation

```bash
cd /workspace/week-2/src
composer create-project laravel/laravel .
```

This command:
- Downloads Laravel framework
- Sets up default directory structure
- Installs all required dependencies
- Creates `.env` file with default configuration

#### Option B: If Files Already Exist

```bash
cd /workspace/week-2/src
composer install
```

#### Configure Your Database

Edit the `.env` file in your project root:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=week2_crud
DB_USERNAME=root
DB_PASSWORD=your_password
```

For SQLite (simpler for learning):

```env
DB_CONNECTION=sqlite
# Remove other DB_* lines
```

Then create the database:

```bash
# For MySQL
mysql -u root -p -e "CREATE DATABASE week2_crud;"

# For SQLite
touch database/database.sqlite
```

Generate application key:

```bash
php artisan key:generate
```

Run initial migrations:

```bash
php artisan migrate
```

---

## Step 1: Create Database Migration

### What is a Migration?

A migration is like **version control for your database**. It allows you to:
- Define database schema in PHP code
- Share schema changes with team members
- Rollback changes if needed
- Track database evolution over time

### Creating the Migration

Run this command:

```bash
php artisan make:migration create_posts_table
```

This creates a file in `database/migrations/` with a timestamp prefix like:
`2024_01_01_000000_create_posts_table.php`

### Understanding the Migration File

Open the created file. You'll see:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Schema definition goes here
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rollback logic goes here
    }
};
```

#### Line-by-Line Explanation:

**Line 1:** `<?php`
- PHP opening tag - tells PHP to interpret this file

**Line 3:** `use Illuminate\Database\Migrations\Migration;`
- Imports the base Migration class from Laravel
- This gives us access to migration functionality

**Line 4:** `use Illuminate\Database\Schema\Blueprint;`
- Imports Blueprint class for defining table structure
- Blueprint provides methods like `string()`, `text()`, `timestamps()`

**Line 5:** `use Illuminate\Support\Facades\Schema;`
- Imports Schema facade for database operations
- Facade provides static-like interface to database schema

**Line 7:** `return new class extends Migration`
- Creates an anonymous class that extends Migration
- This is Laravel's modern migration syntax (Laravel 9+)

**Line 10:** `public function up(): void`
- The `up()` method runs when you execute `php artisan migrate`
- Contains logic to create/modify tables
- `: void` means this method returns nothing

**Line 17:** `public function down(): void`
- The `down()` method runs when you rollback (`php artisan migrate:rollback`)
- Should reverse whatever `up()` does
- Important for clean database management

### Adding Table Columns

Now modify the `up()` method:

```php
public function up(): void
{
    Schema::create('posts', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('content');
        $table->string('author_name');
        $table->string('status')->default('draft');
        $table->timestamps();
    });
}
```

#### Column Definitions Explained:

**`$table->id();`**
- Creates an auto-incrementing integer primary key
- Named `id` by convention
- Essential for identifying each record uniquely

**`$table->string('title');`**
- Creates a VARCHAR column (default length: 255)
- Perfect for short text like titles
- Required field (no `nullable()` means NOT NULL)

**`$table->text('content');`**
- Creates a TEXT column for longer content
- Can store much more than string (up to 65,535 characters)
- Used for blog post body content

**`$table->string('author_name');`**
- Another VARCHAR column for author name
- Could be a foreign key to users table in real apps
- Simplified here for learning purposes

**`$table->string('status')->default('draft');`**
- Creates status column with a default value
- `'draft'` is automatically assigned if no value provided
- Useful for workflow states (draft, published, archived)

**`$table->timestamps();`**
- Adds two columns: `created_at` and `updated_at`
- Automatically managed by Laravel Eloquent
- Tracks when records are created and modified

### Implementing the Rollback

Add the `down()` method:

```php
public function down(): void
{
    Schema::dropIfExists('posts');
}
```

**Explanation:**
- `dropIfExists('posts')` removes the posts table if it exists
- Safe operation - won't error if table doesn't exist
- Must match the table name from `up()`

### Running the Migration

Execute:

```bash
php artisan migrate
```

You should see output like:
```
Migrating: 2024_01_01_000000_create_posts_table
Migrated:  2024_01_01_000000_create_posts_table (XX ms)
```

Verify in your database:
```sql
DESCRIBE posts;
```

---

## Step 2: Create the Model

### What is a Model?

A Model represents a **single database table** and provides:
- Object-oriented interface to database records
- Built-in CRUD operations
- Relationship definitions
- Business logic encapsulation

### Creating the Model

Run:

```bash
php artisan make:model Post
```

This creates `app/Models/Post.php`

### Understanding the Model File

Open `app/Models/Post.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // Additional configuration goes here
}
```

#### Line-by-Line Explanation:

**Line 3:** `namespace App\Models;`
- Defines the namespace for this class
- Prevents naming conflicts with other classes
- Follows PSR-4 autoloading standard

**Line 5:** `use Illuminate\Database\Eloquent\Factories\HasFactory;`
- Imports trait for model factories
- Factories help generate test data
- Used for seeding and testing

**Line 6:** `use Illuminate\Database\Eloquent\Model;`
- Imports the base Eloquent Model class
- Provides all ORM (Object-Relational Mapping) functionality

**Line 8:** `class Post extends Model`
- Declares Post class inheriting from Model
- Convention: singular, PascalCase class name
- Maps to `posts` table (plural, snake_case) automatically

**Line 10:** `use HasFactory;`
- Uses the HasFactory trait
- Enables factory pattern for creating test records

### Configuring the Model

Add fillable properties and any custom logic:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'author_name',
        'status',
    ];

    /**
     * Get the posts that are published.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
```

#### New Additions Explained:

**`protected $fillable = [...]`**
- Defines which attributes can be mass-assigned
- Security feature against mass assignment vulnerabilities
- Only listed fields can be set via `create()` or `fill()`
- Example: `Post::create($request->only($fillable))`

**Why not allow all fields?**
- Prevents malicious users from setting protected fields
- Example attack: setting `is_admin = true` via form
- Always explicitly define fillable fields

**`scopePublished($query)`**
- Defines a local query scope
- Allows: `Post::published()->get()`
- Reusable query constraints
- Keeps queries DRY (Don't Repeat Yourself)

### Model Conventions Laravel Uses:

| Convention | Example |
|------------|---------|
| Table name | `Post` → `posts` |
| Primary key | `id` (auto-detected) |
| Timestamps | `created_at`, `updated_at` |
| Foreign key | `post_id` (singular + _id) |

You can override these if needed:

```php
// Custom table name
protected $table = 'blog_posts';

// Disable timestamps
public $timestamps = false;

// Custom primary key
protected $primaryKey = 'post_uuid';
public $incrementing = false;
public $keyType = 'string';
```

---

## Step 3: Create Form Request Classes

### What are Form Requests?

Form Request classes handle **validation logic separately from controllers**:
- Cleaner controllers (single responsibility)
- Reusable validation rules
- Automatic error responses
- Authorization checks

### Creating Form Requests

Create two requests (one for storing, one for updating):

```bash
php artisan make:request StorePostRequest
php artisan make:request UpdatePostRequest
```

This creates:
- `app/Http/Requests/StorePostRequest.php`
- `app/Http/Requests/UpdatePostRequest.php`

### Understanding StorePostRequest

Open `app/Http/Requests/StorePostRequest.php`:

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Rules go here
        ];
    }
}
```

#### Line-by-Line Explanation:

**Line 5:** `use Illuminate\Foundation\Http\FormRequest;`
- Imports the base FormRequest class
- Provides validation and authorization functionality

**Line 7:** `class StorePostRequest extends FormRequest`
- Extends FormRequest to inherit validation behavior
- Laravel automatically runs validation before controller method

**`authorize(): bool`**
- Determines if user can make this request
- Return `true` to allow, `false` to deny
- Returns 403 Forbidden if unauthorized

**Change `authorize()` to:**

```php
public function authorize(): bool
{
    return true;
}
```

For now, we allow all authenticated users. In real apps, check roles/permissions.

### Defining Validation Rules

Update the `rules()` method in `StorePostRequest`:

```php
public function rules(): array
{
    return [
        'title' => 'required|string|max:255',
        'content' => 'required|string|min:50',
        'author_name' => 'required|string|max:100',
        'status' => 'sometimes|in:draft,published,archived',
    ];
}
```

#### Rule Breakdown:

**`'title' => 'required|string|max:255'`**
- `required`: Field must be present and not empty
- `string`: Must be a string type
- `max:255`: Maximum 255 characters

**`'content' => 'required|string|min:50'`**
- `min:50`: At least 50 characters (ensures meaningful content)
- Prevents very short posts

**`'author_name' => 'required|string|max:100'`**
- Similar to title but max 100 chars

**`'status' => 'sometimes|in:draft,published,archived'`**
- `sometimes`: Only validate if present (allows default value)
- `in:...`: Must be one of the listed values
- Prevents invalid status values

### Adding Custom Error Messages (Optional)

Add this method for user-friendly messages:

```php
public function messages(): array
{
    return [
        'title.required' => 'Please provide a title for your post.',
        'content.min' => 'Content must be at least 50 characters long.',
        'status.in' => 'Status must be draft, published, or archived.',
    ];
}
```

### Understanding UpdatePostRequest

Open `app/Http/Requests/UpdatePostRequest.php`:

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string|min:50',
            'author_name' => 'sometimes|required|string|max:100',
            'status' => 'sometimes|in:draft,published,archived',
        ];
    }
}
```

#### Key Differences from StorePostRequest:

**`sometimes` rule:**
- Fields are optional in update requests
- User might only want to update title, not content
- But if provided, must still meet validation criteria

---

## Step 4: Create the Controller

### What is a Controller?

Controllers **handle HTTP requests** and return responses:
- Receive input from routes
- Validate data (via Form Requests)
- Interact with models
- Return views or JSON

### Creating the Controller

Run:

```bash
php artisan make:controller PostController --resource
```

The `--resource` flag creates all CRUD methods automatically.

### Understanding the Controller

Open `app/Http/Controllers/PostController.php`:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
```

#### Import Statements Explained:

**`use App\Models\Post;`**
- Imports our Post model
- Needed to interact with posts table

**`use App\Http\Requests\StorePostRequest;`**
- Imports the form request for validation
- Type-hinted in `store()` method

**`use App\Http\Requests\UpdatePostRequest;`**
- Similar for update operations

**`use Illuminate\Http\Request;`**
- Laravel's HTTP request object
- Contains all request data (though we use Form Requests)

### Implementing Each Method

#### 1. `index()` - List All Posts

```php
public function index()
{
    $posts = Post::latest()->paginate(10);
    
    return view('posts.index', compact('posts'));
}
```

**Explanation:**
- `Post::latest()` - Orders by `created_at` DESC (newest first)
- `paginate(10)` - Shows 10 posts per page with pagination links
- `view('posts.index', ...)` - Returns Blade template
- `compact('posts')` - Passes $posts variable to view

#### 2. `create()` - Show Create Form

```php
public function create()
{
    return view('posts.create');
}
```

**Explanation:**
- Simply returns the create form view
- No data needed initially

#### 3. `store()` - Save New Post

```php
public function store(StorePostRequest $request)
{
    $post = Post::create($request->validated());
    
    return redirect()
        ->route('posts.show', $post)
        ->with('success', 'Post created successfully!');
}
```

**Line-by-Line:**

**`StorePostRequest $request`**
- Laravel automatically validates incoming request
- If validation fails, redirects back with errors
- If passes, controller method executes

**`$request->validated()`**
- Returns only validated data
- Safe to use for mass assignment
- Filters out any non-validated fields

**`Post::create(...)`**
- Creates new record in database
- Returns the saved Post model instance
- Uses `$fillable` property for security

**`redirect()->route(...)`**
- Redirects to another route
- `posts.show` is the route name
- `$post` passes the ID (route model binding)

**`->with('success', ...)`**
- Flashes success message to session
- Available in next request only
- Common pattern for user feedback

#### 4. `show()` - Display Single Post

```php
public function show(Post $post)
{
    return view('posts.show', compact('post'));
}
```

**Route Model Binding:**
- Laravel automatically injects the Post model
- Based on `{post}` parameter in route
- Fetches from database by ID
- Returns 404 if not found

#### 5. `edit()` - Show Edit Form

```php
public function edit(Post $post)
{
    return view('posts.edit', compact('post'));
}
```

**Explanation:**
- Similar to `show()` but returns edit form
- Form will be pre-populated with existing data

#### 6. `update()` - Update Existing Post

```php
public function update(UpdatePostRequest $request, Post $post)
{
    $post->update($request->validated());
    
    return redirect()
        ->route('posts.show', $post)
        ->with('success', 'Post updated successfully!');
}
```

**Key Points:**
- Uses `UpdatePostRequest` for validation
- `$post->update()` updates existing record
- Same redirect pattern as `store()`

#### 7. `destroy()` - Delete Post

```php
public function destroy(Post $post)
{
    $post->delete();
    
    return redirect()
        ->route('posts.index')
        ->with('success', 'Post deleted successfully!');
}
```

**Explanation:**
- Deletes the post from database
- Redirects to index page
- Shows confirmation message

---

## Step 5: Define Routes

### What are Routes?

Routes **map URLs to controller actions**:
- Define application endpoints
- Support different HTTP methods (GET, POST, PUT, DELETE)
- Can include middleware for authentication, etc.

### Adding Resource Routes

Open `routes/web.php`:

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('posts', PostController::class);
```

#### Understanding `Route::resource()`

This single line creates **7 RESTful routes**:

| HTTP Method | URI | Action | Route Name |
|-------------|-----|--------|------------|
| GET | `/posts` | index | posts.index |
| GET | `/posts/create` | create | posts.create |
| POST | `/posts` | store | posts.store |
| GET | `/posts/{post}` | show | posts.show |
| GET | `/posts/{post}/edit` | edit | posts.edit |
| PUT/PATCH | `/posts/{post}` | update | posts.update |
| DELETE | `/posts/{post}` | destroy | posts.destroy |

View all routes with:
```bash
php artisan route:list
```

### Adding a Root Redirect (Optional)

Update `web.php`:

```php
Route::get('/', function () {
    return redirect()->route('posts.index');
});
```

Now visiting `/` redirects to `/posts`.

---

## Step 6: Create Blade Views

### What is Blade?

Blade is Laravel's **templating engine**:
- Clean, readable syntax
- Template inheritance (layouts)
- Control structures (@if, @foreach)
- Escaped output by default (security)

### Creating the Layout

Create `resources/views/layouts/app.blade.php`:

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Blog Posts')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f5f5f5;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            background: #fff;
            padding: 20px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2563eb;
            text-decoration: none;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
        }
        
        .btn:hover {
            background: #1d4ed8;
        }
        
        .btn-sm {
            padding: 5px 10px;
            font-size: 0.8rem;
        }
        
        .btn-danger {
            background: #dc2626;
        }
        
        .btn-danger:hover {
            background: #b91c1c;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        
        .card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        
        textarea {
            min-height: 200px;
            resize: vertical;
        }
        
        .error {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 5px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        th {
            background: #f9fafb;
            font-weight: 600;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .badge-draft {
            background: #fef3c7;
            color: #92400e;
        }
        
        .badge-published {
            background: #d1fae5;
            color: #065f46;
        }
        
        .badge-archived {
            background: #e5e7eb;
            color: #374151;
        }
        
        .actions {
            display: flex;
            gap: 10px;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            gap: 5px;
            margin-top: 20px;
        }
        
        .pagination a,
        .pagination span {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-decoration: none;
            color: #2563eb;
        }
        
        .pagination .active {
            background: #2563eb;
            color: white;
            border-color: #2563eb;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <nav>
                <a href="{{ route('posts.index') }}" class="logo">📝 Blog Manager</a>
                <a href="{{ route('posts.create') }}" class="btn">New Post</a>
            </nav>
        </div>
    </header>
    
    <main class="container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @yield('content')
    </main>
</body>
</html>
```

#### Blade Syntax Explained:

**`@yield('title', 'Blog Posts')`**
- Defines a section that child views can override
- Second parameter is default value
- Allows each page to have unique title

**`{{ route('posts.index') }}`**
- Generates URL for named route
- Safer than hardcoding URLs
- Updates automatically if routes change

**`@if(session('success'))`**
- Blade conditional statement
- Checks if success message exists in session
- Equivalent to PHP `<?php if(): ?>`

**`{{ session('success') }}`**
- Outputs escaped session data
- Double braces escape HTML (security)
- Prevents XSS attacks

**`@yield('content')`**
- Main content area
- Child views provide actual content
- Template inheritance pattern

### Creating the Index View

Create `resources/views/posts/index.blade.php`:

```blade
@extends('layouts.app')

@section('title', 'All Posts')

@section('content')
<div class="card">
    <h1>All Posts</h1>
    
    @if($posts->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($posts as $post)
                <tr>
                    <td>{{ $post->title }}</td>
                    <td>{{ $post->author_name }}</td>
                    <td>
                        <span class="badge badge-{{ $post->status }}">
                            {{ ucfirst($post->status) }}
                        </span>
                    </td>
                    <td>{{ $post->created_at->format('M d, Y') }}</td>
                    <td class="actions">
                        <a href="{{ route('posts.show', $post) }}" class="btn btn-sm">View</a>
                        <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm">Edit</a>
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" 
                                    onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="pagination">
            {{ $posts->links() }}
        </div>
    @else
        <p>No posts yet. <a href="{{ route('posts.create') }}">Create your first post!</a></p>
    @endif
</div>
@endsection
```

#### New Blade Concepts:

**`@extends('layouts.app')`**
- Specifies parent layout
- Inherits all HTML structure from app.blade.php

**`@section('title', ...)`**
- Overrides the title section
- Short syntax for simple content

**`@section('content') ... @endsection`**
- Defines content block
- Injected into layout's `@yield('content')`

**`@foreach($posts as $post)`**
- Loops through posts collection
- Equivalent to PHP foreach loop

**`{{ $post->created_at->format('M d, Y') }}`**
- Accesses Carbon date object
- Formats date nicely
- Laravel automatically casts timestamps to Carbon

**`@csrf`**
- Adds CSRF token hidden field
- Protects against Cross-Site Request Forgery
- Required for all POST/PUT/DELETE forms

**`@method('DELETE')`**
- Spoofs HTTP method
- HTML forms only support GET/POST
- Laravel recognizes this as DELETE request

**`{{ $posts->links() }}`**
- Renders pagination links
- Automatically generated from paginator
- Includes previous/next buttons

### Creating the Create View

Create `resources/views/posts/create.blade.php`:

```blade
@extends('layouts.app')

@section('title', 'Create Post')

@section('content')
<div class="card">
    <h1>Create New Post</h1>
    
    <form action="{{ route('posts.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" 
                   id="title" 
                   name="title" 
                   value="{{ old('title') }}" 
                   required>
            @error('title')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="content">Content</label>
            <textarea id="content" 
                      name="content" 
                      required>{{ old('content') }}</textarea>
            @error('content')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="author_name">Author Name</label>
            <input type="text" 
                   id="author_name" 
                   name="author_name" 
                   value="{{ old('author_name') }}" 
                   required>
            @error('author_name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>Archived</option>
            </select>
            @error('status')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn">Create Post</button>
            <a href="{{ route('posts.index') }}" class="btn" style="background:#6b7280;">Cancel</a>
        </div>
    </form>
</div>
@endsection
```

#### Important Concepts:

**`old('title')`**
- Retrieves old input after validation failure
- Preserves user's input
- Better UX than making them retype everything

**`@error('title')`**
- Checks if validation error exists for field
- Displays error message from Form Request
- `$message` contains the error text

**`required` attribute**
- HTML5 client-side validation
- Works alongside server-side validation
- Provides immediate feedback

### Creating the Edit View

Create `resources/views/posts/edit.blade.php`:

```blade
@extends('layouts.app')

@section('title', 'Edit Post')

@section('content')
<div class="card">
    <h1>Edit Post</h1>
    
    <form action="{{ route('posts.update', $post) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" 
                   id="title" 
                   name="title" 
                   value="{{ old('title', $post->title) }}" 
                   required>
            @error('title')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="content">Content</label>
            <textarea id="content" 
                      name="content" 
                      required>{{ old('content', $post->content) }}</textarea>
            @error('content')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="author_name">Author Name</label>
            <input type="text" 
                   id="author_name" 
                   name="author_name" 
                   value="{{ old('author_name', $post->author_name) }}" 
                   required>
            @error('author_name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>Published</option>
                <option value="archived" {{ old('status', $post->status) === 'archived' ? 'selected' : '' }}>Archived</option>
            </select>
            @error('status')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn">Update Post</button>
            <a href="{{ route('posts.show', $post) }}" class="btn" style="background:#6b7280;">Cancel</a>
        </div>
    </form>
</div>
@endsection
```

#### Key Difference from Create:

**`old('title', $post->title)`**
- Second parameter is default value
- Shows existing post data
- Falls back to old input if validation failed

**`@method('PUT')`**
- Specifies UPDATE HTTP method
- Required for update operations

### Creating the Show View

Create `resources/views/posts/show.blade.php`:

```blade
@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
        <h1>{{ $post->title }}</h1>
        <span class="badge badge-{{ $post->status }}">
            {{ ucfirst($post->status) }}
        </span>
    </div>
    
    <div style="color:#6b7280;margin-bottom:20px;">
        <strong>Author:</strong> {{ $post->author_name }} |
        <strong>Created:</strong> {{ $post->created_at->format('F d, Y \a\t g:i A') }}
        @if($post->updated_at != $post->created_at)
            | <strong>Updated:</strong> {{ $post->updated_at->format('F d, Y \a\t g:i A') }}
        @endif
    </div>
    
    <div style="line-height:1.8;white-space:pre-wrap;">{{ $post->content }}</div>
    
    <div style="margin-top:30px;display:flex;gap:10px;">
        <a href="{{ route('posts.edit', $post) }}" class="btn">Edit</a>
        <form action="{{ route('posts.destroy', $post) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" 
                    onclick="return confirm('Are you sure you want to delete this post?')">Delete</button>
        </form>
        <a href="{{ route('posts.index') }}" class="btn" style="background:#6b7280;">Back to List</a>
    </div>
</div>
@endsection
```

---

## Step 7: Test Everything

### Manual Testing Checklist

1. **Visit `/posts`**
   - Should see empty list or existing posts
   - "New Post" button visible
   
2. **Click "New Post"**
   - Form displays correctly
   - All fields present

3. **Submit Invalid Data**
   - Leave title empty → See error
   - Content < 50 chars → See error
   - Errors clear after fixing

4. **Submit Valid Data**
   - All fields filled correctly
   - Redirects to show page
   - Success message appears
   - Data saved in database

5. **View Post**
   - All data displays correctly
   - Edit and Delete buttons visible

6. **Edit Post**
   - Form pre-populated
   - Changes save correctly
   - Updated timestamp changes

7. **Delete Post**
   - Confirmation dialog appears
   - Post removed from database
   - Redirects to index

8. **Test Pagination**
   - Create 11+ posts
   - Pagination links appear
   - Navigate between pages

### Testing Commands

```bash
# Check routes
php artisan route:list --path=posts

# Check database
php artisan tinker
>>> App\Models\Post::count()
>>> App\Models\Post::latest()->first()

# Clear caches if issues occur
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

---

## Common Mistakes to Avoid

### 1. Forgetting CSRF Token
❌ Wrong:
```blade
<form action="/posts" method="POST">
```

✅ Correct:
```blade
<form action="/posts" method="POST">
    @csrf
```

### 2. Not Using Fillable Property
❌ Security risk - allows mass assignment of all fields

✅ Always define `$fillable` in model

### 3. Putting Logic in Controllers
❌ Wrong:
```php
public function store(Request $request) {
    // Validation here
    // Business logic here
    // Database queries here
}
```

✅ Correct:
```php
public function store(StorePostRequest $request) {
    // Only orchestration
    $post = Post::create($request->validated());
    return redirect()->route('posts.show', $post);
}
```

### 4. Hardcoding URLs
❌ Wrong:
```blade
<a href="/posts/1/edit">Edit</a>
```

✅ Correct:
```blade
<a href="{{ route('posts.edit', $post) }}">Edit</a>
```

### 5. Not Handling Empty States
Always check if data exists before looping:
```blade
@if($posts->count() > 0)
    @foreach($posts as $post)
    @endforeach
@else
    <p>No posts found.</p>
@endif
```

---

## Summary

You've now built a complete Laravel CRUD module with:

✅ Database migration with proper schema
✅ Eloquent model with fillable properties  
✅ Form Request validation classes
✅ Resource controller with all CRUD methods
✅ RESTful routing
✅ Blade templates with layout inheritance
✅ Proper error handling and user feedback
✅ Security best practices (CSRF, mass assignment protection)

### What You Learned:

1. **MVC Architecture** - Clear separation of concerns
2. **Validation** - Server-side validation with Form Requests
3. **Eloquent ORM** - Database operations using models
4. **Blade Templating** - Clean, secure views
5. **Routing** - RESTful resource routes
6. **Best Practices** - Security, code organization, UX

### Next Steps:

1. Review every line of code
2. Experiment with modifications
3. Add features (search, filtering, categories)
4. Prepare for Week 3 (Performance & Error Handling)

---

**Remember:** Understanding WHY each line exists is more important than memorizing it. Ask yourself:
- What problem does this solve?
- What happens if I remove it?
- How could this be improved?

Happy coding! 🚀
