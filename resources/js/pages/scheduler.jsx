import { Head } from "@inertiajs/react";
import SchedulerView from "@/components/scheduler/scheduler-view.jsx";
import { differenceInDays } from "date-fns";

const constraints = [
    {
        id: 1,
        employee_id: 49,
        start_date: "2025-04-09T00:00:00.000Z",
        end_date: "2025-04-12",
        code: "AL",
    },
];
const departments = [];

export default function Scheduler({ users, schedule, shifts }) {
    const numDays =
        differenceInDays(schedule.end_date, schedule.start_date) + 1;

    return (
        <div>
            <Head title="Employee Scheduler" />
            <div className="py-12">
                <div className="max-w-full mx-auto sm:px-6 lg:px-8">
                    <div
                        className={`bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg`}
                    >
                        <SchedulerView
                            employees={users}
                            assignedShifts={shifts}
                            constraints={constraints}
                            departments={departments}
                            initialStartDate={schedule.start_date} // Optional prop from backend
                            numDays={numDays}
                        />
                    </div>
                </div>
            </div>
        </div>
    );
}
