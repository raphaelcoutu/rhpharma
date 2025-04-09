import { Head } from "@inertiajs/react";
import SchedulerView from "@/components/scheduler/scheduler-view.jsx";

const assignedShifts = [
    {
        id: 1,
        employee_id: 1,
        date: "2025-04-10",
        code: "8HF",
    },
];
const constraints = [
    {
        id: 1,
        employee_id: 1,
        start_date: "2025-04-09T00:00:00.000Z",
        end_date: "2025-04-12",
        code: "AL",
    },
];
const departments = [];
const initialStartDate = new Date();

export default function Scheduler({ users }) {
    return (
        <div>
            <Head title="Employee Scheduler" />
            <div className="py-12">
                <div className="max-w-full mx-auto sm:px-6 lg:px-8">
                    {" "}
                    {/* Use max-w-full or similar for wide tables */}
                    <div className="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <p>Scheduler</p>
                        <SchedulerView
                            employees={users}
                            assignedShifts={assignedShifts}
                            constraints={constraints}
                            departments={departments}
                            initialStartDate={initialStartDate} // Optional prop from backend
                            // numDays={21} // Optionally override default number of days
                        />
                    </div>
                </div>
            </div>
        </div>
    );
}
