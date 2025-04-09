// resources/js/Components/Scheduler/MultiShiftEditor.jsx
import React, { useState, useCallback, useEffect } from "react";
import { router } from "@inertiajs/react";
import clsx from "clsx";

// Basic Modal Structure - Replace with ShadCN/ui Dialog or Drawer
const MultiShiftEditor = ({ isOpen, onClose, selectedCellsData = [] }) => {
    // selectedCellsData: [{ employeeId, date }, ...]
    const [shiftCode, setShiftCode] = useState("");
    const [isLoading, setIsLoading] = useState(false);
    const [errors, setErrors] = useState({}); // For validation errors

    useEffect(() => {
        // Reset form when modal opens or selection changes significantly
        if (isOpen) {
            setShiftCode("");
            setErrors({});
        }
    }, [isOpen]); // Only reset when opening/closing, not on every data change while open

    const handleSave = useCallback(
        (e) => {
            e.preventDefault();
            if (!selectedCellsData.length || isLoading) return;

            setIsLoading(true);
            setErrors({}); // Clear previous errors

            const payload = {
                // Structure expected by the backend batch update endpoint
                updates: selectedCellsData.map((cell) => ({
                    employee_id: cell.employeeId,
                    date: cell.date,
                    shift_code: shiftCode.trim() || null, // Send null if empty to clear shifts
                    // We are NOT sending constraint_code here - this editor only handles shifts
                })),
            };

            // Use a dedicated batch update route
            router.put(route("schedule.batch-update"), payload, {
                // Replace with your actual route name
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => {
                    onClose(); // Close editor on success
                },
                onError: (err) => {
                    console.error("Batch update failed:", err);
                    setErrors(err); // Store validation errors
                },
                onFinish: () => {
                    setIsLoading(false);
                },
            });
        },
        [selectedCellsData, shiftCode, onClose, isLoading],
    );

    const handleClose = () => {
        if (!isLoading) {
            onClose();
        }
    };

    if (!isOpen) {
        return null;
    }

    return (
        <div className="fixed inset-0 bg-black bg-opacity-50 z-40 flex justify-end">
            <div className="bg-white dark:bg-gray-800 w-full max-w-md h-full shadow-xl p-6 overflow-y-auto">
                <div className="flex justify-between items-center mb-6">
                    <h2 className="text-xl font-semibold text-gray-900 dark:text-white">
                        Edit Multiple Shifts
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
                    <p className="text-sm text-gray-600 dark:text-gray-400">
                        Applying changes to{" "}
                        <strong>{selectedCellsData.length}</strong> selected
                        cell(s).
                    </p>
                    <p className="text-xs text-gray-500 dark:text-gray-500">
                        Note: This will overwrite existing shifts and may remove
                        conflicting constraints in the selected cells. Leave the
                        code blank to clear shifts.
                    </p>
                </div>

                <form onSubmit={handleSave}>
                    <div className="mb-4">
                        <label
                            htmlFor="multi-shift-code"
                            className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                        >
                            New Shift Code
                        </label>
                        <input
                            type="text"
                            id="multi-shift-code"
                            value={shiftCode}
                            onChange={(e) => setShiftCode(e.target.value)}
                            placeholder="e.g., D8, N12 (leave blank to clear)"
                            disabled={isLoading}
                            className={clsx(
                                "w-full px-3 py-2 border rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white disabled:opacity-50",
                                errors.updates
                                    ? "border-red-500"
                                    : "border-gray-300 dark:border-gray-600", // Highlight if general batch error
                            )}
                            aria-describedby={
                                errors.updates ? "multi-shift-error" : undefined
                            }
                        />
                        {errors.updates && ( // Display general batch update error
                            <p
                                className="mt-1 text-xs text-red-600 dark:text-red-400"
                                id="multi-shift-error"
                            >
                                {errors.updates}
                            </p>
                        )}
                        {/* You might get specific errors per item, e.g., errors['updates.0.shift_code'] - more complex to display */}
                    </div>

                    <div className="flex justify-end gap-3 mt-6">
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
                            disabled={isLoading || !selectedCellsData.length}
                            className="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            {isLoading
                                ? "Saving..."
                                : `Apply to ${selectedCellsData.length} Cells`}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    );
};

export default MultiShiftEditor;
