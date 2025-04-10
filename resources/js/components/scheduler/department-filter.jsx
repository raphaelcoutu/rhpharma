// resources/js/Components/Scheduler/DepartmentFilter.jsx

// Basic Select - Replace with ShadCN/ui Select if you install it
const DepartmentFilter = ({ departments, selectedDepartment, onChange, allLabel = 'All Departments' }) => {
    return (
        <div className="mb-4">
            <label htmlFor="department-filter" className="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                Filter by Department
            </label>
            <select
                id="department-filter"
                value={selectedDepartment || ''}
                onChange={(e) => onChange(e.target.value || null)} // Send null if "All" is selected
                className="block w-full max-w-xs rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
            >
                <option value="">{allLabel}</option>
                {(departments || []).map((dept) =>
                    // Assuming departments are strings or objects with id/name
                    typeof dept === 'string' ? (
                        <option key={dept} value={dept}>
                            {dept}
                        </option>
                    ) : (
                        <option key={dept.id} value={dept.id}>
                            {dept.name}
                        </option>
                    ),
                )}
            </select>
        </div>
    );
};

export default DepartmentFilter;
