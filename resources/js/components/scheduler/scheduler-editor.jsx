// resources/js/Components/Scheduler/ScheduleEditor.jsx
import { router } from '@inertiajs/react'; // Or use the hook: import { useForm } from '@inertiajs/react';
import { useCallback, useEffect, useState } from 'react';

// Basic Modal Structure - Replace with ShadCN/ui Dialog or Drawer
const ScheduleEditor = ({ isOpen, onClose, cellData, initialShift, initialConstraint, employees }) => {
    // cellData: { employeeId, date }
    const [shiftCode, setShiftCode] = useState('');
    const [constraintCode, setConstraintCode] = useState('');
    const [isLoading, setIsLoading] = useState(false);

    const employee = employees?.find((e) => e.id === cellData?.employeeId);

    useEffect(() => {
        if (cellData) {
            // Reset form when cellData changes (new cell selected)
            setShiftCode(initialShift?.shift.code || '');
            setConstraintCode(initialConstraint?.code || '');
        } else {
            // Reset when closed
            setShiftCode('');
            setConstraintCode('');
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
            router.put(route('schedule.update'), payload, {
                // Replace 'schedule.update' with your actual route name
                preserveState: true, // Keep component state
                preserveScroll: true, // Keep scroll position
                onSuccess: () => {
                    onClose(); // Close editor on success
                },
                onError: (errors) => {
                    console.error('Save failed:', errors);
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
        <div className="fixed inset-0 z-40 flex justify-end bg-black bg-opacity-50">
            <div className="h-full w-full max-w-md overflow-y-auto bg-white p-6 shadow-xl dark:bg-gray-800">
                <div className="mb-6 flex items-center justify-between">
                    <h2 className="text-xl font-semibold text-gray-900 dark:text-white">Edit Schedule</h2>
                    <button
                        onClick={handleClose}
                        disabled={isLoading}
                        className="text-gray-400 hover:text-gray-600 disabled:opacity-50 dark:hover:text-gray-300"
                    >
                        × {/* Close Icon */}
                    </button>
                </div>

                <div className="mb-4">
                    <p>
                        <strong>Employee:</strong> {employee.firstname} {employee.lastname}
                    </p>
                    <p>
                        <strong>Date:</strong> {cellData.date}
                    </p>
                </div>

                <form onSubmit={handleSave}>
                    <div className="mb-4">
                        <label htmlFor="shift-code" className="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Assigned Shift Code
                        </label>
                        <input
                            type="text"
                            id="shift-code"
                            value={shiftCode}
                            onChange={(e) => setShiftCode(e.target.value)}
                            placeholder="e.g., D8, N12"
                            disabled={isLoading}
                            className="w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
                        />
                        {/* Consider clearing constraint if shift is entered */}
                    </div>

                    <div className="mb-6">
                        <label htmlFor="constraint-code" className="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Constraint Code
                        </label>
                        <input
                            type="text"
                            id="constraint-code"
                            value={constraintCode}
                            onChange={(e) => setConstraintCode(e.target.value)}
                            placeholder="e.g., RDO, PTO"
                            disabled={isLoading}
                            className="w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:opacity-50 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
                        />
                        {/* Consider clearing shift if constraint is entered */}
                        {/* Add fields for constraint start/end date if needed */}
                    </div>

                    <div className="flex justify-end gap-3">
                        <button
                            type="button"
                            onClick={handleClose}
                            disabled={isLoading}
                            className="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 dark:border-gray-500 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            disabled={isLoading}
                            className="rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            {isLoading ? 'Saving...' : 'Save Changes'}
                        </button>
                    </div>
                    {/* Display validation errors here */}
                </form>
            </div>
        </div>
    );
};

export default ScheduleEditor;
