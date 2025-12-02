<?php

use Alihoushy\FilamentPersianSuite\Jalali\JalaliConverter;
use InvalidArgumentException;

test('can convert gregorian to jalali', function () {
    // 2024-03-21 (Nowruz) should be 1403/01/01
    $jalali = JalaliConverter::toJalali(2024, 3, 21);
    
    expect($jalali)->toBeArray()
        ->and($jalali['year'])->toBe(1403)
        ->and($jalali['month'])->toBe(1)
        ->and($jalali['day'])->toBe(1);
});

test('can convert jalali to gregorian', function () {
    // 1403/01/01 (Nowruz) should be 2024-03-21
    $gregorian = JalaliConverter::toGregorian(1403, 1, 1);
    
    expect($gregorian)->toBeArray()
        ->and($gregorian['year'])->toBe(2024)
        ->and($gregorian['month'])->toBe(3)
        ->and($gregorian['day'])->toBe(21);
});

test('conversion is reversible', function () {
    $gregorianYear = 2024;
    $gregorianMonth = 6;
    $gregorianDay = 15;
    
    $jalali = JalaliConverter::toJalali($gregorianYear, $gregorianMonth, $gregorianDay);
    $backToGregorian = JalaliConverter::toGregorian($jalali['year'], $jalali['month'], $jalali['day']);
    
    expect($backToGregorian['year'])->toBe($gregorianYear)
        ->and($backToGregorian['month'])->toBe($gregorianMonth)
        ->and($backToGregorian['day'])->toBe($gregorianDay);
});

test('can validate jalali date', function () {
    expect(JalaliConverter::isValidJalali(1403, 1, 1))->toBeTrue()
        ->and(JalaliConverter::isValidJalali(1403, 12, 29))->toBeTrue()
        ->and(JalaliConverter::isValidJalali(1403, 12, 30))->toBeTrue() // Leap year
        ->and(JalaliConverter::isValidJalali(1403, 12, 31))->toBeFalse()
        ->and(JalaliConverter::isValidJalali(1403, 13, 1))->toBeFalse()
        ->and(JalaliConverter::isValidJalali(1403, 0, 1))->toBeFalse()
        ->and(JalaliConverter::isValidJalali(1403, 1, 0))->toBeFalse();
});

test('throws exception for invalid gregorian date', function () {
    expect(fn() => JalaliConverter::toJalali(2024, 13, 1))
        ->toThrow(InvalidArgumentException::class);
});

test('throws exception for invalid jalali date', function () {
    expect(fn() => JalaliConverter::toGregorian(1403, 13, 1))
        ->toThrow(InvalidArgumentException::class);
});

test('can format jalali date', function () {
    $date = new \DateTime('2024-03-21');
    $formatted = JalaliConverter::format('Y/m/d', $date);
    
    expect($formatted)->toContain('1403')
        ->and($formatted)->toContain('01')
        ->and($formatted)->toContain('01');
});

test('can format jalali date with month name', function () {
    $date = new \DateTime('2024-03-21');
    $formatted = JalaliConverter::format('Y M d', $date);
    
    expect($formatted)->toContain('1403')
        ->and($formatted)->toContain('فروردین');
});

test('can convert datetime to jalali', function () {
    $date = new \DateTime('2024-03-21');
    $jalali = JalaliConverter::dateTimeToJalali($date);
    
    expect($jalali)->toBeArray()
        ->and($jalali['year'])->toBe(1403)
        ->and($jalali['month'])->toBe(1)
        ->and($jalali['day'])->toBe(1);
});

test('can convert jalali to datetime', function () {
    $dateTime = JalaliConverter::jalaliToDateTime(1403, 1, 1);
    
    expect($dateTime)->toBeInstanceOf(\DateTime::class)
        ->and($dateTime->format('Y-m-d'))->toBe('2024-03-21');
});

test('handles leap years correctly', function () {
    // 1403 is a leap year (Esfand has 30 days)
    expect(JalaliConverter::isValidJalali(1403, 12, 30))->toBeTrue();
    
    // 1402 is not a leap year (Esfand has 29 days)
    expect(JalaliConverter::isValidJalali(1402, 12, 29))->toBeTrue()
        ->and(JalaliConverter::isValidJalali(1402, 12, 30))->toBeFalse();
});

test('handles edge cases for month boundaries', function () {
    // First day of year
    $jalali = JalaliConverter::toJalali(2024, 3, 21);
    expect($jalali['month'])->toBe(1)
        ->and($jalali['day'])->toBe(1);
    
    // Last day of year (check if it's valid)
    $lastDay = JalaliConverter::toJalali(2024, 3, 20);
    expect($lastDay['month'])->toBe(12);
});

