<?php

namespace Alihoushy\FilamentPersianSuite\Jalali;

use DateTimeInterface;
use InvalidArgumentException;

/**
 * Jalali Date Converter - In-House Implementation
 *
 * Converts between Gregorian (Miladi) and Jalali (Shamsi) dates
 * without any external dependencies.
 */
class JalaliConverter
{
    // Constants for Jalali calendar
    private const JALALI_EPOCH = 1948320; // Julian day number for 1 Farvardin 1

    // Jalali month names in Persian
    private const MONTH_NAMES = [
        1 => 'فروردین',
        2 => 'اردیبهشت',
        3 => 'خرداد',
        4 => 'تیر',
        5 => 'مرداد',
        6 => 'شهریور',
        7 => 'مهر',
        8 => 'آبان',
        9 => 'آذر',
        10 => 'دی',
        11 => 'بهمن',
        12 => 'اسفند',
    ];

    // Day names in Persian
    private const DAY_NAMES = [
        'شنبه',
        'یکشنبه',
        'دوشنبه',
        'سه‌شنبه',
        'چهارشنبه',
        'پنج‌شنبه',
        'جمعه',
    ];

    /**
     * Convert Gregorian date to Jalali
     *
     * @param int $year Gregorian year
     * @param int $month Gregorian month (1-12)
     * @param int $day Gregorian day (1-31)
     * @return array{year: int, month: int, day: int} Jalali date array
     * @throws InvalidArgumentException
     */
    public static function toJalali(int $year, int $month, int $day): array
    {
        if (! self::isValidGregorian($year, $month, $day)) {
            throw new InvalidArgumentException("Invalid Gregorian date: {$year}-{$month}-{$day}");
        }

        $julianDay = self::gregorianToJulianDay($year, $month, $day);
        return self::julianDayToJalali($julianDay);
    }

    /**
     * Convert Jalali date to Gregorian
     *
     * @param int $year Jalali year
     * @param int $month Jalali month (1-12)
     * @param int $day Jalali day (1-31)
     * @return array{year: int, month: int, day: int} Gregorian date array
     * @throws InvalidArgumentException
     */
    public static function toGregorian(int $year, int $month, int $day): array
    {
        if (! self::isValidJalali($year, $month, $day)) {
            throw new InvalidArgumentException("Invalid Jalali date: {$year}-{$month}-{$day}");
        }

        $julianDay = self::jalaliToJulianDay($year, $month, $day);
        return self::julianDayToGregorian($julianDay);
    }

    /**
     * Format Jalali date string
     *
     * @param string $format Format string (Y=year, m=month, d=day, M=month name, D=day name)
     * @param DateTimeInterface|null $date DateTime to convert (defaults to now)
     * @return string Formatted Jalali date string
     */
    public static function format(string $format, ?DateTimeInterface $date = null): string
    {
        if ($date === null) {
            $date = new \DateTime();
        }

        $jalali = self::toJalali(
            (int) $date->format('Y'),
            (int) $date->format('m'),
            (int) $date->format('d')
        );

        $result = $format;
        $result = str_replace('Y', (string) $jalali['year'], $result);
        $result = str_replace('m', str_pad((string) $jalali['month'], 2, '0', STR_PAD_LEFT), $result);
        $result = str_replace('d', str_pad((string) $jalali['day'], 2, '0', STR_PAD_LEFT), $result);
        $result = str_replace('M', self::MONTH_NAMES[$jalali['month']] ?? '', $result);
        $result = str_replace('D', self::getDayName($jalali['year'], $jalali['month'], $jalali['day']), $result);

        return $result;
    }

    /**
     * Validate Jalali date
     *
     * @param int $year Jalali year
     * @param int $month Jalali month (1-12)
     * @param int $day Jalali day (1-31)
     * @return bool True if valid Jalali date
     */
    public static function isValidJalali(int $year, int $month, int $day): bool
    {
        if ($year < 1 || $year > 3000) {
            return false;
        }

        if ($month < 1 || $month > 12) {
            return false;
        }

        $daysInMonth = self::getDaysInJalaliMonth($year, $month);

        return $day >= 1 && $day <= $daysInMonth;
    }

    /**
     * Check if year is leap year in Jalali calendar
     */
    private static function isJalaliLeapYear(int $year): bool
    {
        $leapYears = [1, 5, 9, 13, 17, 21, 25, 29];
        $cyclePosition = ($year + 2346) % 33;

        return in_array($cyclePosition, $leapYears, true);
    }

