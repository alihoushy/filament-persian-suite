<?php

use Alihoushy\FilamentPersianSuite\Support\PersianNumberFormatter;

test('can format numbers to persian digits', function () {
    expect(PersianNumberFormatter::format(12345))->toBe('۱۲۳۴۵')
        ->and(PersianNumberFormatter::format('12345'))->toBe('۱۲۳۴۵')
        ->and(PersianNumberFormatter::format(0))->toBe('۰')
        ->and(PersianNumberFormatter::format(9876543210))->toBe('۹۸۷۶۵۴۳۲۱۰');
});

test('can format decimal numbers', function () {
    expect(PersianNumberFormatter::format(123.45))->toBe('۱۲۳.۴۵')
        ->and(PersianNumberFormatter::format('123.45'))->toBe('۱۲۳.۴۵');
});

test('can format numbers with thousand separators', function () {
    $formatted = PersianNumberFormatter::formatNumber(1234567, 0);
    
    expect($formatted)->toContain('۱۲۳۴۵۶۷')
        ->and($formatted)->toContain('،');
});

test('can format numbers with decimals', function () {
    $formatted = PersianNumberFormatter::formatNumber(1234.56, 2);
    
    expect($formatted)->toContain('۱۲۳۴')
        ->and($formatted)->toContain('۵۶');
});

test('preserves non-numeric characters', function () {
    expect(PersianNumberFormatter::format('123-456'))->toBe('۱۲۳-۴۵۶')
        ->and(PersianNumberFormatter::format('abc123def'))->toBe('abc۱۲۳def');
});

