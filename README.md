# Filament Persian Suite

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

A comprehensive Persian localization package for **FilamentPHP v4** and **Laravel LTS**, featuring in-house Jalali (Persian) date conversion without external dependencies.

## Features

- ✅ **In-House Jalali Date Conversion** - No external PHP dependencies for date conversion
- ✅ **Jalali Date Picker** - Custom form component with Alpine.js integration
- ✅ **Jalali Date Column** - Table column for displaying Jalali dates
- ✅ **Jalali Date Range Filter** - Filter records by Jalali date range
- ✅ **Persian Number Formatting** - Automatic conversion of English to Persian digits
- ✅ **Vazirmatn Font Integration** - Beautiful Persian font with RTL support
- ✅ **Tailwind v4 Compatible** - Modern CSS with dark mode support
- ✅ **Fully Tested** - Comprehensive Pest test coverage

## Requirements

- PHP 8.3+
- Laravel 11.0+ (LTS)
- Filament 4.0+

## Installation

Install the package via Composer:

```bash
composer require alihoushy/filament-persian-suite
```

Publish the assets:

```bash
php artisan vendor:publish --tag=filament-persian-suite-assets
```

## Usage

### Register the Plugin

In your Filament panel configuration file (e.g., `config/filament.php` or in your Panel provider):

```php
use Alihoushy\FilamentPersianSuite\FilamentPersianSuitePlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            FilamentPersianSuitePlugin::make(),
        ]);
}
```

### Jalali Date Picker

Use the Jalali date picker in your forms:

```php
use Alihoushy\FilamentPersianSuite\Forms\Components\JalaliDatePicker;

JalaliDatePicker::make('birth_date')
    ->label('تاریخ تولد')
    ->required()
```

The component automatically converts Jalali dates to Gregorian for database storage.

### Jalali Date Column

Display Jalali dates in your tables:

```php
use Alihoushy\FilamentPersianSuite\Tables\Columns\JalaliDateColumn;

JalaliDateColumn::make('created_at')
    ->label('تاریخ ایجاد')
    ->format('Y/m/d')
    ->withMonthName() // Optional: Show month name
```

Available formatting methods:
- `short()` - Short format (Y/m/d)
- `long()` - Long format with day name
- `withMonthName()` - Include Persian month name
- `withTime()` - Include time

### Jalali Date Range Filter

Filter records by Jalali date range:

```php
use Alihoushy\FilamentPersianSuite\Tables\Filters\JalaliDateRangeFilter;

JalaliDateRangeFilter::make('created_at')
    ->label('بازه زمانی')
```

### Persian Number Formatting

Format numbers to Persian digits:

```php
use Alihoushy\FilamentPersianSuite\Support\PersianNumberFormatter;

$formatted = PersianNumberFormatter::format(12345); // Returns: ۱۲۳۴۵
```

Or use the trait in your columns:

```php
use Alihoushy\FilamentPersianSuite\Support\FormatsPersianNumbers;

class MyColumn extends TextColumn
{
    use FormatsPersianNumbers;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->formatStateUsing(fn ($state) => 
            $this->formatPersianNumbers($state)
        );
    }
}
```

## Architecture

### In-House Jalali Conversion

This package implements Jalali date conversion algorithms entirely in-house, without relying on external PHP libraries like `morilog/jalali`. The conversion logic is implemented in both PHP (server-side) and JavaScript (client-side) for seamless integration.

### Component Design

Following Filament v4 best practices, components use mutation and formatting methods rather than full rewrites:
- `formatStateUsing()` - For displaying Jalali dates from Gregorian database values
- `mutateStateUsing()` - For converting Jalali input to Gregorian for storage

## Testing

Run the test suite:

```bash
composer test
```

Or with Pest:

```bash
./vendor/bin/pest
```

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

---

# Filament Persian Suite (فارسی)

پکیج جامع بومی‌سازی فارسی برای **FilamentPHP v4** و **Laravel LTS** با پیاده‌سازی مستقل تبدیل تاریخ شمسی بدون وابستگی خارجی.

## ویژگی‌ها

- ✅ **تبدیل تاریخ شمسی مستقل** - بدون وابستگی به پکیج‌های خارجی PHP
- ✅ **انتخابگر تاریخ شمسی** - کامپوننت فرم با یکپارچه‌سازی Alpine.js
- ✅ **ستون تاریخ شمسی** - ستون جدول برای نمایش تاریخ‌های شمسی
- ✅ **فیلتر بازه تاریخ شمسی** - فیلتر رکوردها بر اساس بازه تاریخ شمسی
- ✅ **فرمت‌دهی اعداد فارسی** - تبدیل خودکار اعداد انگلیسی به فارسی
- ✅ **یکپارچه‌سازی فونت Vazirmatn** - فونت فارسی زیبا با پشتیبانی RTL
- ✅ **سازگار با Tailwind v4** - CSS مدرن با پشتیبانی حالت تاریک
- ✅ **تست‌شده کامل** - پوشش تست جامع با Pest

