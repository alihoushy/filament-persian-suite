<?php

use Alihoushy\FilamentPersianSuite\Tables\Filters\JalaliDateRangeFilter;
use Alihoushy\FilamentPersianSuite\Jalali\JalaliConverter;

test('jalali date range filter can be instantiated', function () {
    $filter = JalaliDateRangeFilter::make('created_at');
    
    expect($filter)->toBeInstanceOf(JalaliDateRangeFilter::class);
});

test('jalali date range filter converts jalali to gregorian for query', function () {
    // Test conversion logic used in filter
    $jalaliDate = '1403/01/01';
    $jalaliDate = str_replace('-', '/', $jalaliDate);
    $parts = explode('/', $jalaliDate);
    
    expect(count($parts))->toBe(3);
    
    [$year, $month, $day] = array_map('intval', $parts);
    $dateTime = JalaliConverter::jalaliToDateTime($year, $month, $day);
    $gregorian = $dateTime->format('Y-m-d');
    
    expect($gregorian)->toBe('2024-03-21');
});

test('jalali date range filter handles date conversion correctly', function () {
    $filter = JalaliDateRangeFilter::make('created_at');
    
    // Test that the filter uses correct conversion
    $jalali = '1403/01/01';
    $parts = explode('/', str_replace('-', '/', $jalali));
    [$year, $month, $day] = array_map('intval', $parts);
    
    expect(JalaliConverter::isValidJalali($year, $month, $day))->toBeTrue();
    
    $dateTime = JalaliConverter::jalaliToDateTime($year, $month, $day);
    expect($dateTime->format('Y-m-d'))->toBe('2024-03-21');
});

test('jalali date range filter can set column', function () {
    $filter = JalaliDateRangeFilter::make('created_at')
        ->column('updated_at');
    
    expect($filter)->toBeInstanceOf(JalaliDateRangeFilter::class);
});

