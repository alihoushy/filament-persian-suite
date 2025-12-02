<?php

namespace Alihoushy\FilamentPersianSuite\Tables\Filters;

use Alihoushy\FilamentPersianSuite\Forms\Components\JalaliDatePicker;
use Alihoushy\FilamentPersianSuite\Jalali\JalaliConverter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

/**
 * Jalali Date Range Filter
 *
 * A custom filter that allows filtering by Jalali date range
 * while querying Gregorian dates in the database.
 */
class JalaliDateRangeFilter extends Filter
{
    protected string $column;

    /**
     * Create a new filter instance
     */
    public static function make(string $name, ?string $column = null): static
    {
        $filter = parent::make($name);
        $filter->column = $column ?? $name;

        return $filter;
    }

    /**
     * Setup the filter
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->form([
            JalaliDatePicker::make('from')
                ->label('از تاریخ')
                ->placeholder('از تاریخ'),
            JalaliDatePicker::make('until')
                ->label('تا تاریخ')
                ->placeholder('تا تاریخ'),
        ]);

        $this->query(function (Builder $query, array $data): Builder {
            return $query
                ->when(
                    $data['from'],
                    fn (Builder $query, $date): Builder => $query->whereDate($this->column, '>=', $this->convertJalaliToGregorian($date)),
                )
                ->when(
                    $data['until'],
                    fn (Builder $query, $date): Builder => $query->whereDate($this->column, '<=', $this->convertJalaliToGregorian($date)),
                );
        });
    }

    /**
     * Convert Jalali date string to Gregorian date string
     */
    protected function convertJalaliToGregorian(string $jalaliDate): string
    {
        // Parse Jalali date string (format: YYYY/MM/DD or YYYY-MM-DD)
        $jalaliDate = str_replace('-', '/', $jalaliDate);
        $parts = explode('/', $jalaliDate);

        if (count($parts) !== 3) {
            throw new \InvalidArgumentException("Invalid Jalali date format: {$jalaliDate}");
        }

        [$year, $month, $day] = array_map('intval', $parts);

        try {
            $dateTime = JalaliConverter::jalaliToDateTime($year, $month, $day);

            return $dateTime->format('Y-m-d');
        } catch (\Exception $e) {
            throw new \InvalidArgumentException("Invalid Jalali date: {$jalaliDate}");
        }
    }

    /**
     * Set the column to filter on
     */
    public function column(string $column): static
    {
        $this->column = $column;

        return $this;
    }
}

