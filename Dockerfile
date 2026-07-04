# استخدام نسخة PHP الرسمية المجهزة بسيرفر Apache الشهير لتشغيل المواقع
FROM php:8.2-apache

# تثبيت الإضافات اللازمة للاتصال بقواعد بيانات PostgreSQL (التي تستخدمها Supabase)
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# نسخ كافة ملفات مشروعك (متجر مسك) إلى داخل السيرفر
COPY . /var/www/html/

# تفعيل ميزة إعادة توجيه الروابط في سيرفر Apache
RUN a2enmod rewrite

# تحديد منفذ التشغيل الافتراضي لمنصة ريندر
EXPOSE 80
