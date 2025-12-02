<?php

namespace Alihoushy\FilamentPersianSuite\Forms\Components;

use Alihoushy\FilamentPersianSuite\Jalali\JalaliConverter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Support\Carbon;

/**
 * Jalali Date Picker Component
 *
 * A custom date picker that displays and handles Jalali (Persian) dates
 * while storing Gregorian dates in the database.
 */
class JalaliDatePicker extends DatePicker
{
    protected string $view = 'filament-persian-suite::components.jalali-date-picker';

    /**
     * Setup the component to handle Jalali date conversion
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
                $date = Carbon::parse($state);
                $jalali = JalaliConverter::dateTimeToJalali($date);

                return sprintf('%04d/%02d/%02d', $jalali['year'], $jalali['month'], $jalali['day']);
            } catch (\Exception $e) {
                return $state;
            }
        });

        // Convert Jalali (Input) to Gregorian (DB) when saving
        $this->mutateStateUsing(function ($state) {
            if (blank($state)) {
                return null;
            }

            // Parse Jalali date string (format: YYYY/MM/DD or YYYY-MM-DD)
            $state = str_replace('-', '/', (string) $state);
            $parts = explode('/', $state);

            if (count($parts) !== 3) {
                return null;
            }

            [$year, $month, $day] = array_map('intval', $parts);

            try {
                if (! JalaliConverter::isValidJalali($year, $month, $day)) {
                    return null;
                }

                $date = JalaliConverter::jalaliToDateTime($year, $month, $day);

                return $date->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        });

        // Set default display format
        $this->displayFormat('Y/m/d');
    }
}

