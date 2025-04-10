// resources/js/Components/Scheduler/SchedulerView.jsx
import DepartmentFilter from '@/components/scheduler/department-filter';
import EmployeeRow from '@/components/scheduler/employee-row';
import MultiShiftEditor from '@/components/scheduler/multi-shift-editor';
import ScheduleEditor from '@/components/scheduler/scheduler-editor';
import { useSchedulerData } from '@/hooks/useSchedulerData';
import { formatDisplayDate, formatDisplayWeekday, getDatesFromStart } from '@/lib/dates';
import clsx from 'clsx';
import { useCallback, useMemo, useState } from 'react';

const SchedulerView = ({ employees = [], assignedShifts = [], constraints = [], departments = [], initialStartDate = new Date(), numDays = 14 }) => {
    const [selectedDepartment, setSelectedDepartment] = useState(null);
    const [editingCell, setEditingCell] = useState(null); // For single edit: { employeeId, date }
    const [selectedCells, setSelectedCells] = useState({}); // For multi-select: { 'empId-date': true, ... }
    const [isMultiEditorOpen, setIsMultiEditorOpen] = useState(false); // State for multi-edit modal

    // --- Data Processing Hooks ---
    const dateColumns = useMemo(() => {
        const start = initialStartDate instanceof Date ? initialStartDate : new Date(initialStartDate);
        return getDatesFromStart(start, numDays, 'yyyy-MM-dd');
    }, [initialStartDate, numDays]);

    const { shiftsByEmployeeDate, findConstraintForEmployeeDate } = useSchedulerData(assignedShifts, constraints);

    const filteredEmployees = useMemo(() => {
        // ... (filtering logic remains the same)
        if (!selectedDepartment) {
            return employees;
        }
        return employees.filter((emp) =>
            typeof departments[0] === 'string' ? emp.department === selectedDepartment : emp.department_id === selectedDepartment,
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
            const [employeeId, date] = key.split('-');
            return { employeeId: parseInt(employeeId, 10), date };
        });
    }, [selectedCells]);

    // --- Other Callbacks (Filter) ---
    const handleFilterChange = useCallback(
        (departmentId) => {
            setSelectedDepartment(departmentId ? (typeof departments[0] === 'string' ? departmentId : parseInt(departmentId, 10)) : null);
            setSelectedCells({}); // Clear selection when filter changes
            setEditingCell(null);
            setIsMultiEditorOpen(false);
        },
        [departments],
    );

    // Find data for the single editing cell (memoized)
    const editingCellData = useMemo(() => {
        if (!editingCell) return { initialShift: null, initialConstraint: null };
        const { employeeId, date } = editingCell;
        const shiftsMap = shiftsByEmployeeDate[employeeId] || {};
        const initialShift = shiftsMap[date] || null;
        const initialConstraint = findConstraintForEmployeeDate(employeeId, date);
        return { initialShift, initialConstraint };
    }, [editingCell, shiftsByEmployeeDate, findConstraintForEmployeeDate]);

    // --- Rendering ---
    const gridColsStyle = `minmax(180px, 1.5fr) repeat(${dateColumns.length}, minmax(90px, 1fr))`; // Slightly wider cell min width maybe
    const numSelected = Object.keys(selectedCells).length;

    const gridMaxHeight = 'max-h-[75vh]'; // Example: 75% of viewport height

    return (
        <div>
            {/* Filters & Actions Section */}
            <div className="mb-4 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                <DepartmentFilter departments={departments} selectedDepartment={selectedDepartment} onChange={handleFilterChange} />
                <div className="flex items-center gap-2">
                    {/* Button to trigger Multi-Shift Editor */}
                    <button
                        onClick={openMultiEditor}
                        disabled={numSelected === 0}
                        className={clsx(
                            'rounded-md border border-transparent bg-purple-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2',
                            numSelected === 0 ? 'cursor-not-allowed opacity-50' : 'opacity-100',
                        )}
                    >
                        Edit {numSelected > 0 ? `${numSelected} ` : ''}
                        Shifts...
                    </button>
                    {/* Add Date Range Picker/Navigation Here Later */}
                </div>
            </div>
            {/* Instructions for multi-select */}
            {numSelected === 0 && (
                <p className="mb-3 text-xs italic text-gray-500 dark:text-gray-400">
                    Hint: Use Ctrl-Click (or Cmd-Click on Mac) to select multiple cells for batch editing shifts.
                </p>
            )}
            {numSelected > 0 && (
                <p className="mb-3 text-xs font-medium text-blue-600 dark:text-blue-400">
                    {numSelected} cell(s) selected. Click "Edit {numSelected} Shifts..." above or Ctrl/Cmd-Click more cells. Click a cell without
                    Ctrl/Cmd to start single edit.
                </p>
            )}
            {/* Schedule Grid */}
            <div
                className={clsx(
                    'relative overflow-x-auto overflow-y-auto rounded-lg border border-gray-200 shadow-sm dark:border-gray-700',
                    gridMaxHeight,
                )}
            >
                <div
                    className="grid auto-rows-max items-stretch dark:bg-gray-800"
                    style={{
                        gridTemplateColumns: gridColsStyle,
                        minWidth: 'min-content',
                    }}
                >
                    {/* Header Row (remains the same) */}
                    <div className="sticky left-0 top-0 z-30 flex items-center justify-start border-b border-r border-gray-300 bg-gray-100 px-3 py-2 text-sm font-semibold text-gray-600 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300">
                        Employee
                    </div>
                    {dateColumns.map((dateString) => (
                        <div
                            key={dateString}
                            className="sticky top-0 z-20 flex flex-col justify-center border-b border-r border-gray-300 bg-gray-100 px-1 py-2 text-center text-xs font-medium text-gray-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400"
                        >
                            <div className="font-semibold text-gray-600 dark:text-gray-300">{formatDisplayDate(dateString)}</div>
                            <div className="text-gray-500 dark:text-gray-400">{formatDisplayWeekday(dateString)}</div>
                        </div>
                    ))}

                    {/* Employee Rows */}
                    {filteredEmployees.length > 0 ? (
                        filteredEmployees.map((employee) => {
                            const shiftsForEmp = shiftsByEmployeeDate[employee.id] || {};
                            const findConstraintForRowDate = useCallback(
                                (dateStr) => {
                                    return findConstraintForEmployeeDate(employee.id, dateStr);
                                },
                                [findConstraintForEmployeeDate, employee.id],
                            );

                            return (
                                <EmployeeRow
                                    key={employee.id}
                                    employee={employee}
                                    dates={dateColumns}
                                    shiftsForEmployee={shiftsForEmp}
                                    findConstraintForDate={findConstraintForRowDate}
                                    selectedCells={selectedCells} // Pass selection state object
                                    onCellSelect={handleCellSelect} // Pass updated handler
                                />
                            );
                        })
                    ) : (
                        <div className="col-span-full py-10 text-center text-gray-500 dark:text-gray-400">
                            No employees found matching the selected criteria.
                        </div>
                    )}
                </div>{' '}
                {/* End Grid */}
            </div>{' '}
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
