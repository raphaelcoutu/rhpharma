// resources/js/Components/Scheduler/EmployeeRow.jsx
import React, { memo, useCallback } from "react";
import ScheduleCell from "./scheduler-cell.jsx";

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
                {" "}
                {/* Use contents to make this part of the parent grid */}
                {/* Employee Name Cell (Sticky) */}
                <div className="sticky left-0 z-10 bg-white dark:bg-gray-900 border-b border-r border-gray-200 dark:border-gray-700 px-3 py-3 flex items-center text-sm font-medium text-gray-700 dark:text-gray-300 min-h-[3.5rem]">
                    {employee.firstname} {employee.lastname}
                </div>
                {/* Date Cells */}
                {dates.map((dateString) => {
                    const shift = shiftsForEmployee?.[dateString] || null;
                    const constraint = findConstraintForDate(dateString);
                    const cellKey = `${employee.id}-${dateString}`;
                    const isSelected = !!selectedCells[cellKey]; // Check if this cell is selected

                    return (
                        <ScheduleCell
                            key={cellKey}
                            shift={shift}
                            constraint={constraint}
                            isSelected={isSelected} // Pass selection state
                            onCellClick={(event) =>
                                handleCellClick(dateString, event)
                            } // Pass event up
                        />
                    );
                })}
            </div>
        );
    },
);

export default EmployeeRow;
