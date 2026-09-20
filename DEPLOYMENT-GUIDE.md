# 🚀 إرشادات نشر "سمارت سنتر" (Smart Center) على الإنترنت

## 📋 المتطلبات الأساسية
- **PHP 8.2** مع الإضافات: mbstring, openssl, pdo, tokenizer, xml, ctype, json, ckh
- **SQLite** (مُدمج في النسخة الافتراضية)
- **Composer** (لتثبيت الحزم)
- **مستودع Git** (اختياري، للتحديثات)

## 🎯 خيارات الاستضافة الموصى بها

| المزود | السعر | ملاحظات |
|-------|-------|----------|
| **Hosting (LiteSpeed/Apache)** | $3-10/شهر | أبسط خيار، PHP + SQLite |
| **VPS (DigitalOcean/Linode)** | $5-10/شهر | تحكم كامل |
| **Render.com** | مجاناً + مدفوع | دعم Laravel مباشرةً |
| **Railway.app** | مجاناً + مدفوع | نشر سريع من GitHub |
| **Vercel (للـ Frontend فقط)** | مجاناً | لا يدعم PHP |

## 🔧 خطوات النشر

### الطريقة 1: استخدام Git + SSH (موصى بها)

```bash
# 1. ربط المشروع بـ GitHub
git init
git add .
git commit -m "Ready for production deployment"
git branch -M main
git remote add origin https://github.com/YOUR-USERNAME/smart-center.git
git push -u origin main

# 2. الاستضافة على Render أو Railway
# - أنشئ حساب على https://render.com أو https://railway.app
# - أنشئ "New Web Service"
# - اربطه بـ GitHub репو
# - اضبط الإعدادات:
#   Build Command: composer install --optimize-autoloader && php artisan config:cache
#   Start Command: php artisan serve --host=0.0.0.0 --port=$PORT
```

### الطريقة 2: رفع يدوي على Shared Hosting

```bash
# 1. أنشئ ملف ZIP بدون الملفات الكبيرة
zip -r smart-center-deploy.zip . \
  --exclude='*.git/*' \
  --exclude='node_modules/*' \
  --exclude='vendor/*' \
  --exclude='.env' \
  --exclude='*.sqlite' \
  --exclude='storage/*.key'

# 2. ارفع الملف على الـ FTP
# 3. استخرج الملفات في المجلد الرئيسي
# 4. أنشئ قاعدة بيانات SQLite أو استخدم MySQL
```

### الطريقة 3: استخدام Docker (للمنصات المتقدمة)

```bash
# 1. أنشئ ملف docker-compose.yml
cat > docker-compose.yml << 'EOF'
version: '3.8'
services:
  app:
    image: php:8.2-apache
    ports:
      - "8000:80"
    volumes:
      - ./:/var/www/html
      - ./storage:/var/www/html/storage
    working_dir: /var/www/html
    environment:
      - APP_ENV=production
      - APP_KEY=your-generated-key
      - DB_CONNECTION=sqlite
      - DB_DATABASE=/var/www/html/database/database.sqlite
EOF

# 2. شغل الـ Docker
docker-compose up -d
```

## 🔐 إعدادات البيئة (Environment Variables)

بعد النشر، أنشئ ملف `.env` على الخادم:

```env
APP_NAME="سمارت سنتر"
APP_ENV=production
APP_KEY=base64:هنا-المفتاح-الذي-تولدته
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite

SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

## 🗝 توليد المفتاح السري (App Key)

```bash
php artisan key:generate
# انسخ القيمة اللي طلعتها وألصقها في .env
```

## 📁 إعداد الـ Storage Permissions

```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

## 🛡️ الأمان بعد النشر

1. **أغلق الـ Debug Mode:**
   ```env
   APP_DEBUG=false
   ```

2. **استخدم HTTPS:**
   - فعل SSL عبر Let's Encrypt
   - أضف في `.env`:
     ```env
     FORCE_HTTPS=true
     ```

3. **حماية قاعدة البيانات:**
   - استخدم مسار مطلق لملف SQLite
   - لا توضع قاعدة البيانات في المجلد العلني

4. **إعداد Cron Jobs (للـ Scheduled Tasks):**
   ```bash
   * * * * * cd /path/to/center && php artisan schedule:run >> /dev/null 2>&1
   ```

## 📱 بعد النشر

- **URL:** https://your-domain.com
- **Login:** admin@center.test / password
- **الميزات الكل كاشية:** قاعدة بيانات، مستخدمين، تقارير، مسح QR، إشعارات!

## 🆘 إذا واجهتك مشاكل

- افتح `storage/logs/laravel.log` لمراجعة الأخطاء
- تأكد من أن `storage/` و `bootstrap/cache/` لها صلاحيات كتابة
- راجع `php artisan config:clear` إذا غيرت الإعدادات

---

# 🚀 Smart Center Production Deployment Guide

## Requirements
- PHP 8.2 with extensions: mbstring, openssl, pdo, tokenizer, xml, ctype, json, cURL
- SQLite or MySQL database
- Composer
- (Optional) Git for version control

## Recommended Hosting Providers

| Provider | Price | Notes |
|----------|-------|-------|
| **Shared Hosting (A2 Hosting/Hostinger)** | $3-10/month | Cheapest option, PHP + SQLite |
| **VPS (DigitalOcean/Linode)** | $5-10/month | Full control |
| **Render.com** | Free tier + paid | Laravel-optimized |
| **Railway.app** | Free tier + paid | Quick deploy from Git |

## Deployment Steps

### Option 1: Git + Render (Recommended)

1. Push code to GitHub/GitLab
2. Create new Web Service on [render.com](https://render.com)
3. Set Build Command:
   ```
   composer install --optimize-autoloader && php artisan config:cache
   ```
4. Set Start Command:
   ```
   php artisan serve --host=0.0.0.0 --port=$PORT
   ```

### Option 2: Manual Upload (Shared Hosting)

1. Create production-ready ZIP:
   ```bash
   zip -r deploy.zip . \
     --exclude='*.git/*' \
     --exclude='node_modules/*' \
     --exclude='vendor/*' \
     --exclude='.env' \
     --exclude='*.sqlite*' \
     --exclude='storage/*.key'
   ```

2. Upload via FTP and extract
3. Run `composer install --optimize-autoloader` on server

### Option 3: Docker

```bash
docker build -t smart-center .
docker run -p 8000:8000 smart-center
```

## Environment Configuration (.env)

```env
APP_NAME="Smart Center"
APP_ENV=production
APP_KEY=your-app-key-here
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite

SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

## Essential Post-Deployment Commands

```bash
# Generate app key
php artisan key:generate

# Set storage permissions
chmod -R 775 storage bootstrap/cache

# Link storage (if not already linked)
php artisan storage:link

# Run migrations (if fresh deploy)
php artisan migrate --force
```

## Security Checklist

✅ `APP_DEBUG=false`
✅ HTTPS enforced
✅ Database file outside public/
✅ Regular backups enabled
✅ Cron jobs configured for scheduled tasks

---

© 2024 Smart Center - Lesson Center Management System