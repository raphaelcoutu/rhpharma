// resources/js/Utils/dates.js (or similar path)
import { addDays, eachDayOfInterval, format, parseISO } from "date-fns";
import { fr } from "date-fns/locale";

/**
 * Generates an array of date strings between startDate and endDate (inclusive)
 * @param {string|Date} start - The start date (ISO string or Date object)
 * @param {string|Date} end - The end date (ISO string or Date object)
 * @param {string} dateFormat - The desired output format (e.g., 'yyyy-MM-dd')
 * @returns {string[]} - Array of formatted date strings
 */
export function getDateRange(start, end, dateFormat = "yyyy-MM-dd") {
    try {
        const startDate = typeof start === "string" ? parseISO(start) : start;
        const endDate = typeof end === "string" ? parseISO(end) : end;
        const interval = eachDayOfInterval({ start: startDate, end: endDate });
        return interval.map((date) => format(date, dateFormat));
    } catch (error) {
        console.error("Error generating date range:", error);
        return [];
    }
}

/**
 * Generates an array of date strings starting from a date for a number of days
 * @param {string|Date} start - The start date (ISO string or Date object)
 * @param {number} numDays - The number of days to generate
 * @param {string} dateFormat - The desired output format (e.g., 'yyyy-MM-dd')
 * @returns {string[]} - Array of formatted date strings
 */
export function getDatesFromStart(start, numDays, dateFormat = "yyyy-MM-dd") {
    try {
        const startDate = typeof start === "string" ? parseISO(start) : start;
        const endDate = addDays(startDate, numDays - 1);
        return getDateRange(startDate, endDate, dateFormat);
    } catch (error) {
        console.error("Error generating dates from start:", error);
        return [];
    }
}

/**
 * Formats a date string for display
 * @param {string} dateString - ISO date string (e.g., 'yyyy-MM-dd')
 * @param {string} displayFormat - e.g., 'MMM d' (Oct 26)
 * @returns {string}
 */
export function formatDisplayDate(dateString, displayFormat = "MMM d") {
    try {
        return format(parseISO(dateString), displayFormat, { locale: fr });
    } catch {
        return dateString; // fallback
    }
}

/**
 * Formats a date string for weekday display
 * @param {string} dateString - ISO date string (e.g., 'yyyy-MM-dd')
 * @param {string} displayFormat - e.g., 'EEE' (Wed)
 * @returns {string}
 */
export function formatDisplayWeekday(dateString, displayFormat = "EEE") {
    try {
        return format(parseISO(dateString), displayFormat, { locale: fr });
    } catch {
        return ""; // fallback
    }
}
