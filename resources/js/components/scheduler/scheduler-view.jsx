// resources/js/Components/Scheduler/SchedulerView.jsx
import React, { useCallback, useMemo, useState } from "react";
import clsx from "clsx";
import DepartmentFilter from "@/components/scheduler/department-filter.jsx";
import {
    formatDisplayDate,
    formatDisplayWeekday,
    getDatesFromStart,
} from "@/lib/dates.js";
import EmployeeRow from "@/components/scheduler/employee-row.jsx";
import MultiShiftEditor from "@/components/scheduler/multi-shift-editor.jsx";
import { useSchedulerData } from "@/hooks/useSchedulerData.js";
import ScheduleEditor from "@/components/scheduler/scheduler-editor.jsx";

const SchedulerView = ({
    employees = [],
    assignedShifts = [],
    constraints = [],
    departments = [],
    initialStartDate = new Date(),
    numDays = 14,
}) => {
    const [selectedDepartment, setSelectedDepartment] = useState(null);
    const [editingCell, setEditingCell] = useState(null); // For single edit: { employeeId, date }
    const [selectedCells, setSelectedCells] = useState({}); // For multi-select: { 'empId-date': true, ... }
    const [isMultiEditorOpen, setIsMultiEditorOpen] = useState(false); // State for multi-edit modal

    // --- Data Processing Hooks ---
    const dateColumns = useMemo(() => {
        const start =
            initialStartDate instanceof Date
                ? initialStartDate
                : new Date(initialStartDate);
        return getDatesFromStart(start, numDays, "yyyy-MM-dd");
    }, [initialStartDate, numDays]);

    const { shiftsByEmployeeDate, findConstraintForEmployeeDate } =
        useSchedulerData(assignedShifts, constraints);

    const filteredEmployees = useMemo(() => {
        // ... (filtering logic remains the same)
        if (!selectedDepartment) {
            return employees;
        }
        return employees.filter((emp) =>
            typeof departments[0] === "string"
                ? emp.department === selectedDepartment
                : emp.department_id === selectedDepartment,
        );
    }, [employees, selectedDepartment, departments]);

    // --- Selection and Editor Logic ---

    // Updated handler for cell clicks
    const handleCellSelect = useCallback((employeeId, date, isMultiSelect) => {
        const cellKey = `${employeeId}-${date}`;

        if (isMultiSelect) {
            // Toggle selection for this cell
            setSelectedCells((prev) => {
                const updated = { ...prev };
                if (updated[cellKey]) {
                    delete updated[cellKey]; // Deselect
                } else {
                    updated[cellKey] = true; // Select
                }
                return updated;
            });
            // Don't open single editor when multi-selecting
            setEditingCell(null);
        } else {
            // Single select: Clear previous multi-selection and open single editor
            setSelectedCells({}); // Clear multi-select
            setEditingCell({ employeeId, date }); // Open single editor
        }
        // Close multi-editor if it was open and a cell is clicked
        setIsMultiEditorOpen(false);
    }, []); // Dependency array is empty as it only uses setters

    const handleCloseEditor = useCallback(() => {
        setEditingCell(null);
        // Decide if you want to clear multi-selection when single editor closes
        // setSelectedCells({}); // Optional: uncomment to clear multi-select on single edit close
    }, []);

    // --- Multi-Edit Modal Logic ---
    const openMultiEditor = useCallback(() => {
        if (Object.keys(selectedCells).length > 0) {
            setIsMultiEditorOpen(true);
            setEditingCell(null); // Ensure single editor is closed
        }
    }, [selectedCells]);

    const closeMultiEditor = useCallback(() => {
        setIsMultiEditorOpen(false);
        // Optionally clear selection when multi-editor closes after save/cancel
        // setSelectedCells({});
    }, []);

    // Prepare data for the multi-edit modal (memoized)
    const multiEditData = useMemo(() => {
        return Object.keys(selectedCells).map((key) => {
            const [employeeId, date] = key.split("-");
            return { employeeId: parseInt(employeeId, 10), date };
        });
    }, [selectedCells]);

    // --- Other Callbacks (Filter) ---
    const handleFilterChange = useCallback(
        (departmentId) => {
            setSelectedDepartment(
                departmentId
                    ? typeof departments[0] === "string"
                        ? departmentId
                        : parseInt(departmentId, 10)
                    : null,
            );
            setSelectedCells({}); // Clear selection when filter changes
            setEditingCell(null);
            setIsMultiEditorOpen(false);
        },
        [departments],
    );

    // Find data for the single editing cell (memoized)
    const editingCellData = useMemo(() => {
        if (!editingCell)
            return { initialShift: null, initialConstraint: null };
        const { employeeId, date } = editingCell;
        const shiftsMap = shiftsByEmployeeDate[employeeId] || {};
        const initialShift = shiftsMap[date] || null;
        const initialConstraint = findConstraintForEmployeeDate(
            employeeId,
            date,
        );
        return { initialShift, initialConstraint };
    }, [editingCell, shiftsByEmployeeDate, findConstraintForEmployeeDate]);

    // --- Rendering ---
    const gridColsStyle = `minmax(180px, 1.5fr) repeat(${dateColumns.length}, minmax(90px, 1fr))`; // Slightly wider cell min width maybe
    const numSelected = Object.keys(selectedCells).length;

    const gridMaxHeight = "max-h-[75vh]"; // Example: 75% of viewport height

    return (
        <div>
            {/* Filters & Actions Section */}
            <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
                <DepartmentFilter
                    departments={departments}
                    selectedDepartment={selectedDepartment}
                    onChange={handleFilterChange}
                />
                <div className="flex items-center gap-2">
                    {/* Button to trigger Multi-Shift Editor */}
                    <button
                        onClick={openMultiEditor}
                        disabled={numSelected === 0}
                        className={clsx(
                            "px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500",
                            numSelected === 0
                                ? "opacity-50 cursor-not-allowed"
                                : "opacity-100",
                        )}
                    >
                        Edit {numSelected > 0 ? `${numSelected} ` : ""}
                        Shifts...
                    </button>
                    {/* Add Date Range Picker/Navigation Here Later */}
                </div>
            </div>
            {/* Instructions for multi-select */}
            {numSelected === 0 && (
                <p className="text-xs text-gray-500 dark:text-gray-400 mb-3 italic">
                    Hint: Use Ctrl-Click (or Cmd-Click on Mac) to select
                    multiple cells for batch editing shifts.
                </p>
            )}
            {numSelected > 0 && (
                <p className="text-xs text-blue-600 dark:text-blue-400 mb-3 font-medium">
                    {numSelected} cell(s) selected. Click "Edit {numSelected}{" "}
                    Shifts..." above or Ctrl/Cmd-Click more cells. Click a cell
                    without Ctrl/Cmd to start single edit.
                </p>
            )}
            {/* Schedule Grid */}
            <div
                className={clsx(
                    "overflow-x-auto overflow-y-auto relative border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm",
                    gridMaxHeight,
                )}
            >
                <div
                    className="grid items-stretch dark:bg-gray-800 auto-rows-max"
                    style={{
                        gridTemplateColumns: gridColsStyle,
                        minWidth: "min-content",
                    }}
                >
                    {/* Header Row (remains the same) */}
                    <div className="sticky top-0 left-0 z-30 bg-gray-100 dark:bg-gray-800 border-b border-r border-gray-300 dark:border-gray-600 px-3 py-2 text-sm font-semibold text-gray-600 dark:text-gray-300 flex items-center justify-start">
                        Employee
                    </div>
                    {dateColumns.map((dateString) => (
                        <div
                            key={dateString}
                            className="sticky top-0 z-20 bg-gray-100 dark:bg-gray-800 border-b border-r border-gray-300 dark:border-gray-600 px-1 py-2 text-xs font-medium text-gray-500 dark:text-gray-400 text-center flex flex-col justify-center"
                        >
                            <div className="font-semibold text-gray-600 dark:text-gray-300">
                                {formatDisplayDate(dateString)}
                            </div>
                            <div className="text-gray-500 dark:text-gray-400">
                                {formatDisplayWeekday(dateString)}
                            </div>
                        </div>
                    ))}

                    {/* Employee Rows */}
                    {filteredEmployees.length > 0 ? (
                        filteredEmployees.map((employee) => {
                            const shiftsForEmp =
                                shiftsByEmployeeDate[employee.id] || {};
                            const findConstraintForRowDate = useCallback(
                                (dateStr) => {
                                    return findConstraintForEmployeeDate(
                                        employee.id,
                                        dateStr,
                                    );
                                },
                                [findConstraintForEmployeeDate, employee.id],
                            );

                            return (
                                <EmployeeRow
                                    key={employee.id}
                                    employee={employee}
                                    dates={dateColumns}
                                    shiftsForEmployee={shiftsForEmp}
                                    findConstraintForDate={
                                        findConstraintForRowDate
                                    }
                                    selectedCells={selectedCells} // Pass selection state object
                                    onCellSelect={handleCellSelect} // Pass updated handler
                                />
                            );
                        })
                    ) : (
                        <div className="col-span-full text-center py-10 text-gray-500 dark:text-gray-400">
                            No employees found matching the selected criteria.
                        </div>
                    )}
                </div>{" "}
                {/* End Grid */}
            </div>{" "}
            {/* End Scroll Container */}
            {/* Editor Modals/Drawers */}
            <ScheduleEditor // Single Cell Editor
                isOpen={!!editingCell}
                onClose={handleCloseEditor}
                cellData={editingCell}
                initialShift={editingCellData.initialShift}
                initialConstraint={editingCellData.initialConstraint}
                employees={employees}
            />
            <MultiShiftEditor // Multiple Cell Editor
                isOpen={isMultiEditorOpen}
                onClose={closeMultiEditor}
                selectedCellsData={multiEditData}
            />
        </div>
    );
};

export default SchedulerView;
