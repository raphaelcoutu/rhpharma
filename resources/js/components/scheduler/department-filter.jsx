// resources/js/Components/Scheduler/DepartmentFilter.jsx
import React from 'react';

// Basic Select - Replace with ShadCN/ui Select if you install it
const DepartmentFilter = ({ departments, selectedDepartment, onChange, allLabel = "All Departments" }) => {
    return (
        <div className="mb-4">
            <label htmlFor="department-filter" className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Filter by Department
            </label>
            <select
                id="department-filter"
                value={selectedDepartment || ''}
                onChange={(e) => onChange(e.target.value || null)} // Send null if "All" is selected
                className="block w-full max-w-xs pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            >
                <option value="">{allLabel}</option>
                {(departments || []).map((dept) => (
                    // Assuming departments are strings or objects with id/name
                    typeof dept === 'string'
                        ? <option key={dept} value={dept}>{dept}</option>
                        : <option key={dept.id} value={dept.id}>{dept.name}</option>
                ))}
            </select>
        </div>
    );
};

export default DepartmentFilter;
