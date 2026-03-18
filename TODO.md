# Create Meets Feature - Implementation Progress

## Plan Summary
Add user-created "Meets" on `/user2026/feeds` page. Meets become feeds with like/comment/share + "Ikut" (join) button.

**Status: [IN PROGRESS]**

## TODO Steps (Sequential)

### 1. [✅] DB Migrations
   - ✅ Created `database/migrations/2024_11_25_000001_add_meets_fields_to_feeds_table.php`
   - ✅ Created `database/migrations/2024_11_25_000002_create_feed_user_joins_table.php`
   - [ ] Run `php artisan migrate`

### 2. [ ] Models
   - Edit `app/Models/Feed.php` (+ fillable, joinedBy relation)
   - Create `app/Models/FeedUserJoin.php`

### 3. [ ] Validation
   - Create `app/Http/Requests/UserStoreFeedRequest.php`

### 4. [ ] Controller
   - Edit `app/Http/Controllers/FeedController.php` (+ store, join methods)

### 5. [ ] Routes
   - Edit `routes/web.php` (+ POST feeds store, POST join)

### 6. [ ] Views
   - Major edit `resources/views/User2026/feeds.blade.php` (+ create modal, meets display, JS)

### 7. [ ] Test
   - Manual test: create meets → verify feed → ikut/like/comment

**Next Step: 1. Run migrations → 2. Models**