## نیازمندی‌ها

- PHP 8.3+
- Laravel 11.0+ (LTS)
- Filament 4.0+

## نصب

پکیج را از طریق Composer نصب کنید:

```bash
composer require alihoushy/filament-persian-suite
```

فایل‌های استاتیک را منتشر کنید:

```bash
php artisan vendor:publish --tag=filament-persian-suite-assets
```

## استفاده

### ثبت پلاگین

در فایل پیکربندی پنل Filament خود (مثلاً `config/filament.php` یا در Panel provider):

```php
use Alihoushy\FilamentPersianSuite\FilamentPersianSuitePlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            FilamentPersianSuitePlugin::make(),
        ]);
}
```

### انتخابگر تاریخ شمسی

از انتخابگر تاریخ شمسی در فرم‌های خود استفاده کنید:

```php
use Alihoushy\FilamentPersianSuite\Forms\Components\JalaliDatePicker;

JalaliDatePicker::make('birth_date')
    ->label('تاریخ تولد')
    ->required()
```

کامپوننت به طور خودکار تاریخ‌های شمسی را به میلادی برای ذخیره در دیتابیس تبدیل می‌کند.

### ستون تاریخ شمسی

تاریخ‌های شمسی را در جداول خود نمایش دهید:

```php
use Alihoushy\FilamentPersianSuite\Tables\Columns\JalaliDateColumn;

JalaliDateColumn::make('created_at')
    ->label('تاریخ ایجاد')
    ->format('Y/m/d')
    ->withMonthName() // اختیاری: نمایش نام ماه
```

متدهای فرمت‌دهی موجود:
- `short()` - فرمت کوتاه (Y/m/d)
- `long()` - فرمت بلند با نام روز
- `withMonthName()` - شامل نام ماه فارسی
- `withTime()` - شامل زمان

### فیلتر بازه تاریخ شمسی

رکوردها را بر اساس بازه تاریخ شمسی فیلتر کنید:

```php
use Alihoushy\FilamentPersianSuite\Tables\Filters\JalaliDateRangeFilter;

JalaliDateRangeFilter::make('created_at')
    ->label('بازه زمانی')
```

### فرمت‌دهی اعداد فارسی

اعداد را به رقم‌های فارسی تبدیل کنید:

```php
use Alihoushy\FilamentPersianSuite\Support\PersianNumberFormatter;

$formatted = PersianNumberFormatter::format(12345); // خروجی: ۱۲۳۴۵
```

یا از trait در ستون‌های خود استفاده کنید:

```php
use Alihoushy\FilamentPersianSuite\Support\FormatsPersianNumbers;

class MyColumn extends TextColumn
{
    use FormatsPersianNumbers;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->formatStateUsing(fn ($state) => 
            $this->formatPersianNumbers($state)
        );
    }
}
```

## معماری

### تبدیل تاریخ شمسی مستقل

این پکیج الگوریتم‌های تبدیل تاریخ شمسی را به طور کامل به صورت داخلی پیاده‌سازی می‌کند، بدون وابستگی به کتابخانه‌های خارجی PHP مانند `morilog/jalali`. منطق تبدیل هم در PHP (سمت سرور) و هم در JavaScript (سمت کلاینت) پیاده‌سازی شده است.

### طراحی کامپوننت

با پیروی از بهترین شیوه‌های Filament v4، کامپوننت‌ها از متدهای mutation و formatting استفاده می‌کنند به جای بازنویسی کامل:
- `formatStateUsing()` - برای نمایش تاریخ‌های شمسی از مقادیر میلادی دیتابیس
- `mutateStateUsing()` - برای تبدیل ورودی شمسی به میلادی برای ذخیره‌سازی

## تست

اجرای مجموعه تست:

```bash
composer test
```

یا با Pest:

```bash
./vendor/bin/pest
```

## مشارکت

مشارکت‌ها خوش‌آمد هستند! لطفاً Pull Request ارسال کنید.

## مجوز

مجوز MIT (MIT). برای اطلاعات بیشتر به [فایل مجوز](LICENSE) مراجعه کنید.

