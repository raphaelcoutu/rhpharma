import clsx from 'clsx'; // Utility for conditional classes: npm install clsx
import { memo } from 'react';

const ScheduleCell = memo(
    ({
        shift,
        constraint,
        isSelected, // New prop: boolean
        onCellClick, // Will now receive the event object
        isWeekend,
    }) => {
        const cellClasses = clsx(
            `relative flex cursor-pointer flex-col items-center justify-center overflow-hidden whitespace-nowrap border-b border-r border-gray-200 px-1 py-1 text-center text-xs dark:border-gray-700`, // Apply overflow to spans now
            {
                'z-[5] bg-blue-100 ring-2 ring-blue-400 dark:bg-blue-900': isSelected, // Style for selected cells
                'bg-slate-50': isWeekend && !isSelected,
                'hover:bg-blue-50 dark:hover:bg-gray-800': !isSelected, // Hover only if not selected
            },
        );

        const shiftCode = shift?.shift.code;
        const constraintCode = constraint?.code;

        // Tooltip shows both if present
        const tooltip = [shiftCode, constraintCode].filter(Boolean).join(' / ');

        return (
            <div
                className={cellClasses}
                onClick={onCellClick} // Pass the raw event up
                title={tooltip}
            >
                {/* Render Shift Code */}
                {shiftCode && (
                    <span className="block w-full overflow-hidden text-ellipsis px-0.5 text-sm font-medium text-gray-700 dark:text-gray-200">
                        {shiftCode}
                    </span>
                )}

                {/* Render Constraint Code (different color) */}
                {constraintCode && (
                    // Add top margin if shift is also present
                    <span
                        className={clsx('block w-full overflow-hidden text-ellipsis px-0.5 font-normal text-orange-600 dark:text-orange-400', {
                            'mt-0.5': shiftCode,
                        })}
                    >
                        {constraintCode}
                    </span>
                )}

                {/* Simple placeholder if empty */}
                {!shiftCode && !constraintCode && <span className="text-gray-300 dark:text-gray-600">-</span>}

                {/* Optional: Keep indicators if needed, adjust positioning */}
                {/* {constraint && !shift && ( ... )} */}
                {/* {shift && ( ... )} */}
            </div>
        );
    },
);

export default ScheduleCell;
