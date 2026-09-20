# سنتر الدروس — نظام إدارة سنتر الدروس الخصوصية

نظام متكامل لإدارة سنتر دروس خصوصية باللغة العربية (RTL) — يعمل على الكمبيوتر والموبايل.

> **الحالة:** جاهز للتشغيل محلياً عبر Docker أو مباشرة بـ SQLite — بيانات تجريبية + حسابات جاهزة.

---

## 1) المميزات المنفذة

- **الطلاب:** كود تلقائي، بحث بالاسم/الكود/الهاتف، فلترة بالصف والحالة، صفحة تفصيلية (أولياء الأمور، المجموعات، الحضور، النتائج، المدفوعات، المتأخرات)
- **أولياء الأمور:** ربط متعدد (ولي أمر ↔ عدة طلاب)، هاتف أساسي/إضافي، صلة قرابة، عنوان
- **المدرسون:** تخصص، نوع الأجر (بالحصة/نسبة)، حالة، عرض مجموعاتهم وعدد الطلاب
- **المواد:** إدارة المواد مع المرحلة والحالة
- **المجموعات:** سعة قصوى، أيام، وقت، قاعة، رسوم شهرية، نسبة امتلاء، منع التجاوز إلا للمدير
- **التسجيل في مجموعة:** منع التكرار لنفس الطالب/المجموعة، تجاوز السعة للمدير فقط
- **الحصص (class_sessions):** إدارة يدوية، حالات (تمت/أُلغيت/مؤجلة/تعويضية)
- **الحضور:** تسجيل جماعي (حاضر/غائب/متأخر/بعذر)، تقارير حضور الطالب والمجموعة والمتغيبون بكثرة
- **الاختبارات والنتائج:** درجة نهائية، نسبة تلقائية، تقدير، ترتيب، متوسط/أعلى/أقل، مقارنة أداء الطالب
- **المدفوعات:** شهري/جزئي/مقدم/خصم، منع التكرار مع تحذير، إلغاء مع سبب (SoftDelete)، حالات (مدفوع/جزئي/لم يدفع/متأخر/ملغى)، إيصال طباعة، رقم إيصال فريد، `remaining` محسوب تلقائياً
- **المصروفات:** تصنيفات (إيجار/كهرباء/مياه/رواتب/أدوات/تسويق/صيانة/أخرى)، مرفق صورة إيصال
- **لوحة التحكم:** طلاب نشطون، مجموعات، مدرسون، إيراد الشهر، متأخرات، مصروفات، صافي، جدد، غائبو اليوم، اختبارات قادمة، مجموعات مكتملة، أكثر غياباً، متأخرون — كل رقم قابل للنقر
- **التقارير:** إيرادات، مصروفات، صافي ربح، حضور، طلاب — مع فلاتر تاريخ/مجموعة وطباعة/PDF
- **المستخدمون والصلاحيات:** 4 أدوار (مدير/استقبال/محاسب/مدرس) مع قيود مناسبة — المدرس يرى مجموعاته فقط
- **الأمان:** Validation عربية، منع أرقام سالبة، منع درجة > النهائية، SoftDelete، Audit Log، Pagination، تأكيد قبل الحذف، توقيت القاهرة، عملة جنيه مصري
- **الواجهة:** عربية RTL، Tailwind CDN + خط Cairo، ألوان مميزة للحالات، Responsive
- **الإشعارات:** نظام إشعارات داخلي (متأخرات/غياب متكرر/اكتمال مجموعة) — جاهز للتوسع لواتساب/SMS
- **التصدير:** طباعة و PDF عبر dompdf — Excel جاهز للإضافة عبر maatwebsite/excel
- **الاختبارات:** 11 اختبار تلقائي يغطي الصلاحيات والسعة والمتبقي والحضور والتقارير

## 2) المتطلبات

- **Docker Desktop** (موصى به) أو **PHP 8.2+** + Composer + SQLite/MySQL
- Git (اختياري)

## 3) التشغيل السريع (Docker — موصى به)

```bash
# 1) فك الضغط أو clone
cd center

# 2) إنشاء ملف البيئة
copy .env.example .env   # ويندوز
# أو: cp .env.example .env

# 3) شغّل الحاويات
docker compose up -d --build

# 4) داخل حاوية التطبيق: تثبيت + مفاتيح + هجرة + بذر
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed

# 5) افتح المتصفح
# http://localhost  (عبر nginx)
# أو http://localhost:8000 (مباشر)
```

## 4) التشغيل المحلي بدون Docker (SQLite — أسهل لغير المتخصص)

```bash
cd center

# تأكد من تثبيت PHP 8.2 (مرفق عبر winget) و Composer
php -v
composer --version

# البيئة — SQLite افتراضي
copy .env.example .env
# عدّل .env:
# DB_CONNECTION=sqlite
# DB_DATABASE=C:/Users/YourName/Desktop/center/database/database.sqlite

php artisan key:generate
php artisan migrate --seed
php artisan serve
# افتح http://127.0.0.1:8000
```

> ملاحظة: المشروع الحالي مضبوط افتراضياً على `DB_CONNECTION=sqlite` ويعمل فوراً بدون MySQL.

## 5) حسابات الدخول التجريبية

| الدور | البريد | كلمة المرور | الصلاحيات |
|-------|--------|-------------|-----------|
| **المدير** | `admin@center.test` | `password` | كل شيء + نسخ احتياطي |
| **الاستقبال** | `reception@center.test` | `password` | طلاب/أولياء/تسجيل/حضور/مدفوعات |
| **المحاسب** | `accountant@center.test` | `password` | مدفوعات/مصروفات/تقارير مالية |
| **المدرس** | `teacher@center.test` | `password` | مجموعاته فقط + حضور/اختبارات |