    /**
     * Get number of days in a Jalali month
     */
    private static function getDaysInJalaliMonth(int $year, int $month): int
    {
        if ($month <= 6) {
            return 31;
        }

        if ($month <= 11) {
            return 30;
        }

        // Esfand (month 12)
        return self::isJalaliLeapYear($year) ? 30 : 29;
    }

    /**
     * Convert Gregorian date to Julian Day Number
     */
    private static function gregorianToJulianDay(int $year, int $month, int $day): int
    {
        if ($month < 3) {
            $year -= 1;
            $month += 12;
        }

        $a = (int) ($year / 100);
        $b = 2 - $a + (int) ($a / 4);

        return (int) ((365.25 * ($year + 4716)) + (30.6001 * ($month + 1)) + $day + $b - 1524.5);
    }

    /**
     * Convert Julian Day Number to Gregorian date
     */
    private static function julianDayToGregorian(int $julianDay): array
    {
        $j = $julianDay + 32044;
        $g = (int) ($j / 146097);
        $dg = $j % 146097;
        $c = ((int) (($dg / 36524) + 1) * 3) / 4;
        $dc = $dg - ($c * 36524);
        $b = (int) ($dc / 1461);
        $db = $dc % 1461;
        $a = ((int) (($db / 365) + 1) * 3) / 4;
        $da = $db - ($a * 365);
        $y = ($g * 400) + ($c * 100) + ($b * 4) + $a;
        $m = (int) ((($da * 5) + 308) / 153) - 2;
        $d = $da - ((($m + 4) * 153) / 5) + 122;
        $year = (int) ($y - 4800 + (($m + 2) / 12));
        $month = (int) (($m + 2) % 12 + 1);
        $day = (int) ($d + 1);

        return ['year' => $year, 'month' => $month, 'day' => $day];
    }

    /**
     * Convert Jalali date to Julian Day Number
     */
    private static function jalaliToJulianDay(int $year, int $month, int $day): int
    {
        $baseDays = ($year - 1) * 365;
        $leapDays = (int) ((($year + 2346) * 8 + 21) / 33);

        $monthDays = 0;
        if ($month > 1) {
            $monthDays = 186;
            if ($month > 7) {
                $monthDays += ($month - 7) * 30;
            } else {
                $monthDays += ($month - 1) * 31;
            }
        }

        return self::JALALI_EPOCH + $baseDays + $leapDays + $monthDays + $day - 1;
    }

    /**
     * Convert Julian Day Number to Jalali date
     */
    private static function julianDayToJalali(int $julianDay): array
    {
        $daysSinceEpoch = $julianDay - self::JALALI_EPOCH;

        $cycle33 = (int) ($daysSinceEpoch / 12053);
        $remainingDays = $daysSinceEpoch % 12053;

        $year = ($cycle33 * 33) + 1;
        $leapYears = (int) ((($remainingDays * 8) + 21) / 33);

        $year += $leapYears;
        $dayOfYear = $remainingDays - ($leapYears * 365);

        if ($dayOfYear < 186) {
            $month = (int) ($dayOfYear / 31) + 1;
            $day = ($dayOfYear % 31) + 1;
        } else {
            $month = (int) (($dayOfYear - 186) / 30) + 7;
            $day = (($dayOfYear - 186) % 30) + 1;
        }

        return ['year' => $year, 'month' => $month, 'day' => $day];
    }

    /**
     * Validate Gregorian date
     */
    private static function isValidGregorian(int $year, int $month, int $day): bool
    {
        if ($year < 1 || $year > 3000) {
            return false;
        }

        if ($month < 1 || $month > 12) {
            return false;
        }

        return checkdate($month, $day, $year);
    }

    /**
     * Get day name in Persian
     */
    private static function getDayName(int $year, int $month, int $day): string
    {
        $julianDay = self::jalaliToJulianDay($year, $month, $day);
        $dayOfWeek = ($julianDay + 2) % 7; // 0 = Saturday, 1 = Sunday, etc.

        return self::DAY_NAMES[$dayOfWeek] ?? '';
    }

    /**
     * Convert DateTime to Jalali array
     */
    public static function dateTimeToJalali(DateTimeInterface $date): array
    {
        return self::toJalali(
            (int) $date->format('Y'),
            (int) $date->format('m'),
            (int) $date->format('d')
        );
    }

    /**
     * Convert Jalali array to DateTime
     */
    public static function jalaliToDateTime(int $year, int $month, int $day): \DateTime
    {
        $gregorian = self::toGregorian($year, $month, $day);

        return new \DateTime(
            sprintf('%04d-%02d-%02d', $gregorian['year'], $gregorian['month'], $gregorian['day'])
        );
    }
}

