// resources/js/hooks/use-scheduler-data.js
import { useCallback, useMemo } from "react";
import { format, isWithinInterval, parseISO } from "date-fns"; // Make sure date-fns is installed

/**
 * Pre-processes scheduler data for efficient lookup.
 * @param {Array} assignedShifts - Raw assigned shifts array
 * @param {Array} constraints - Raw constraints array
 * @returns {Object} - { shiftsByEmployeeDate, constraintsByEmployeeDate }
 */
export function useSchedulerData(assignedShifts = [], constraints = []) {
    // Memoize the shift lookup map: { employeeId: { 'YYYY-MM-DD': shift } }
    const shiftsByEmployeeDate = useMemo(() => {
        const map = {};
        (assignedShifts || []).forEach((shift) => {
            if (!shift || !shift.user_id || !shift.date) return; // Basic validation
            if (!map[shift.user_id]) {
                map[shift.user_id] = {};
            }
            // Assuming one shift per employee per day for simplicity
            // Adjust if multiple shifts are possible
            map[shift.user_id][format(shift.date, "yyyy-MM-dd")] = shift;
        });
        return map;
    }, [assignedShifts]);

    // Memoize the constraint lookup map: { employeeId: { 'YYYY-MM-DD': constraint } }
    // Handles date ranges for constraints
    const constraintsByEmployeeDate = useMemo(() => {
        const map = {};
        (constraints || []).forEach((constraint) => {
            if (
                !constraint ||
                !constraint.employee_id ||
                !constraint.start_date ||
                !constraint.end_date
            )
                return; // Basic validation

            try {
                const startDate = parseISO(constraint.start_date);
                const endDate = parseISO(constraint.end_date);
                const interval = { start: startDate, end: endDate };

                // Iterate through dates in the constraint range (efficiently if possible)
                // For simplicity here, we'll check during lookup, but pre-calculating might be better for huge ranges/many constraints
                // Pre-calculation example (requires getDateRange):
                // const datesInRange = getDateRange(startDate, endDate);
                // datesInRange.forEach(dateStr => { ... });

                if (!map[constraint.user_id]) {
                    map[constraint.user_id] = []; // Store constraints as an array per employee
                }
                // Store the constraint object along with its interval for later checking
                map[constraint.user_id].push({ ...constraint, interval });
            } catch (error) {
                console.error(
                    "Error processing constraint date range:",
                    constraint,
                    error,
                );
            }
        });

        // Now create the final lookup map for quick access by date
        const finalMap = {};
        Object.keys(map).forEach((employeeId) => {
            finalMap[employeeId] = {};
            map[employeeId].forEach((constraintInfo) => {
                // This still requires checking the interval at lookup time
                // To optimize further, you'd expand the dates here if performance demands it
                // For now, we just store the constraint definitions per employee
                // A lookup function will be needed in the cell component
            });
        });

        // Revised approach: Store constraints relevant *within the view's date range* only during lookup
        // We'll actually do the check *inside* the component needing it (EmployeeRow/ScheduleCell)
        // So, this memo just organizes constraints by employee for easier filtering later.
        const organizedConstraints = {};
        (constraints || []).forEach((constraint) => {
            if (!constraint || !constraint.user_id) return;
            if (!organizedConstraints[constraint.user_id]) {
                organizedConstraints[constraint.user_id] = [];
            }
            try {
                // Store with pre-parsed dates for efficiency
                organizedConstraints[constraint.user_id].push({
                    ...constraint,
                    startDateObj: parseISO(constraint.start_date),
                    endDateObj: parseISO(constraint.end_date),
                });
            } catch (e) {
                console.error("Invalid date in constraint", constraint);
            }
        });

        return organizedConstraints; // { employeeId: [constraint1, constraint2] }
    }, [constraints]);

    /**
     * Finds the relevant constraint for a specific employee and date.
     * @param {number|string} employeeId
     * @param {string} dateString - 'YYYY-MM-DD'
     * @returns {Object|null} - The constraint object or null
     */
    const findConstraintForEmployeeDate = useCallback(
        (employeeId, dateString) => {
            const employeeConstraints = constraintsByEmployeeDate[employeeId];
            if (!employeeConstraints || employeeConstraints.length === 0) {
                return null;
            }

            try {
                const targetDate = parseISO(dateString);
                // Find the *first* matching constraint for this date (adjust logic if needed)
                return (
                    employeeConstraints.find((c) =>
                        isWithinInterval(targetDate, {
                            start: c.startDateObj,
                            end: c.endDateObj,
                        }),
                    ) || null
                );
            } catch (error) {
                console.error(
                    "Error finding constraint for date:",
                    employeeId,
                    dateString,
                    error,
                );
                return null;
            }
        },
        [constraintsByEmployeeDate],
    );

    return { shiftsByEmployeeDate, findConstraintForEmployeeDate };
}
