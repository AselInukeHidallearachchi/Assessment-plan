# Assessment Plan (Week 1 - Week 3)

This repository contains deliverables for the **One Month Performance Improvement Plan**.

## Completed Work

### Week 1 - Fundamentals & Structure (Completed)
Deliverables completed:
- Two responsive UI screens built with semantic HTML/CSS
- Clean folder structure and naming conventions
- Readable componentized styling

Primary files:
- `week-1/src/pages/screen-1.html`
- `week-1/src/pages/screen-2.html`

### Week 2 - Backend Basics & Validation (Completed)
Deliverables completed:
- Laravel project initialized in `week-2/`
- Product Catalog CRUD implemented
- Validation moved to Form Requests
- Business logic separated into service layer
- Use-case handlers introduced for clean orchestration
- Blade CRUD views implemented
- Feature tests added and passing
- MySQL runtime configuration set up

Primary files:
- `week-2/app/Http/Controllers/ProductController.php`
- `week-2/app/Handlers/Products/`
- `week-2/app/Services/ProductService.php`
- `week-2/app/Http/Requests/StoreProductRequest.php`
- `week-2/app/Http/Requests/UpdateProductRequest.php`
- `week-2/tests/Feature/ProductCrudTest.php`

### Week 3 - Performance & Error Handling (Completed)
Deliverables completed:
- Product image upload feature with validation
- Public storage handling for uploaded product images
- Product image cleanup on update/delete
- Structured exception handling for product operations
- Global user-safe error display
- Product listing performance optimization
- Additional feature tests for image upload flows

Primary files:
- `week-2/app/Exceptions/ProductOperationException.php`
- `week-2/app/Services/ProductService.php`
- `week-2/app/Handlers/Products/ProductHandler.php`
- `week-2/database/migrations/2026_05_12_090000_add_image_path_to_products_table.php`
- `week-2/resources/views/products/`
- `week-2/WEEK3_PERFORMANCE_ERROR_HANDLING_GUIDE.md`

## Week 2 Run Instructions

```bash
cd "/Users/aselinuke/Desktop/Assessment plan/week-2"
php artisan config:clear
php artisan migrate --force
php artisan storage:link
php artisan serve
```

Open:
- `http://127.0.0.1:8000/products`

## Week 2 Test Instructions

```bash
cd "/Users/aselinuke/Desktop/Assessment plan/week-2"
php artisan test
```

## Viva / Interview Preparation

Comprehensive viva guide:
- `week-2/WEEK2_VIVA_GUIDE.md`
- `week-2/WEEK3_PERFORMANCE_ERROR_HANDLING_GUIDE.md`

This includes:
- Architecture explanations
- likely viva questions and model answers
- demo flow
- command reference
