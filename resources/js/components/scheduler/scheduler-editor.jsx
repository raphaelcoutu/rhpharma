// resources/js/Components/Scheduler/ScheduleEditor.jsx
import React, { useState, useEffect, useCallback } from "react";
import { router } from "@inertiajs/react"; // Or use the hook: import { useForm } from '@inertiajs/react';

// Basic Modal Structure - Replace with ShadCN/ui Dialog or Drawer
const ScheduleEditor = ({
    isOpen,
    onClose,
    cellData,
    initialShift,
    initialConstraint,
    employees,
}) => {
    // cellData: { employeeId, date }
    const [shiftCode, setShiftCode] = useState("");
    const [constraintCode, setConstraintCode] = useState("");
    const [isLoading, setIsLoading] = useState(false);

    const employee = employees?.find((e) => e.id === cellData?.employeeId);

    useEffect(() => {
        if (cellData) {
            // Reset form when cellData changes (new cell selected)
            setShiftCode(initialShift?.code || "");
            setConstraintCode(initialConstraint?.code || "");
        } else {
            // Reset when closed
            setShiftCode("");
            setConstraintCode("");
        }
    }, [cellData, initialShift, initialConstraint]);

    const handleSave = useCallback(
        (e) => {
            e.preventDefault();
            if (!cellData || isLoading) return;

            setIsLoading(true);

            const payload = {
                employee_id: cellData.employeeId,
                date: cellData.date,
                shift_code: shiftCode.trim() || null, // Send null if empty
                constraint_code: constraintCode.trim() || null, // Send null if empty
                // You might need start/end dates for constraints
            };

            // Example using Inertia.put - adjust route/method as needed
            router.put(route("schedule.update"), payload, {
                // Replace 'schedule.update' with your actual route name
                preserveState: true, // Keep component state
                preserveScroll: true, // Keep scroll position
                onSuccess: () => {
                    onClose(); // Close editor on success
                },
                onError: (errors) => {
                    console.error("Save failed:", errors);
                    // Handle validation errors (display them)
                },
                onFinish: () => {
                    setIsLoading(false);
                },
            });
        },
        [cellData, shiftCode, constraintCode, onClose, isLoading],
    );

    const handleClose = () => {
        if (!isLoading) {
            onClose();
        }
    };

    if (!isOpen || !cellData || !employee) {
        return null;
    }

    // Basic Modal Styling (replace with Drawer/Dialog)
    return (
        <div className="fixed inset-0 bg-black bg-opacity-50 z-40 flex justify-end">
            <div className="bg-white dark:bg-gray-800 w-full max-w-md h-full shadow-xl p-6 overflow-y-auto">
                <div className="flex justify-between items-center mb-6">
                    <h2 className="text-xl font-semibold text-gray-900 dark:text-white">
                        Edit Schedule
                    </h2>
                    <button
                        onClick={handleClose}
                        disabled={isLoading}
                        className="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 disabled:opacity-50"
                    >
                        × {/* Close Icon */}
                    </button>
                </div>

                <div className="mb-4">
                    <p>
                        <strong>Employee:</strong> {employee.firstname}{" "}
                        {employee.lastname}
                    </p>
                    <p>
                        <strong>Date:</strong> {cellData.date}
                    </p>
                </div>

                <form onSubmit={handleSave}>
                    <div className="mb-4">
                        <label
                            htmlFor="shift-code"
                            className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                        >
                            Assigned Shift Code
                        </label>
                        <input
                            type="text"
                            id="shift-code"
                            value={shiftCode}
                            onChange={(e) => setShiftCode(e.target.value)}
                            placeholder="e.g., D8, N12"
                            disabled={isLoading}
                            className="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white disabled:opacity-50"
                        />
                        {/* Consider clearing constraint if shift is entered */}
                    </div>

                    <div className="mb-6">
                        <label
                            htmlFor="constraint-code"
                            className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                        >
                            Constraint Code
                        </label>
                        <input
                            type="text"
                            id="constraint-code"
                            value={constraintCode}
                            onChange={(e) => setConstraintCode(e.target.value)}
                            placeholder="e.g., RDO, PTO"
                            disabled={isLoading}
                            className="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white disabled:opacity-50"
                        />
                        {/* Consider clearing shift if constraint is entered */}
                        {/* Add fields for constraint start/end date if needed */}
                    </div>

                    <div className="flex justify-end gap-3">
                        <button
                            type="button"
                            onClick={handleClose}
                            disabled={isLoading}
                            className="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-600 dark:text-gray-200 dark:border-gray-500 dark:hover:bg-gray-500 disabled:opacity-50"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            disabled={isLoading}
                            className="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            {isLoading ? "Saving..." : "Save Changes"}
                        </button>
                    </div>
                    {/* Display validation errors here */}
                </form>
            </div>
        </div>
    );
};

export default ScheduleEditor;
