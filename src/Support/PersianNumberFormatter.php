<?php

namespace Alihoushy\FilamentPersianSuite\Support;

/**
 * Persian Number Formatter
 *
 * Converts English digits to Persian digits
 */
class PersianNumberFormatter
{
    /**
     * Persian digits mapping
     */
    private const PERSIAN_DIGITS = [
        '0' => '۰',
        '1' => '۱',
        '2' => '۲',
        '3' => '۳',
        '4' => '۴',
        '5' => '۵',
        '6' => '۶',
        '7' => '۷',
        '8' => '۸',
        '9' => '۹',
    ];

    /**
     * Format a number or string to Persian digits
     *
     * @param string|int|float $value The value to format
     * @return string Formatted string with Persian digits
     */
    public static function format(string|int|float $value): string
    {
        $value = (string) $value;

        return strtr($value, self::PERSIAN_DIGITS);
    }

    /**
     * Format a number with Persian digits and thousand separators
     *
     * @param int|float $number The number to format
     * @param int $decimals Number of decimal places
     * @return string Formatted string with Persian digits
     */
    public static function formatNumber(int|float $number, int $decimals = 0): string
    {
        $formatted = number_format($number, $decimals, '/', '،');

        return self::format($formatted);
    }
}

