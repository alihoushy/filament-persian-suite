/**
 * Jalali Date Picker - In-House JavaScript Implementation
 *
 * Alpine.js component for handling Jalali (Persian) date picker
 * without any external dependencies.
 */

document.addEventListener('alpine:init', () => {
    Alpine.data('jalaliDatePickerComponent', (config) => ({
        state: config.state,
        displayFormat: config.displayFormat || 'Y/m/d',
        placeholder: config.placeholder || '',
        disabled: config.disabled || false,
        required: config.required || false,
        showCalendar: false,
        currentYear: null,
        currentMonth: null,
        displayValue: '',

        init() {
            this.updateDisplayValue();
            const today = this.getTodayJalali();
            this.currentYear = today.year;
            this.currentMonth = today.month;
        },

        updateDisplayValue() {
            if (!this.state) {
                this.displayValue = '';
                return;
            }

            // State is in Gregorian format (YYYY-MM-DD), convert to Jalali
            const parts = this.state.split('-');
            if (parts.length !== 3) {
                this.displayValue = this.state;
                return;
            }

            const gregorian = {
                year: parseInt(parts[0]),
                month: parseInt(parts[1]),
                day: parseInt(parts[2])
            };

            const jalali = this.gregorianToJalali(gregorian.year, gregorian.month, gregorian.day);
            this.displayValue = this.formatJalaliDate(jalali, this.displayFormat);
        },

        handleInput(event) {
            const value = event.target.value;
            // Parse Jalali date and convert to Gregorian
            const jalali = this.parseJalaliDate(value);
            if (jalali) {
                const gregorian = this.jalaliToGregorian(jalali.year, jalali.month, jalali.day);
                this.state = `${gregorian.year}-${String(gregorian.month).padStart(2, '0')}-${String(gregorian.day).padStart(2, '0')}`;
            }
        },

        toggleCalendar() {
            if (!this.disabled) {
                this.showCalendar = !this.showCalendar;
            }
        },

        get calendarHTML() {
            return this.generateCalendarHTML();
        },

        generateCalendarHTML() {
            const monthNames = ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'];
            const dayNames = ['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'];

            let html = `
                <div class="flex items-center justify-between mb-4">
                    <button type="button" @click="previousMonth" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">‹</button>
                    <div class="text-lg font-semibold">${monthNames[this.currentMonth - 1]} ${this.currentYear}</div>
                    <button type="button" @click="nextMonth" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">›</button>
                </div>
                <div class="grid grid-cols-7 gap-1 mb-2">
                    ${dayNames.map(day => `<div class="text-center text-sm font-medium text-gray-500 dark:text-gray-400">${day}</div>`).join('')}
                </div>
                <div class="grid grid-cols-7 gap-1">
                    ${this.generateCalendarDays()}
                </div>
            `;

            return html;
        },

        generateCalendarDays() {
            const daysInMonth = this.getDaysInJalaliMonth(this.currentYear, this.currentMonth);
            const firstDayOfWeek = this.getFirstDayOfWeek(this.currentYear, this.currentMonth);

            let html = '';
            
            // Empty cells for days before month starts
            for (let i = 0; i < firstDayOfWeek; i++) {
                html += '<div class="p-2"></div>';
            }

            // Days of the month
            for (let day = 1; day <= daysInMonth; day++) {
                const isToday = this.isToday(this.currentYear, this.currentMonth, day);
                const isSelected = this.isSelected(this.currentYear, this.currentMonth, day);
                
                html += `
                    <button
                        type="button"
                        @click="selectDate(${this.currentYear}, ${this.currentMonth}, ${day})"
                        class="p-2 text-center rounded hover:bg-primary-100 dark:hover:bg-primary-900 ${isToday ? 'bg-primary-50 dark:bg-primary-900/50' : ''} ${isSelected ? 'bg-primary-500 text-white' : ''}"
                    >
                        ${day}
                    </button>
                `;
            }

            return html;
        },

        selectDate(year, month, day) {
            const gregorian = this.jalaliToGregorian(year, month, day);
            this.state = `${gregorian.year}-${String(gregorian.month).padStart(2, '0')}-${String(gregorian.day).padStart(2, '0')}`;
            this.updateDisplayValue();
            this.showCalendar = false;
        },

        previousMonth() {
            if (this.currentMonth === 1) {
                this.currentMonth = 12;
                this.currentYear--;
            } else {
                this.currentMonth--;
            }
        },

        nextMonth() {
            if (this.currentMonth === 12) {
                this.currentMonth = 1;
                this.currentYear++;
            } else {
                this.currentMonth++;
            }
        },

        // Jalali conversion methods (in-house implementation)
        gregorianToJalali(year, month, day) {
            const julianDay = this.gregorianToJulianDay(year, month, day);
            return this.julianDayToJalali(julianDay);
        },

        jalaliToGregorian(year, month, day) {
            const julianDay = this.jalaliToJulianDay(year, month, day);
            return this.julianDayToGregorian(julianDay);
        },

        gregorianToJulianDay(year, month, day) {
            if (month < 3) {
                year -= 1;
                month += 12;
            }
            const a = Math.floor(year / 100);
            const b = 2 - a + Math.floor(a / 4);
            return Math.floor(365.25 * (year + 4716)) + Math.floor(30.6001 * (month + 1)) + day + b - 1524.5;
        },

        julianDayToGregorian(julianDay) {
            const j = julianDay + 32044;
            const g = Math.floor(j / 146097);
            const dg = j % 146097;
            const c = Math.floor((Math.floor(dg / 36524) + 1) * 3 / 4);
            const dc = dg - c * 36524;
            const b = Math.floor(dc / 1461);
            const db = dc % 1461;
            const a = Math.floor((Math.floor(db / 365) + 1) * 3 / 4);
            const da = db - a * 365;
            const y = g * 400 + c * 100 + b * 4 + a;
            const m = Math.floor(((da * 5) + 308) / 153) - 2;
            const d = da - Math.floor(((m + 4) * 153) / 5) + 122;
            const year = y - 4800 + Math.floor((m + 2) / 12);
            const month = ((m + 2) % 12) + 1;
            const day = d + 1;
            return { year, month, day };
        },

        jalaliToJulianDay(year, month, day) {
            const JALALI_EPOCH = 1948320;
            const baseDays = (year - 1) * 365;
            const leapDays = Math.floor(((year + 2346) * 8 + 21) / 33);
            let monthDays = 0;
            if (month > 1) {
                monthDays = 186;
                if (month > 7) {
                    monthDays += (month - 7) * 30;
                } else {
                    monthDays += (month - 1) * 31;
                }
            }
            return JALALI_EPOCH + baseDays + leapDays + monthDays + day - 1;
        },

        julianDayToJalali(julianDay) {
            const JALALI_EPOCH = 1948320;
            const daysSinceEpoch = julianDay - JALALI_EPOCH;
            const cycle33 = Math.floor(daysSinceEpoch / 12053);
            const remainingDays = daysSinceEpoch % 12053;
            let year = cycle33 * 33 + 1;
            const leapYears = Math.floor((remainingDays * 8 + 21) / 33);
            year += leapYears;
            const dayOfYear = remainingDays - leapYears * 365;
            let month, day;
            if (dayOfYear < 186) {
                month = Math.floor(dayOfYear / 31) + 1;
                day = (dayOfYear % 31) + 1;
            } else {
                month = Math.floor((dayOfYear - 186) / 30) + 7;
                day = ((dayOfYear - 186) % 30) + 1;
            }
            return { year, month, day };
        },

        isJalaliLeapYear(year) {
            const leapYears = [1, 5, 9, 13, 17, 21, 25, 29];
            const cyclePosition = (year + 2346) % 33;
            return leapYears.includes(cyclePosition);
        },

        getDaysInJalaliMonth(year, month) {
            if (month <= 6) return 31;
            if (month <= 11) return 30;
            return this.isJalaliLeapYear(year) ? 30 : 29;
        },

        getFirstDayOfWeek(year, month) {
            const julianDay = this.jalaliToJulianDay(year, month, 1);
            return (julianDay + 2) % 7;
        },

        getTodayJalali() {
            const today = new Date();
            return this.gregorianToJalali(today.getFullYear(), today.getMonth() + 1, today.getDate());
        },

        isToday(year, month, day) {
            const today = this.getTodayJalali();
            return today.year === year && today.month === month && today.day === day;
        },

        isSelected(year, month, day) {
            if (!this.state) return false;
            const parts = this.state.split('-');
            if (parts.length !== 3) return false;
            const gregorian = {
                year: parseInt(parts[0]),
                month: parseInt(parts[1]),
                day: parseInt(parts[2])
            };
            const jalali = this.gregorianToJalali(gregorian.year, gregorian.month, gregorian.day);
            return jalali.year === year && jalali.month === month && jalali.day === day;
        },

        parseJalaliDate(dateString) {
            const cleaned = dateString.replace(/-/g, '/');
            const parts = cleaned.split('/');
            if (parts.length !== 3) return null;
            const year = parseInt(parts[0]);
            const month = parseInt(parts[1]);
            const day = parseInt(parts[2]);
            if (isNaN(year) || isNaN(month) || isNaN(day)) return null;
            if (month < 1 || month > 12) return null;
            if (day < 1 || day > this.getDaysInJalaliMonth(year, month)) return null;
            return { year, month, day };
        },

        formatJalaliDate(jalali, format) {
            format = format.replace('Y', String(jalali.year));
            format = format.replace('m', String(jalali.month).padStart(2, '0'));
            format = format.replace('d', String(jalali.day).padStart(2, '0'));
            return format;
        }
    }));
});

