// resources/js/Components/Scheduler/EmployeeRow.jsx
import ScheduleCell from '@/components/scheduler/scheduler-cell';
import { isWeekend, parseISO } from 'date-fns';
import { memo, useCallback } from 'react';

const EmployeeRow = memo(
    ({
        employee,
        dates, // Array of 'YYYY-MM-DD' strings
        shiftsForEmployee, // Pre-looked up: { 'YYYY-MM-DD': shift }
        findConstraintForDate, // Function: (dateString) => constraint | null
        selectedCells, // New prop: Object mapping 'empId-date' to true
        onCellSelect, // Renamed for clarity: (employeeId, dateString, isMultiSelect) => void
    }) => {
        // The click handler now determines if it's a multi-select or single-select action
        const handleCellClick = useCallback(
            (dateString, event) => {
                const isMultiSelect = event.ctrlKey || event.metaKey; // Check Ctrl (Win/Linux) or Cmd (Mac)
                onCellSelect(employee.id, dateString, isMultiSelect);
            },
            [employee.id, onCellSelect],
        );

        return (
            <div className="contents">
                {/* Use contents to make this part of the parent grid */}
                {/* Employee Name Cell (Sticky) */}
                <div className="sticky left-0 z-20 flex min-h-[3.5rem] items-center border-b border-r border-gray-200 bg-white px-3 py-3 text-sm font-medium text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                    {employee.firstname}
                    {employee.lastname}
                </div>
                {/* Date Cells */}
                {dates.map((dateString) => {
                    const shift = shiftsForEmployee?.[dateString] || null;
                    const constraint = findConstraintForDate(dateString);
                    const cellKey = `${employee.id}-${dateString}`;
                    const isSelected = !!selectedCells[cellKey]; // Check if this cell is selected
                    const isWeekendDate = isWeekend(parseISO(dateString));

                    return (
                        <ScheduleCell
                            key={cellKey}
                            shift={shift}
                            constraint={constraint}
                            isSelected={isSelected} // Pass selection state
                            isWeekend={isWeekendDate}
                            onCellClick={(event) => handleCellClick(dateString, event)} // Pass event up
                        />
                    );
                })}
            </div>
        );
    },
);

export default EmployeeRow;
