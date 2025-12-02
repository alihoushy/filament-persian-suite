<?php

namespace Alihoushy\FilamentPersianSuite\Tables\Columns;

use Alihoushy\FilamentPersianSuite\Jalali\JalaliConverter;
use Alihoushy\FilamentPersianSuite\Support\FormatsPersianNumbers;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Carbon;

/**
 * Jalali Date Column
 *
 * A custom table column that displays Jalali (Persian) dates
 * from Gregorian dates stored in the database.
 */
class JalaliDateColumn extends TextColumn
{
    use FormatsPersianNumbers;

    protected string $format = 'Y/m/d';

    protected bool $includeTime = false;

    protected string $timeFormat = 'H:i';

    /**
     * Setup the column to handle Jalali date conversion
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Convert Gregorian (DB) to Jalali (Display) when displaying
        $this->formatStateUsing(function ($state) {
            if (blank($state)) {
                return null;
            }

            try {
                $date = $state instanceof \DateTimeInterface
                    ? Carbon::instance($state)
                    : Carbon::parse($state);

                $jalali = JalaliConverter::dateTimeToJalali($date);

                $formatted = JalaliConverter::format($this->format, $date);

                // Convert numbers to Persian
                $formatted = $this->formatPersianNumbers($formatted);

                // Add time if requested
                if ($this->includeTime) {
                    $time = $date->format($this->timeFormat);
                    $formatted .= ' ' . $this->formatPersianNumbers($time);
                }

                return $formatted;
            } catch (\Exception $e) {
                return $state;
            }
        });
    }

    /**
     * Set the date format
     */
    public function format(string $format): static
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Include time in the display
     */
    public function withTime(string $format = 'H:i'): static
    {
        $this->includeTime = true;
        $this->timeFormat = $format;

        return $this;
    }

    /**
     * Set format to show full date with month name
     */
    public function withMonthName(): static
    {
        return $this->format('d M Y');
    }

    /**
     * Set format to show short date
     */
    public function short(): static
    {
        return $this->format('Y/m/d');
    }

    /**
     * Set format to show long date
     */
    public function long(): static
    {
        return $this->format('D d M Y');
    }
}

