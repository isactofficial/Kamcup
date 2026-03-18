# Fix RouteNotFoundException for admin.userpages.feeds ✅

## Plan Summary
Update sidebar Feeds links to use existing `admin.feeds.index` route instead of undefined `admin.userpages.feeds`.

## Steps
- [x] 1. Update `resources/views/layouts/_admin-sidebar.blade.php`
- [x] 2. Update `resources/views/layouts/admin.blade.php` 
- [x] 3. Clear route/config cache: `php artisan route:clear && php artisan config:clear` (manual if needed)
- [x] 4. Test `/admin/dashboard` loads without error
- [x] 5. Verify Feeds link goes to `/admin/feeds`
- [x] 6. Complete task ✅
