<?php

use Alihoushy\FilamentPersianSuite\Support\FormatsPersianNumbers;
use Alihoushy\FilamentPersianSuite\Support\PersianNumberFormatter;

// Create a test class that uses the trait
class TestClassWithTrait
{
    use FormatsPersianNumbers;

    public function format($value): string
    {
        return $this->formatPersianNumbers($value);
    }
}

test('formats persian numbers trait works correctly', function () {
    $testClass = new TestClassWithTrait();
    
    expect($testClass->format(12345))->toBe('۱۲۳۴۵')
        ->and($testClass->format('12345'))->toBe('۱۲۳۴۵')
        ->and($testClass->format(0))->toBe('۰');
});

test('formats persian numbers trait uses formatter', function () {
    $testClass = new TestClassWithTrait();
    
    // The trait should use PersianNumberFormatter internally
    $direct = PersianNumberFormatter::format(12345);
    $viaTrait = $testClass->format(12345);
    
    expect($direct)->toBe($viaTrait);
});

