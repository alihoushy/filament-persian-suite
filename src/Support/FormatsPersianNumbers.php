<?php

namespace Alihoushy\FilamentPersianSuite\Support;

/**
 * Trait for formatting numbers to Persian digits
 */
trait FormatsPersianNumbers
{
    /**
     * Format a value to Persian digits
     *
     * @param string|int|float $value The value to format
     * @return string Formatted string with Persian digits
     */
    protected function formatPersianNumbers(string|int|float $value): string
    {
        return PersianNumberFormatter::format($value);
    }
}

