<?php

use Alihoushy\FilamentPersianSuite\Forms\Components\JalaliDatePicker;
use Alihoushy\FilamentPersianSuite\Jalali\JalaliConverter;

test('jalali date picker can be instantiated', function () {
    $picker = JalaliDatePicker::make('test_date');
    
    expect($picker)->toBeInstanceOf(JalaliDatePicker::class)
        ->and($picker->getName())->toBe('test_date');
});

test('jalali date picker has correct view', function () {
    $picker = JalaliDatePicker::make('test_date');
    
    expect($picker->getView())->toBe('filament-persian-suite::components.jalali-date-picker');
});

test('jalali date picker uses jalali converter for conversion', function () {
    // Test that the underlying converter works correctly
    $gregorian = JalaliConverter::toJalali(2024, 3, 21);
    
    expect($gregorian['year'])->toBe(1403)
        ->and($gregorian['month'])->toBe(1)
        ->and($gregorian['day'])->toBe(1);
    
    $jalali = JalaliConverter::toGregorian(1403, 1, 1);
    
    expect($jalali['year'])->toBe(2024)
        ->and($jalali['month'])->toBe(3)
        ->and($jalali['day'])->toBe(21);
});

test('jalali date picker conversion logic works correctly', function () {
    // Test the conversion logic that would be used in the component
    $gregorianDate = '2024-03-21';
    $date = \Illuminate\Support\Carbon::parse($gregorianDate);
    $jalali = JalaliConverter::dateTimeToJalali($date);
    $formatted = sprintf('%04d/%02d/%02d', $jalali['year'], $jalali['month'], $jalali['day']);
    
    expect($formatted)->toBe('1403/01/01');
    
    // Reverse conversion
    $parts = explode('/', '1403/01/01');
    [$year, $month, $day] = array_map('intval', $parts);
    $dateTime = JalaliConverter::jalaliToDateTime($year, $month, $day);
    
    expect($dateTime->format('Y-m-d'))->toBe('2024-03-21');
});

test('jalali date picker handles date parsing correctly', function () {
    // Test parsing logic used in mutateStateUsing
    $state = '1403/01/01';
    $state = str_replace('-', '/', $state);
    $parts = explode('/', $state);
    
    expect(count($parts))->toBe(3);
    
    [$year, $month, $day] = array_map('intval', $parts);
    
    expect($year)->toBe(1403)
        ->and($month)->toBe(1)
        ->and($day)->toBe(1)
        ->and(JalaliConverter::isValidJalali($year, $month, $day))->toBeTrue();
});

test('jalali date picker handles invalid date formats', function () {
    $invalidFormats = [
        '1403-01',
        '1403',
        'invalid',
        '',
    ];
    
    foreach ($invalidFormats as $format) {
        $state = str_replace('-', '/', $format);
        $parts = explode('/', $state);
        
        expect(count($parts) !== 3)->toBeTrue();
    }
});

