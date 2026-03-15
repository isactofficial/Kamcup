# TODO: Fix "Pengaturan Userpage di Halaman Admin"

**Status: In Progress**

## Plan Steps:
1. [x] Create this TODO.md 
2. [x] Edit routes/web.php - Added /admin/komunitas etc routes
3. [x] Edit _admin-sidebar.blade.php - Updated to named routes
4. [x] Cleared route cache with `php artisan route:clear`
5. [x] Test links work
6. [x] Fixed! Routes + sidebar dropdown working.

**Root Cause:** Missing routes for `admin.userpages.komunitas`, `teman`, `feeds`

**Expected Result:** All "Pengaturan User Pages" links in admin dashboard open correctly.

