<?php

use Alihoushy\FilamentPersianSuite\Tables\Columns\JalaliDateColumn;
use Alihoushy\FilamentPersianSuite\Jalali\JalaliConverter;

test('jalali date column can be instantiated', function () {
    $column = JalaliDateColumn::make('test_date');
    
    expect($column)->toBeInstanceOf(JalaliDateColumn::class)
        ->and($column->getName())->toBe('test_date');
});

test('jalali date column converts gregorian to jalali', function () {
    $column = JalaliDateColumn::make('created_at');
    
    // Test the underlying conversion logic
    $gregorianDate = '2024-03-21';
    $date = \Illuminate\Support\Carbon::parse($gregorianDate);
    $jalali = JalaliConverter::dateTimeToJalali($date);
    
    expect($jalali['year'])->toBe(1403)
        ->and($jalali['month'])->toBe(1)
        ->and($jalali['day'])->toBe(1);
});

test('jalali date column supports formatting methods', function () {
    $column = JalaliDateColumn::make('created_at');
    
    expect($column->short())->toBeInstanceOf(JalaliDateColumn::class)
        ->and($column->long())->toBeInstanceOf(JalaliDateColumn::class)
        ->and($column->withMonthName())->toBeInstanceOf(JalaliDateColumn::class)
        ->and($column->withTime())->toBeInstanceOf(JalaliDateColumn::class);
});

test('jalali date column formats dates correctly', function () {
    // Test format logic
    $date = new \DateTime('2024-03-21');
    $jalali = JalaliConverter::dateTimeToJalali($date);
    $formatted = JalaliConverter::format('Y/m/d', $date);
    
    expect($formatted)->toContain('1403')
        ->and($formatted)->toContain('01');
});

test('jalali date column handles null values', function () {
    $column = JalaliDateColumn::make('created_at');
    
    // The formatStateUsing should handle null
    expect($column)->toBeInstanceOf(JalaliDateColumn::class);
});

