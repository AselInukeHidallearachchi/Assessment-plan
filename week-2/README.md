## Week 2 — Backend Basics & Validation (Laravel CRUD Module)

### Goal (from plan)

Strengthen backend fundamentals by building a **small CRUD module** using Laravel with proper:
- **Form Request validation**
- **Business logic handling**
- **MVC structure separation**

---

### Focus Areas

1. **Laravel Fundamentals**
   - Routing
   - Controllers
   - Models & Migrations
   - Blade Templates
   - Form Requests

2. **Validation**
   - Using Form Request classes
   - Custom validation rules
   - Error message handling

3. **Business Logic**
   - Keeping controllers thin
   - Using Services or Model methods for logic
   - Proper separation of concerns

4. **MVC Structure**
   - Model: Data and business rules
   - View: Presentation layer (Blade)
   - Controller: Request handling and response

---

### Deliverables

Build a **Blog Post Management Module** with full CRUD operations:

1. **Create** - Add new blog posts with title, content, author, status
2. **Read** - List all posts, view single post details
3. **Update** - Edit existing posts
4. **Delete** - Remove posts (with confirmation)

#### Required Features:
- Form validation using **Form Request** classes
- Proper error handling and user feedback
- Clean URL routing (RESTful conventions)
- Semantic Blade templates
- Database migrations for the `posts` table

---

### Folder Structure

```text
week-2/
  src/
    app/
      Http/
        Controllers/
          PostController.php
        Requests/
          StorePostRequest.php
          UpdatePostRequest.php
      Models/
        Post.php
      Providers/
    config/
    database/
      migrations/
        2024_01_01_000000_create_posts_table.php
    routes/
      web.php
    resources/
      views/
        layouts/
          app.blade.php
        posts/
          index.blade.php
          create.blade.php
          edit.blade.php
          show.blade.php
  docs/
    EXPLANATIONS.md
    STEP_BY_STEP_GUIDE.md
```

---

### How to Run

#### Prerequisites
- PHP 8.1+ installed
- Composer installed
- MySQL/SQLite/PostgreSQL database

#### Setup Steps

```bash
# Navigate to week-2 directory
cd week-2/src

# Create new Laravel project (if starting fresh)
composer create-project laravel/laravel . 

# Or if files are already in place, ensure dependencies are installed
composer install

# Configure your .env file with database credentials
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate

# Start development server
php artisan serve
```

Then visit: `http://localhost:8000/posts`

---

### Step-by-Step Implementation Plan

#### Step 1: Database Migration
Create the `posts` table with necessary columns.

#### Step 2: Model Creation
Create the `Post` model with fillable properties and relationships.

#### Step 3: Form Requests
Create validation logic in dedicated Form Request classes.

#### Step 4: Controller
Implement CRUD methods following RESTful conventions.

#### Step 5: Routes
Define RESTful routes for the posts resource.

#### Step 6: Views
Create Blade templates for each CRUD operation.

#### Step 7: Testing
Test all CRUD operations manually or with automated tests.

---

### What to Look For (Supervisor Checklist)

- ✅ **Correct use of Laravel basics**
  - Proper service container usage
  - Dependency injection
  - Facades used appropriately

- ✅ **Clean separation of logic**
  - Controllers handle only request/response
  - Business logic in Models or Services
  - Validation in Form Requests

- ✅ **Proper MVC structure**
  - No business logic in views
  - No SQL queries in controllers
  - Clear responsibility boundaries

- ✅ **Code quality**
  - Meaningful variable names
  - Consistent coding style (PSR-12)
  - Comments where necessary

- ✅ **Error handling**
  - Graceful failure messages
  - User-friendly validation errors
  - Proper HTTP status codes

---

### Learning Outcomes

After completing Week 2, you should be able to:

1. Explain the purpose of each component in Laravel's MVC architecture
2. Write clean, validated forms using Form Request classes
3. Structure a Laravel application following best practices
4. Handle CRUD operations efficiently
5. Understand the flow from route → controller → model → view

---

### Next Steps

Once completed:
1. Review all code line-by-line
2. Document any questions in `docs/QUESTIONS.md`
3. Prepare for Week 3 (Performance & Error Handling)
