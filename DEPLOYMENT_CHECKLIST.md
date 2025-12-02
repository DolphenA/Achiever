# 🚀 DEPLOYMENT SECURITY CHECKLIST

## ⚠️ CRITICAL - DO BEFORE GOING LIVE:

### 1. Remove Debug Routes
**File:** `routes/web.php`
- [ ] Delete lines 22-42 (all `/debug/*` routes)
- [ ] Remove `debug-user.blade.php` view file

### 2. Update .env File
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_PASSWORD=YOUR_STRONG_PASSWORD_HERE

SESSION_SECURE_COOKIE=true
SESSION_DOMAIN=yourdomain.com
SESSION_PATH=/
```

### 3. Generate New Application Key
```bash
php artisan key:generate
```

### 4. Set Strong Database Password
- [ ] Create strong password for MySQL user
- [ ] Update DB_PASSWORD in .env
- [ ] Test database connection

### 5. Configure Session for Production
```env
SESSION_DRIVER=database  # or redis for better performance
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true  # HTTPS only
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=strict  # or lax
```

### 6. File Permissions (Linux/Unix servers)
```bash
chmod -R 755 /path/to/achiever
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data /path/to/achiever
```

### 7. Configure .htaccess (if using Apache)
- [ ] Ensure `.htaccess` in public/ redirects to index.php
- [ ] Block access to .env file
- [ ] Set proper document root to `/public`

### 8. Enable HTTPS/SSL
- [ ] Install SSL certificate (Let's Encrypt recommended)
- [ ] Force HTTPS in your web server config
- [ ] Update APP_URL to https://

### 9. Optimize for Production
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --optimize-autoloader --no-dev
```

### 10. Security Headers
Add to `.htaccess` or web server config:
```apache
Header set X-Content-Type-Options "nosniff"
Header set X-Frame-Options "SAMEORIGIN"
Header set X-XSS-Protection "1; mode=block"
Header set Referrer-Policy "strict-origin-when-cross-origin"
```

## 📁 FILES TO NEVER UPLOAD:
- ✅ `.env` (already in .gitignore)
- ✅ `/vendor` (composer install on server)
- ✅ `/node_modules` (npm install on server)
- ✅ `/storage/*.key`
- ✅ Database backups

## 🔐 ADDITIONAL SECURITY:

### Rate Limiting
Add to routes that accept forms:
```php
Route::post('/login')->middleware('throttle:5,1');  // 5 attempts per minute
Route::post('/signup')->middleware('throttle:3,1');
```

### CSRF Protection
- ✅ Already implemented (using @csrf_token)

### SQL Injection Protection
- ✅ Using Eloquent ORM (safe from SQL injection)

### File Upload Security
- ✅ Limited to specific file types (.pdf, .doc, .docx)
- ✅ 5GB size limit configured
- [ ] Consider adding virus scanning on upload

### Password Security
- ✅ Using bcrypt hashing
- ✅ Minimum 6 characters (consider increasing to 8+)

## 🌐 GOOGLE CLOUD SPECIFIC:

### 1. Use Cloud SQL
- Replace 127.0.0.1 with Cloud SQL instance connection

### 2. Use Cloud Storage
```env
FILESYSTEM_DISK=gcs
```

### 3. Environment Variables
- Set via App Engine app.yaml or Cloud Run
- Never commit .env to repository

### 4. Enable Cloud Armor
- DDoS protection
- WAF rules

## ✅ POST-DEPLOYMENT:

- [ ] Test all functionality on production
- [ ] Monitor error logs: `storage/logs/laravel.log`
- [ ] Set up automated backups (database + storage/app)
- [ ] Configure monitoring (uptime, errors)
- [ ] Test SSL certificate
- [ ] Verify HTTPS redirect works
- [ ] Test session persistence
- [ ] Test file uploads
- [ ] Test authentication
- [ ] Check page load speeds

## 🚨 EMERGENCY CONTACTS:
- Database backups location: ________________
- Server admin: ________________
- Emergency shutdown command: `php artisan down`