**غيّر كلمة المرور فوراً:**

1. سجل دخول كـ مدير → **المستخدمون** → تعديل → أدخل كلمة مرور جديدة → حفظ
2. أو عبر سطر الأوامر:
```bash
php artisan tinker
>>> $u = App\Models\User::where('email','admin@center.test')->first();
>>> $u->password = Hash::make('كلمة-جديدة-قوية');
>>> $u->save();
```

## 6) البيانات التجريبية المزروعة

- 4 مستخدمين (أدوار مختلفة)
- 4 مواد (رياضيات، عربي، إنجليزي، علوم)
- 3 مدرسين
- 8 أولياء أمور
- 15 طالباً (أكواد STU0001…)
- 4 مجموعات (واحدة مكتملة للاختبار)
- تسجيلات، حصص (class_sessions)، حضور، اختباران بنتائج، 33 دفعة (كاملة/جزئية/متأخرة)، 8 مصروفات

إعادة الزرع:
```bash
php artisan migrate:fresh --seed
```

## 7) النسخ الاحتياطي

### SQLite (الوضع الافتراضي)
```bash
# نسخ ملف قاعدة البيانات
copy database\database.sqlite database\backup\database_%date:~10,4%-%date:~4,2%-%date:~7,2%.sqlite

# أو عبر artisan
php artisan tinker --execute="copy('database/database.sqlite','database/backup_'.date('Y-m-d_His').'.sqlite'); echo 'تم';"
```

### MySQL (Docker)
```bash
docker compose exec db mysqldump -u center -psecret center > backup_2026-09-11.sql
# الاستعادة
docker compose exec -T db mysql -u center -psecret center < backup_2026-09-11.sql
# أو نسخ مجلد dbdata
docker compose exec db sh -c 'mysqldump -u root -psecret --all-databases' > full_backup.sql
```

**جدولة يومية (ويندوز Task Scheduler):**
```bat
@echo off
set DST=C:\Backups\center_%date:~10,4%-%date:~4,2%-%date:~7,2%.sqlite
copy C:\Users\YourName\Desktop\center\database\database.sqlite %DST%
```

## 8) النشر على استضافة

### استضافة مشتركة (cPanel)
1. ارفع الملفات (بدون `node_modules`).
2. أنشئ قاعدة MySQL وعدّل `.env` (DB_HOST/DB_DATABASE/DB_USERNAME/DB_PASSWORD).
3. شغّل عبر Terminal أو SSH:
```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan config:cache && php artisan route:cache && php artisan view:cache
```
4. وجّه الـ DocumentRoot إلى `public/`.

### VPS / Docker
```bash
docker compose -f docker-compose.yml up -d --build
docker compose exec app php artisan migrate --seed --force
```

## 9) الإعدادات المهمة

- **المنطقة الزمنية:** `config/app.php` → `'timezone' => 'Africa/Cairo'` (مضبوط)
- **اللغة:** `'locale' => 'ar'`, `'faker_locale' => 'ar_EG'`
- **العملة:** جنيه مصري — تنسيق `number_format` في كل التقارير
- **التواريخ:** `Y/m/d` و `now('Africa/Cairo')` في كل مكان
- **الصور:** تُخزن في `storage/app/public` — شغّل `php artisan storage:link`

## 10) الاختبارات

```bash
php artisan test
php artisan test --filter=CenterTest
```

يغطي: تسجيل دخول، كود طالب، منع تكرار التسجيل، منع تجاوز السعة، تجاوز المدير، حساب المتبقي، حضور، تقارير، إيصال.

## 11) هيكل المشروع

```
center/
├── app/Models/ (Student, ParentModel, Teacher, Subject, Group, Enrollment, Session, Attendance, Exam, ExamResult, Payment, Expense, User...)
├── app/Http/Controllers/ (Dashboard, Student, Group, Payment, Report...)
├── app/Http/Requests/ (Validation عربية)
├── database/migrations/ (18 migration)
├── database/seeders/DatabaseSeeder.php
├── resources/views/ (layouts/app.blade.php RTL + 40+ صفحة)
├── routes/web.php
├── docker-compose.yml / Dockerfile / docker/
├── .env.example
└── tests/Feature/CenterTest.php
```

## 12) المميزات المؤجلة (المرحلة الثانية)

- تصدير Excel عبر `maatwebsite/excel` (الهيكل جاهز)
- إشعارات واتساب/SMS (الهيكل الداخلي جاهز)
- دفع إلكتروني
- تطبيق موبايل (API)
- باركود/QR للطلاب
- تقارير متقدمة (مقارنة فترات، رسوم بيانية)
- نظام حضور بالبصمة/QR
- أرشفة سنوات دراسية

## 13) استكشاف الأخطاء

| المشكلة | الحل |
|---------|------|
| `could not find driver` | فعّل `extension=pdo_sqlite` و `sqlite3` في `php.ini` |
| `419 Page Expired` | تأكد من `APP_KEY` و `php artisan key:generate` |
| `table already exists` | `php artisan migrate:fresh --seed` |
| لا يظهر التنسيق | تأكد من اتصال الإنترنت (Tailwind CDN) أو شغّل `npm run build` |
| المدير لا يتجاوز السعة | سجل دخول بـ `admin@center.test` فقط |

## 14) الدعم

- افتح `storage/logs/laravel.log` للأخطاء
- شغّل `php artisan config:clear && php artisan cache:clear` عند تغيير `.env`

---

**تم البناء بـ Laravel 12 + PHP 8.2 + Tailwind CSS + SQLite/MySQL + Docker**

> غيّر كلمات مرور الحسابات التجريبية قبل النشر الإنتاجي.

