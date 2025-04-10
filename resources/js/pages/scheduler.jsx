import SchedulerView from '@/components/scheduler/scheduler-view';
import { Head } from '@inertiajs/react';
import { differenceInDays } from 'date-fns';

const constraints = [
    {
        id: 1,
        employee_id: 49,
        start_date: '2019-05-15',
        end_date: '2019-05-20',
        code: 'AL',
    },
];
const departments = [];

export default function Scheduler({ users, schedule, shifts }) {
    const numDays = differenceInDays(schedule.end_date, schedule.start_date) + 1;

    return (
        <div>
            <Head title="Employee Scheduler" />
            <div className="py-12">
                <div className="mx-auto max-w-full sm:px-6 lg:px-8">
                    <div className={`overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg`}>
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
